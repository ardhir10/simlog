<?php

namespace App\Http\Controllers;

use App\ApprovalProcess;
use App\BarangKeluar;
use App\BarangPersediaan;
use App\FileLaporanDistribusi;
use App\LaporanDistribusi;
use App\PermintaanBarang;
use App\PermintaanBarangDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{

    public function index()
    {
        $data['page_title'] = "Persetujuan Permintaan";

        if ((Auth::user()->role->name ?? null) == 'Kepala Distrik Navigasi') {
            $data['permintaan_barang'] = PermintaanBarang::orderBy('id', 'desc')
            ->get();
        } else if ((Auth::user()->role->name ?? null) == 'Pengelola Gudang') {
            $data['permintaan_barang'] = PermintaanBarang::orderBy('id', 'desc')
            ->get();
        } else if ((Auth::user()->role->name ?? null) == 'Kabid Logistik') {
            $data['permintaan_barang'] = PermintaanBarang::orderBy('id', 'desc')
                ->get();
        } else if (
            (Auth::user()->role->name ?? null) == 'Admin SIMLOG' ||
            (Auth::user()->role->name ?? null) == 'Kasie Pengadaan' ||
            (Auth::user()->role->name ?? null) == 'Bendahara Materil'

            ) {
            $data['permintaan_barang'] = PermintaanBarang::orderBy('id', 'desc')
            ->get();
        } else if ((Auth::user()->role->name ?? null) == 'Kurir/Offsetter') {
            $data['permintaan_barang'] = PermintaanBarang::whereHas('approvals', function ($q) {
                $q->where('approve_by_id', Auth::user()->id);
            })->orderBy('id', 'desc')
                ->get();
        } else {
            $data['permintaan_barang'] = PermintaanBarang::where('user_id', Auth::user()->id)
                ->orderBy('id', 'desc')
                ->get();
        }

        return view('approval.index', $data);
    }
    public function review($id)
    {
        $permintaanBarang =
            PermintaanBarang::where('id', $id)
            ->with(["timeline" => function ($query) {
                    // return $query
                    // ->distinct('step');
                    $query->where(function($q){
                        $q->where('kategori','APPROVAL')
                        ->where('status','!=','done')
                        ->orwhere('step',1);
                    })
                    ->orwhere('kategori','PERSETUJUAN')
                    ->orderBy('id','asc');
                }])
            ->with(["approvals" => function ($query) {
                    // return $query
                    // ->distinct('step');
                    $query
                    ->orderBy('id','asc');
                }])
                ->first();
        $data['page_title'] = $permintaanBarang->nomor_nota_dinas;
        $data['data'] = $permintaanBarang;
        if (Auth::user()->role->name == 'Kepala Distrik Navigasi') {
            return view('approval-kadisnav.review', $data);
        }else if (Auth::user()->role->name == 'Kabid Logistik') {
            return view('approval-kabid-logistik.review', $data);
        } else if (Auth::user()->role->name == 'Pengelola Gudang') {
            return view('approval-kepala-gudang.review', $data);
        } else if (Auth::user()->role->name == 'Kasie Pengadaan') {
            return view('approval-kasie-pengadaan.review', $data);
        } else if (Auth::user()->role->name == 'Bendahara Materil') {
            return view('approval-bendahara-materil.review', $data);
        } else if (Auth::user()->role->name == 'Kurir/Offsetter') {
            return view('approval-kurir.review', $data);
        }
        else {
            # code...
        }

    }

    public function tindakLanjut(Request $request,$id){
        $permintaanBarang = PermintaanBarang::find($id);

        try {
            DB::beginTransaction();
            // update approval sebelumnya
            ApprovalProcess::where('permintaan_barang_id', $id)
            ->orderBy('id', 'desc')
            ->first()
            ->update([
                'status' => 'done',
                'tindak_lanjut' => $request->tindak_lanjut,
                'approve_by_id' => Auth::user()->id,
                'keterangan' => $request->keterangan
            ]);

            // PERSETUJUAN PROCESS 2
            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['permintaan_barang_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $permintaanBarang->user_id;
            $dataPersetujuan['user_peminta_name'] = $permintaanBarang->user->name ?? '';



            $dataPersetujuan['role_to_name'] = Auth::user()->role->name;

            $dataPersetujuan['type'] = 'Disetujui Kadisnav';
            $dataPersetujuan['status'] = 'done';

            $dataPersetujuan['step'] = 2;
            $dataPersetujuan['keterangan'] = $request->keterangan;

            $dataPersetujuan['tindak_lanjut'] = $request->tindak_lanjut;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            ApprovalProcess::create($dataPersetujuan);


            // APPROVAL PROCESS 2
            $dataApproval['timestamp'] = date('Y-m-d H:i:s');
            $dataApproval['permintaan_barang_id'] = $id;
            $dataApproval['user_peminta_id'] = $permintaanBarang->user_id;
            $dataApproval['user_peminta_name'] = $permintaanBarang->user->name ?? '';


            if ($request->tindak_lanjut == 'SETUJUI') {

                if (Auth::user()->role->name == 'Kepala Distrik Navigasi') {
                    $dataApproval['role_to_name'] = 'Kabid Logistik';

                    // Udate Barang Diminta Sesuai Request
                    $barangDiminta = $permintaanBarang->barang_diminta;
                    foreach ($barangDiminta as $key => $value) {
                        PermintaanBarangDetail::where('id',$value->id)
                            ->update(['jumlah_disetujui'=>$value->jumlah]);

                        // GENERATE UPP4
                        PermintaanBarang::where('id',$id)
                            ->update(['nomor_upp4'=>$this->generateNomorUpp4($permintaanBarang->nomor_nota_dinas)]);
                    }
                } else {
                    dd('NOT YET AVAILABLE ! 2');
                }
            } else {
                dd('NOT YET AVAILABLE !');
            }

            $dataApproval['type'] = 'Menunggu Persetujuan Kabid Logistik';
            $dataApproval['status'] = '';

            $dataApproval['step'] = 2;
            $dataApproval['keterangan'] = $request->keterangan;

            $dataApproval['tindak_lanjut'] = $request->tindak_lanjut;
            $dataApproval['approve_by_id'] = Auth::user()->id;
            $dataApproval['kategori'] = 'APPROVAL';
            ApprovalProcess::create($dataApproval);

            DB::commit();
            return redirect()->route('approval.review',$permintaanBarang->id)->with(['success' => 'Data Berhasil diajukan !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('approval.review',$permintaanBarang->id)->with(['failed' => $th->getMessage()]);
        }

    }

    public function kabidLogistikSetuju(Request $request, $id)
    {
        $permintaanBarang = PermintaanBarang::find($id);

        try {
            DB::beginTransaction();

            ApprovalProcess::where('permintaan_barang_id', $id)
            ->where('type', 'Menunggu Persetujuan Kabid Logistik')
            ->orderBy('id', 'desc')
            ->first()
            ->update([
                'status' => 'done',
                'approve_by_id' => Auth::user()->id,
            ]);


            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['permintaan_barang_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $permintaanBarang->user_id;
            $dataPersetujuan['user_peminta_name'] = $permintaanBarang->user->name ?? '';
            $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';
            $dataPersetujuan['type'] = 'Disetujui Kabid Logistik';
            $dataPersetujuan['status'] = 'done';
            $dataPersetujuan['step'] = 0;
            $dataPersetujuan['keterangan'] = $request->keterangan;
            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            ApprovalProcess::create($dataPersetujuan);


            $dataApproval['timestamp'] = date('Y-m-d H:i:s');
            $dataApproval['permintaan_barang_id'] = $id;
            $dataApproval['user_peminta_id'] = $permintaanBarang->user_id;
            $dataApproval['user_peminta_name'] = $permintaanBarang->user->name ?? '';
            $dataApproval['role_to_name'] = 'Kasie Pengadaan' ?? '';
            $dataApproval['type'] = 'Menunggu Persetujuan Kasie Pengadaan';
            $dataApproval['status'] = '';
            $dataApproval['step'] = 0;
            $dataApproval['keterangan'] = $request->keterangan;
            $dataApproval['tindak_lanjut'] = null;
            $dataApproval['approve_by_id'] = 0;
            $dataApproval['kategori'] = 'APPROVAL';
            ApprovalProcess::create($dataApproval);

            // UPDATE STATUS PERMINTAAN BARANG


            DB::commit();
            return redirect()->route('approval.review', $permintaanBarang->id)->with(['success' => 'Data Berhasil Disetujui !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('approval.review', $permintaanBarang->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function kasiePengadaanSetuju(Request $request, $id)
    {
        $permintaanBarang = PermintaanBarang::find($id);

        try {
            DB::beginTransaction();

            ApprovalProcess::where('permintaan_barang_id', $id)
                ->where('type', 'Menunggu Persetujuan Kasie Pengadaan')
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',
                    'approve_by_id' => Auth::user()->id,
                ]);


            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['permintaan_barang_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $permintaanBarang->user_id;
            $dataPersetujuan['user_peminta_name'] = $permintaanBarang->user->name ?? '';
            $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';
            $dataPersetujuan['type'] = 'Disetujui Kasie Pengadaan';
            $dataPersetujuan['status'] = 'done';
            $dataPersetujuan['step'] = 0;
            $dataPersetujuan['keterangan'] = $request->keterangan;
            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            ApprovalProcess::create($dataPersetujuan);


            $dataApproval['timestamp'] = date('Y-m-d H:i:s');
            $dataApproval['permintaan_barang_id'] = $id;
            $dataApproval['user_peminta_id'] = $permintaanBarang->user_id;
            $dataApproval['user_peminta_name'] = $permintaanBarang->user->name ?? '';
            $dataApproval['role_to_name'] = 'Bendahara Materil' ?? '';
            $dataApproval['type'] = 'Menunggu Persetujuan Bendahara Materil';
            $dataApproval['status'] = '';
            $dataApproval['step'] = 0;
            $dataApproval['keterangan'] = $request->keterangan;
            $dataApproval['tindak_lanjut'] = null;
            $dataApproval['approve_by_id'] = 0;
            $dataApproval['kategori'] = 'APPROVAL';
            ApprovalProcess::create($dataApproval);



            DB::commit();
            return redirect()->route('approval.review', $permintaanBarang->id)->with(['success' => 'Data Berhasil Disetujui !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('approval.review', $permintaanBarang->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function bendaharaMaterilSetuju(Request $request, $id)
    {
        $permintaanBarang = PermintaanBarang::find($id);

        try {
            DB::beginTransaction();

            ApprovalProcess::where('permintaan_barang_id', $id)
                ->where('type', 'Menunggu Persetujuan Bendahara Materil')
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',
                    'approve_by_id' => Auth::user()->id,
                ]);


            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['permintaan_barang_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $permintaanBarang->user_id;
            $dataPersetujuan['user_peminta_name'] = $permintaanBarang->user->name ?? '';
            $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';
            $dataPersetujuan['type'] = 'Disetujui Bendahara Materil';
            $dataPersetujuan['status'] = 'done';
            $dataPersetujuan['step'] = 0;
            $dataPersetujuan['keterangan'] = $request->keterangan;
            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            ApprovalProcess::create($dataPersetujuan);


            $dataApproval['timestamp'] = date('Y-m-d H:i:s');
            $dataApproval['permintaan_barang_id'] = $id;
            $dataApproval['user_peminta_id'] = $permintaanBarang->user_id;
            $dataApproval['user_peminta_name'] = $permintaanBarang->user->name ?? '';
            $dataApproval['role_to_name'] = 'Pengelola Gudang' ?? '';
            $dataApproval['type'] = 'Menunggu Persetujuan Pengelola Gudang';
            $dataApproval['status'] = '';
            $dataApproval['step'] = 0;
            $dataApproval['keterangan'] = $request->keterangan;
            $dataApproval['tindak_lanjut'] = null;
            $dataApproval['approve_by_id'] = 0;
            $dataApproval['kategori'] = 'APPROVAL';
            ApprovalProcess::create($dataApproval);


            DB::commit();
            return redirect()->route('approval.review', $permintaanBarang->id)->with(['success' => 'Data Berhasil Disetujui !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('approval.review', $permintaanBarang->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function pengelolaGudangSiap(Request $request, $id)
    {
        $permintaanBarang = PermintaanBarang::find($id);

        try {
            DB::beginTransaction();

            ApprovalProcess::where('permintaan_barang_id', $id)
                ->where('type', 'Menunggu Persetujuan Pengelola Gudang')
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',
                    'approve_by_id' => Auth::user()->id,
                ]);
            PermintaanBarang::where('id', $id)
            ->update(['status' => 'Disetujui']);

            // $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            // $dataPersetujuan['permintaan_barang_id'] = $id;
            // $dataPersetujuan['user_peminta_id'] = $permintaanBarang->user_id;
            // $dataPersetujuan['user_peminta_name'] = $permintaanBarang->user->name ?? '';
            // $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';
            // $dataPersetujuan['type'] = 'Disetujui Pengelola Gudang';
            // $dataPersetujuan['status'] = 'done';
            // $dataPersetujuan['step'] = 0;
            // $dataPersetujuan['keterangan'] = $request->keterangan;
            // $dataPersetujuan['tindak_lanjut'] = null;
            // $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            // $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            // ApprovalProcess::create($dataPersetujuan);

            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['permintaan_barang_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $permintaanBarang->user_id;
            $dataPersetujuan['user_peminta_name'] = $permintaanBarang->user->name ?? '';
            $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';
            $dataPersetujuan['type'] = 'Pesanan Telah disiapkan Pengelola Gudang';
            $dataPersetujuan['status'] = 'done';
            $dataPersetujuan['step'] = 0;
            $dataPersetujuan['keterangan'] = $request->keterangan;
            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            ApprovalProcess::create($dataPersetujuan);


            $dataApproval['timestamp'] = date('Y-m-d H:i:s');
            $dataApproval['permintaan_barang_id'] = $id;
            $dataApproval['user_peminta_id'] = $permintaanBarang->user_id;
            $dataApproval['user_peminta_name'] = $permintaanBarang->user->name ?? '';
            $dataApproval['role_to_name'] = 'Pengelola Gudang' ?? '';
            $dataApproval['type'] = 'Menunggu Barang Diserahkan';
            $dataApproval['status'] = '';
            $dataApproval['step'] = 0;
            $dataApproval['keterangan'] = $request->keterangan;
            $dataApproval['tindak_lanjut'] = null;
            $dataApproval['approve_by_id'] = 0;
            $dataApproval['kategori'] = 'APPROVAL';
            ApprovalProcess::create($dataApproval);




            DB::commit();
            return redirect()->route('approval.review', $permintaanBarang->id)->with(['success' => 'Pesanan Sudah Siap !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('approval.review', $permintaanBarang->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function serahkanBarang(Request $request, $id)
    {
        $permintaanBarang = PermintaanBarang::find($id);

        try {
            DB::beginTransaction();

            ApprovalProcess::where('permintaan_barang_id', $id)
                ->where('type', 'Menunggu Barang Diserahkan')
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',
                    'approve_by_id' => Auth::user()->id,
                ]);


            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['permintaan_barang_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $permintaanBarang->user_id;
            $dataPersetujuan['user_peminta_name'] = $permintaanBarang->user->name ?? '';
            $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';
            $dataPersetujuan['type'] = 'Barang Telah diserahkan Pengelola Gudang';
            $dataPersetujuan['status'] = 'done';
            $dataPersetujuan['step'] = 0;
            $dataPersetujuan['keterangan'] = 'Barang sudah diserahkan mohon konfirmasi barang diterima.';
            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            ApprovalProcess::create($dataPersetujuan);

            $dataApproval['timestamp'] = date('Y-m-d H:i:s');
            $dataApproval['permintaan_barang_id'] = $id;
            $dataApproval['user_peminta_id'] = $permintaanBarang->user_id;
            $dataApproval['user_peminta_name'] = $permintaanBarang->user->name ?? '';
            $dataApproval['role_to_name'] = $permintaanBarang->user->role->name ?? '';
            $dataApproval['type'] = 'Menunggu Barang Diterima';
            $dataApproval['status'] = '';
            $dataApproval['step'] = 0;
            $dataApproval['keterangan'] = $request->keterangan;
            $dataApproval['tindak_lanjut'] = null;
            $dataApproval['approve_by_id'] = 0;
            $dataApproval['kategori'] = 'APPROVAL';
            ApprovalProcess::create($dataApproval);


            DB::commit();
            return redirect()->route('approval.review', $permintaanBarang->id)->with(['success' => 'Barang berhasil diserahkan !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('approval.review', $permintaanBarang->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function terimaBarang(Request $request, $id)
    {
        $permintaanBarang = PermintaanBarang::find($id);

        try {
            DB::beginTransaction();

            ApprovalProcess::where('permintaan_barang_id', $id)
                ->where('type', 'Menunggu Barang Diterima')
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',
                    'approve_by_id' => Auth::user()->id,
                ]);


            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['permintaan_barang_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $permintaanBarang->user_id;
            $dataPersetujuan['user_peminta_name'] = $permintaanBarang->user->name ?? '';
            $dataPersetujuan['role_to_name'] = '' ?? '';
            $dataPersetujuan['type'] = 'Barang Telah diterima';
            $dataPersetujuan['status'] = 'done';
            $dataPersetujuan['step'] = 0;
            $dataPersetujuan['keterangan'] = 'Selesai';
            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            ApprovalProcess::create($dataPersetujuan);


            foreach ($permintaanBarang->barang_diminta as $key => $value) {

                $barangPersediaan = BarangPersediaan::where('id', $value->barang_persediaan_id)
                    ->first();


                BarangKeluar::create([
                    'timestamp'=> date('Y-m-d H:i:s'),
                    'barang_keluar_id'=> $value->barang_persediaan_id,
                    'permintaan_id'=> $id,
                    'harga_perolehan'=> $barangPersediaan->harga_perolehan,
                    'jumlah'=> $value->jumlah,
                    'tahun_perolehan'=> $barangPersediaan->tahun_perolehan,
                    'sub_sub_kategori'=> $barangPersediaan->sub_sub_kategori,
                ]);

            }



            // UPDATE STATUS PERMINTAAN BARANG
            PermintaanBarang::where('id', $id)
                ->update([
                    'bast_at'=>date('Y-m-d H:i:s'),
                    'nomor_bast'=>$this->generateNomorBast(),
                    'status' => 'Selesai']);
            DB::commit();
            return redirect()->route('permintaan-barang.detail', $permintaanBarang->id)->with(['success' => 'Sukses Terima barang !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('permintaan-barang.detail', $permintaanBarang->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function pengelolaGudangsetuju(Request $request, $id)
    {
        $permintaanBarang = PermintaanBarang::find($id);

        try {
            DB::beginTransaction();


            // PERSETUJUAN PROCESS 2
            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['permintaan_barang_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $permintaanBarang->user_id;
            $dataPersetujuan['user_peminta_name'] = $permintaanBarang->user->name ?? '';



            $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';


            $dataPersetujuan['type'] = 'Disetujui Pengelola Gudang';
            $dataPersetujuan['status'] = 'done';

            $dataPersetujuan['step'] = 3;
            $dataPersetujuan['keterangan'] = $request->keterangan;

            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            ApprovalProcess::create($dataPersetujuan);


            // APPROVAL PROCESS 2
            $dataApproval['timestamp'] = date('Y-m-d H:i:s');
            $dataApproval['permintaan_barang_id'] = $id;
            $dataApproval['user_peminta_id'] = $permintaanBarang->user_id;
            $dataApproval['user_peminta_name'] = $permintaanBarang->user->name ?? '';


            $dataApproval['role_to_name'] = 'Kasie Pengadaan' ?? '';


            $dataApproval['type'] = 'Disetujui Pengelola Gudang';
            $dataApproval['status'] = 'done';

            $dataApproval['step'] = 3;
            $dataApproval['keterangan'] = $request->keterangan;

            $dataApproval['tindak_lanjut'] = null;
            $dataApproval['approve_by_id'] = Auth::user()->id;
            $dataApproval['kategori'] = 'APPROVAL';
            ApprovalProcess::create($dataApproval);

            // UPDATE STATUS PERMINTAAN BARANG


            DB::commit();
            return redirect()->route('approval.review',$permintaanBarang->id)->with(['success' => 'Data Berhasil Disetujui !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('approval.review', $permintaanBarang->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function kirimKurir(Request $request, $id)
    {
        $permintaanBarang = PermintaanBarang::find($id);

        try {
            DB::beginTransaction();

            ApprovalProcess::where('permintaan_barang_id', $id)
                ->where('type', 'Menunggu Barang Diterima')
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',
                    'approve_by_id' => Auth::user()->id,
                ]);



            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['permintaan_barang_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $permintaanBarang->user_id;
            $dataPersetujuan['user_peminta_name'] = $permintaanBarang->user->name ?? '';
            $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';
            $dataPersetujuan['type'] = 'Barang Dijemput Kurir';
            $dataPersetujuan['status'] = 'done';
            $dataPersetujuan['step'] = 0;
            $dataPersetujuan['keterangan'] = 'Barang Dijemput Kurir';
            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            ApprovalProcess::create($dataPersetujuan);

            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['permintaan_barang_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $permintaanBarang->user_id;
            $dataPersetujuan['user_peminta_name'] = $permintaanBarang->user->name ?? '';
            $dataPersetujuan['role_to_name'] = 'Kurir/Offsetter' ?? '';
            $dataPersetujuan['type'] = 'Menunggu Barang diterima Kurir';
            $dataPersetujuan['status'] = '';
            $dataPersetujuan['step'] = 0;
            $dataPersetujuan['keterangan'] = $request->keterangan;
            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = $request->kurir_id;
            $dataPersetujuan['kategori'] = 'APPROVAL';
            ApprovalProcess::create($dataPersetujuan);



            DB::commit();
            return redirect()->route('permintaan-barang.detail', $permintaanBarang->id)->with(['success' => 'Kurir telah dipilih !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('permintaan-barang.detail', $permintaanBarang->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function terimaBarangByKurir(Request $request, $id)
    {
        $permintaanBarang = PermintaanBarang::find($id);

        try {
            DB::beginTransaction();

            ApprovalProcess::where('permintaan_barang_id', $id)
                ->where('type', 'Menunggu Barang diterima Kurir')
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',
                    'approve_by_id' => Auth::user()->id,
                ]);


            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['permintaan_barang_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $permintaanBarang->user_id;
            $dataPersetujuan['user_peminta_name'] = $permintaanBarang->user->name ?? '';
            $dataPersetujuan['role_to_name'] = ' ' ?? '';
            $dataPersetujuan['type'] = 'Barang Telah diterima';
            $dataPersetujuan['status'] = 'done';
            $dataPersetujuan['step'] = 0;
            $dataPersetujuan['keterangan'] = 'Selesai';
            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            ApprovalProcess::create($dataPersetujuan);


            foreach ($permintaanBarang->barang_diminta as $key => $value) {

                $barangPersediaan = BarangPersediaan::where('id', $value->barang_persediaan_id)
                    ->first();


                BarangKeluar::create([
                    'timestamp' => date('Y-m-d H:i:s'),
                    'barang_keluar_id' => $value->barang_persediaan_id,
                    'permintaan_id' => $id,
                    'harga_perolehan' => $barangPersediaan->harga_perolehan,
                    'jumlah' => $value->jumlah,
                    'tahun_perolehan' => $barangPersediaan->tahun_perolehan,
                    'sub_sub_kategori' => $barangPersediaan->sub_sub_kategori,
                ]);
            }


            // UPDATE STATUS PERMINTAAN BARANG
            PermintaanBarang::where('id', $id)
                ->update([
                    'bast_at' => date('Y-m-d H:i:s'),
                    'nomor_bast' => $this->generateNomorBast(),
                    'status' => 'Selesai'
                ]);
            DB::commit();
            return redirect()->route('permintaan-barang.detail', $permintaanBarang->id)->with(['success' => 'Sukses Terima barang !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('permintaan-barang.detail', $permintaanBarang->id)->with(['failed' => $th->getMessage()]);
        }
    }




    public function kurangiStock(Request $request, $id)
    {
        $permintaanBarang = PermintaanBarang::find($id);
        try {
            DB::beginTransaction();
            // UPDATE STOCK PERSEDIAAN


            foreach ($permintaanBarang->barang_diminta as $key => $value) {
                $stockBefore = BarangPersediaan::where('id',$value->barang_persediaan_id)
                    ->first()->jumlah;
                $jumlahDisetujui = $value->jumlah_disetujui;

                $sisaStock = $stockBefore - $jumlahDisetujui;
                $stockBefore = BarangPersediaan::where('id', $value->barang_persediaan_id)
                    ->update(['jumlah'=> $sisaStock]);
            }


            // Buat Update Nomor BAST !

            // UPDATE STATUS PERMINTAAN BARANG


            DB::commit();
            return redirect()->route('permintaan-barang.detail', $permintaanBarang->id)->with(['success' => 'Barang Diserahkan !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('permintaan-barang.detail', $permintaanBarang->id)->with(['failed' => $th->getMessage()]);
        }
    }


    private function generateNomorUpp4($notaDinas)
    {
        /*
        PL.001/04/SIM/KNK-2022
        PL. adalah bentuk baku, 001 adalah nomor urut auto generate (Nomor Urut direset jadi 001
        jika berbeda bulan), 04 Adalah bulan April (05-Mei, 06-Juni, dan seterusnya), SIM adalah
        bentuk baku yang mengartikan SIMLOG, KNK adalah Kode Role User, -2022 adalah tahun.
        Kode Role User
        • Jika Role = (Nakhoda), Kode = KNK
        • Jika Role = (Kepala VTS), Kode = VTS
        • Jika Role = (Kepala SROP), Kode = SROP
        • Jika Role = (Kepala Distrik Navigasi), Kode = TU
        • Jika Role = (Kabag Tata Usaha, Subbag Kepegawaian & Umum, Subbag Keuangan), Kode = TU
        • Jika Role = (Kabid Operasi, Seksi Program, Seksi Sarana Prasarana), Kode = OPS
        • Jika Role = (Kabid Logistik, Seksi Pengadaan, Seksi Inventaris), Kode = LOG
        • Jika Role = (Kepala Kelompok Pengamatan Laut), Kode = PENGLA
        • Jika Role = (Kepala Kelompok Bengkel), Kode = BKL
        • Jika Role = (Kepala Kelompok SBNP), Kode = SBNP
        */

        $urutNotaDinas = explode('/', $notaDinas);
        $urutNotaDinas = explode('.', $urutNotaDinas[0]);
        $urutNotaDinas = $urutNotaDinas[1];

        $roleName = Auth::user()->role->name;

        $year_now = date('Y');
        $obj = DB::table('permintaan_barang')
        ->select('nomor_upp3')
        ->latest('id')
            ->where('created_at', 'ilike', $year_now . '%')
            ->first();

        if ($obj) {
            $increment = explode('.', $obj->nomor_upp3);
            $removed1char = substr($increment[2], 1);
            $generateOrder_nr = 'P.' . $urutNotaDinas . '.' . str_pad($removed1char + 1, 4, "0", STR_PAD_LEFT) . '.' . $this->bagianBidangUpp4($roleName);
        } else {
            $generateOrder_nr = 'P.' . $urutNotaDinas . '.' . str_pad(1, 4, "0", STR_PAD_LEFT) . '.' . $this->bagianBidangUpp4($roleName);
        }
        return $generateOrder_nr;
    }

    private function generateNomorBast()
    {
        /*
        PL.001/04/SIM/KNK-2022
        PL. adalah bentuk baku, 001 adalah nomor urut auto generate (Nomor Urut direset jadi 001
        jika berbeda bulan), 04 Adalah bulan April (05-Mei, 06-Juni, dan seterusnya), SIM adalah
        bentuk baku yang mengartikan SIMLOG, KNK adalah Kode Role User, -2022 adalah tahun.
        Kode Role User
        • Jika Role = (Nakhoda), Kode = KNK
        • Jika Role = (Kepala VTS), Kode = VTS
        • Jika Role = (Kepala SROP), Kode = SROP
        • Jika Role = (Kepala Distrik Navigasi), Kode = TU
        • Jika Role = (Kabag Tata Usaha, Subbag Kepegawaian & Umum, Subbag Keuangan), Kode = TU
        • Jika Role = (Kabid Operasi, Seksi Program, Seksi Sarana Prasarana), Kode = OPS
        • Jika Role = (Kabid Logistik, Seksi Pengadaan, Seksi Inventaris), Kode = LOG
        • Jika Role = (Kepala Kelompok Pengamatan Laut), Kode = PENGLA
        • Jika Role = (Kepala Kelompok Bengkel), Kode = BKL
        • Jika Role = (Kepala Kelompok SBNP), Kode = SBNP
        */


        $roleName = Auth::user()->role->name;

        $year_now = date('Y');
        $obj = DB::table('permintaan_barang')
        ->select('nomor_bast')
        ->latest('id')
            ->where('created_at', 'ilike', $year_now . '%')
            ->first();

        if ($obj) {
            $increment = explode('/', $obj->nomor_bast);
            $removed1char = substr($increment[0], 1);
            $generateOrder_nr = str_pad($removed1char + 1, 4, "0", STR_PAD_LEFT) . '/'.$year_now.'/' . $this->bagianBidangUpp4($roleName);
        } else {
            $generateOrder_nr = str_pad(1, 4, "0", STR_PAD_LEFT) . '/'.$year_now.'/' . $this->bagianBidangUpp4($roleName);
        }
        return $generateOrder_nr;
    }

    private function bagianBidangUpp4($roleName)
    {
        if (
            $roleName == 'Kepala Distrik Navigasi' ||
            $roleName == 'Kabag Tata Usaha' ||
            $roleName == 'Subbag Kepegawaian dan Umum' ||
            $roleName == 'Subbag Keuangan'
        ) {
            $kode = 'TU';
        } else if (
            $roleName == 'Kabid Operasi' ||
            $roleName == 'Seksi Program' ||
            $roleName == 'Seksi Sarana Prasarana' ||
            $roleName == 'Nakhoda' ||
            $roleName == 'Kepala VTS' ||
            $roleName == 'Kepala SROP' ||
            $roleName == 'Kepala Kelompok Pengamatan Laut' ||
            $roleName == 'Kepala Kelompok Bengkel' ||
            $roleName == 'Kepala Kelompok SBNP'
        ) {
            $kode = 'OPS';
        } else if (
            $roleName == 'Kabid Logistik' ||
            $roleName == 'Seksi Pengadaan' ||
            $roleName == 'Seksi Inventaris'
        ) {
            $kode = 'LOG';
        } else {
            $kode = 'NONUSER';
        }

        return $kode;
    }

    public function lapor(Request $request,$id){
        try{
            DB::beginTransaction();
            $laporanDistribusi['permintaan_barang_id'] = $id;
            $laporanDistribusi['lokasi_distribusi'] = $request->lokasi_distribusi;
            $laporanDistribusi['tanggal_waktu'] = $request->tanggal.' '.$request->jam.':00';
            $laporanDistribusi['keterangan'] = $request->keterangan;
            $laporanDistribusi['lapor_by_id'] = Auth::user()->id;
            $ld = LaporanDistribusi::create($laporanDistribusi);

            foreach ($request->file as $key => $value) {
                    $value = $value;
                    $name = $id.'_'. $key.'_'.time() . '.' . $value->getClientOriginalExtension();
                    $destinationPath = public_path('images/laporan-distribusi/');
                    $value->move($destinationPath, $name);

                    $insertFile['laporan_distribusi_id'] = $ld->id;
                    $insertFile['timestamp'] = date('Y-m-d H:i:s');
                    $insertFile['file_name'] = $name;
                    FileLaporanDistribusi::create($insertFile);
            }
            DB::commit();
            return redirect()->route('permintaan-barang.detail', $id)->with(['success' => 'Sukses Lapor Distribusi barang !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('permintaan-barang.detail', $id)->with(['failed' => $th->getMessage()]);
        }
    }
}
