<?php

namespace App\Http\Controllers;

use App\ApprovalProcess;
use App\BarangPersediaan;
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
        } else if ((Auth::user()->role->name ?? null) == 'Kepala Gudang') {
            $data['permintaan_barang'] = PermintaanBarang::orderBy('id', 'desc')
            ->get();
        } else if ((Auth::user()->role->name ?? null) == 'Admin SIMLOG') {
            $data['permintaan_barang'] = PermintaanBarang::orderBy('id', 'desc')
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
                    return $query
                    ->distinct('step');
                }])
                ->first();
        $data['page_title'] = $permintaanBarang->nomor_nota_dinas;
        $data['data'] = $permintaanBarang;
        if (Auth::user()->role->name == 'Kepala Distrik Navigasi') {
            return view('approval-kadisnav.review', $data);
        }
        else if (Auth::user()->role->name == 'Kepala Gudang') {
            return view('approval-kepala-gudang.review', $data);
        } else {
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

            $dataPersetujuan['type'] = 'Permintaan Disetujui';
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
                    $dataApproval['role_to_name'] = 'Kepala Gudang';

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

            $dataApproval['type'] = 'Permintaan Disetujui';
            $dataApproval['status'] = 'done';

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


    public function pesananSiap(Request $request, $id)
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


            $dataPersetujuan['type'] = 'Pesanan Sudah Siap';
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


            $dataApproval['role_to_name'] = $permintaanBarang->user->role->name ?? '';


            $dataApproval['type'] = 'Pesanan Sudah Siap';
            $dataApproval['status'] = 'done';

            $dataApproval['step'] = 3;
            $dataApproval['keterangan'] = $request->keterangan;

            $dataApproval['tindak_lanjut'] = null;
            $dataApproval['approve_by_id'] = Auth::user()->id;
            $dataApproval['kategori'] = 'APPROVAL';
            ApprovalProcess::create($dataApproval);

            // UPDATE STATUS PERMINTAAN BARANG
            PermintaanBarang::where('id', $id)
                ->update(['status' => 'Pesanan Siap']);

            DB::commit();
            return redirect()->route('approval.review',$permintaanBarang->id)->with(['success' => 'Data Berhasil diajukan !']);
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


            // PERSETUJUAN PROCESS 2
            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['permintaan_barang_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $permintaanBarang->user_id;
            $dataPersetujuan['user_peminta_name'] = $permintaanBarang->user->name ?? '';



            $dataPersetujuan['role_to_name'] = $permintaanBarang->user->role->name ?? '';


            $dataPersetujuan['type'] = 'Terima Barang';
            $dataPersetujuan['status'] = 'done';

            $dataPersetujuan['step'] = 4;
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


            $dataApproval['role_to_name'] = 'Kepala Gudang';


            $dataApproval['type'] = 'Terima Barang';
            $dataApproval['status'] = 'done';

            $dataApproval['step'] = 4;
            $dataApproval['keterangan'] = $request->keterangan;

            $dataApproval['tindak_lanjut'] = null;
            $dataApproval['approve_by_id'] = Auth::user()->id;
            $dataApproval['kategori'] = 'APPROVAL';
            ApprovalProcess::create($dataApproval);

            // UPDATE STATUS PERMINTAAN BARANG
            PermintaanBarang::where('id', $id)
                ->update(['status' => 'Pesanan Siap']);

            DB::commit();
            return redirect()->route('permintaan-barang.detail', $permintaanBarang->id)->with(['success' => 'Terima barang sukses !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('permintaan-barang.detail', $permintaanBarang->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function serahkanBarang(Request $request, $id)
    {
        $permintaanBarang = PermintaanBarang::find($id);

        try {
            DB::beginTransaction();


            // PERSETUJUAN PROCESS 5
            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['permintaan_barang_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $permintaanBarang->user_id;
            $dataPersetujuan['user_peminta_name'] = $permintaanBarang->user->name ?? '';



            $dataPersetujuan['role_to_name'] = '';


            $dataPersetujuan['type'] = 'Diserahkan Kepala Gudang';
            $dataPersetujuan['status'] = 'done';

            $dataPersetujuan['step'] = 5;
            $dataPersetujuan['keterangan'] = $request->keterangan;

            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            ApprovalProcess::create($dataPersetujuan);

            // PERSETUJUAN PROCESS 6
            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['permintaan_barang_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $permintaanBarang->user_id;
            $dataPersetujuan['user_peminta_name'] = $permintaanBarang->user->name ?? '';



            $dataPersetujuan['role_to_name'] = '';


            $dataPersetujuan['type'] = 'Barang Diterima';
            $dataPersetujuan['status'] = 'done';

            $dataPersetujuan['step'] = 6;
            $dataPersetujuan['keterangan'] = $request->keterangan;

            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            ApprovalProcess::create($dataPersetujuan);


            // APPROVAL PROCESS 6
            $dataApproval['timestamp'] = date('Y-m-d H:i:s');
            $dataApproval['permintaan_barang_id'] = $id;
            $dataApproval['user_peminta_id'] = $permintaanBarang->user_id;
            $dataApproval['user_peminta_name'] = $permintaanBarang->user->name ?? '';


            $dataApproval['role_to_name'] = $permintaanBarang->user->role->name ?? '';


            $dataApproval['type'] = 'Terima Barang';
            $dataApproval['status'] = 'done';

            $dataApproval['step'] = 6;
            $dataApproval['keterangan'] = $request->keterangan;

            $dataApproval['tindak_lanjut'] = null;
            $dataApproval['approve_by_id'] = Auth::user()->id;
            $dataApproval['kategori'] = 'APPROVAL';
            ApprovalProcess::create($dataApproval);


            // UPDATE STOCK PERSEDIAAN
            foreach ($permintaanBarang->barang_diminta as $key => $value) {
                $stockBefore = BarangPersediaan::where('id',$value->barang_persediaan_id)
                    ->first()->jumlah;
                $jumlahDisetujui = $value->jumlah_disetujui;

                $sisaStock = $stockBefore - $jumlahDisetujui;
                $stockBefore = BarangPersediaan::where('id', $value->barang_persediaan_id)
                    ->update(['jumlah'=> $sisaStock]);
            }






            // UPDATE STATUS PERMINTAAN BARANG
            PermintaanBarang::where('id', $id)
                ->update(['status' => 'Barang Diterima']);

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
        • Jika Role = (Manager VTS), Kode = VTS
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
            $roleName == 'Manager VTS' ||
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
}
