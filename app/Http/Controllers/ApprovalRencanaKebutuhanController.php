<?php

namespace App\Http\Controllers;

use App\ApprovalRencanaKebutuhanProcess;
use App\RencanaKebutuhan;
use App\RencanaKebutuhanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApprovalRencanaKebutuhanController extends Controller
{
    public function review($id){
        $rencanaKebutuhan =
            RencanaKebutuhan::where('id', $id)
            ->with(["timeline" => function ($query) {
                // return $query
                // ->distinct('step');
                $query->where(function ($q) {
                    $q->where('kategori', 'APPROVAL')
                    ->where('status', '!=', 'done')
                    ->orwhere('step', 1);
                })
                    ->orwhere('kategori', 'PERSETUJUAN')
                    ->orwhere('kategori', 'DISPOSISI')

                    ->orderBy('id', 'asc');
            }])
            ->with(["approvals" => function ($query) {
                $query
                    ->orderBy('id', 'asc');
            }])
            ->first();



        $data['page_title'] = $rencanaKebutuhan->nomor_rk;
        $data['data'] = $rencanaKebutuhan;
        if (Auth::user()->role->name == 'Kepala Distrik Navigasi') {
            return view('rk-approval-kadisnav.review', $data);
        } else if (Auth::user()->role->name == 'Kabid Logistik') {
            return view('rk-approval-kabid-logistik.review', $data);
        } else if (Auth::user()->role->name == 'Kabid Operasi') {
            return view('rk-approval-kabid-operasi.review', $data);
        } else if (Auth::user()->role->name == 'Kasie Program') {
            return view('rk-approval-kasie-program.review', $data);
        } else if (Auth::user()->role->name == 'Kasie Sarpras') {
            return view('rk-approval-kasie-sarpras.review', $data);
        } else if (Auth::user()->role->name == 'Kabag Tata Usaha') {
            return view('rk-approval-kabag-tata-usaha.review', $data);
        } else if (Auth::user()->role->name == 'Kasie Kepeg & Umum') {
            return view('rk-approval-kasie-kepeg-umum.review', $data);
        } else if (Auth::user()->role->name == 'Kasie Keuangan') {
            return view('rk-approval-kasie-kepeg-umum.review', $data);
        } else if (Auth::user()->role->name == 'Pengelola Gudang') {
            return view('rk-approval-kepala-gudang.review', $data);
        } else if (Auth::user()->role->name == 'Kasie Pengadaan') {
            return view('rk-approval-kasie-pengadaan.review', $data);
        } else if (Auth::user()->role->name == 'Kasie Inventaris') {
            return view('rk-approval-kasie-inventaris.review', $data);
        } else if (Auth::user()->role->name == 'Bendahara Materil') {
            return view('rk-approval-bendahara-materil.review', $data);
        } else if (Auth::user()->role->name == 'Staff Seksi Pengadaan') {
            return view('rk-approval-staff-seksi-pengadaan.review', $data);
        } else if (Auth::user()->role->name == 'Kurir/Offsetter') {
            return view('rk-approval-kurir.review', $data);
        } else {
            return view('rencana-kebutuhan.detail', $data);
        }
    }

    public function tindakLanjut(Request $request, $id)
    {
        $rencanaKebutuhan = RencanaKebutuhan::find($id);

        try {
            DB::beginTransaction();
            // update approval sebelumnya
            ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $id)
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',
                    'tindak_lanjut' => '',
                    'keterangan' => $request->keterangan
                ]);



            if ($request->tindak_lanjut == 'SETUJUI' || $request->tindak_lanjut == "UPDATE") {
                // PERSETUJUAN PROCESS
                $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
                $dataPersetujuan['rencana_kebutuhan_id'] = $id;
                $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
                $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';



                $dataPersetujuan['role_to_name'] = Auth::user()->role->name;

                $dataPersetujuan['type'] = 'Disetujui Kadisnav';
                $dataPersetujuan['status'] = 'done';

                $dataPersetujuan['step'] = 2;
                $dataPersetujuan['keterangan'] = $request->keterangan;

                $dataPersetujuan['tindak_lanjut'] = $request->tindak_lanjut;
                $dataPersetujuan['approve_by_id'] = Auth::user()->id;
                $dataPersetujuan['kategori'] = 'PERSETUJUAN';
                $dataPersetujuan['from_kadisnav'] = 'SETUJUI';

                ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);


                // APPROVAL PROCESS
                $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                $dataApproval['rencana_kebutuhan_id'] = $id;
                $dataApproval['user_peminta_id'] = $rencanaKebutuhan->created_by;
                $dataApproval['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
                $dataApproval['role_to_name'] = 'Kabid Logistik';

                // Udate Barang Diminta Sesuai Request
                foreach ($request->jumlah_disetujui as $key => $value) {
                    RencanaKebutuhanDetail::where('id', $key)
                        ->update(['jumlah_disetujui' => $value]);
                }

                $dataApproval['type'] = 'Menunggu Persetujuan Kabid Logistik';
                $dataApproval['status'] = '';

                $dataApproval['step'] = 2;
                $dataApproval['keterangan'] = $request->keterangan;

                $dataApproval['tindak_lanjut'] = $request->tindak_lanjut;
                $dataApproval['approve_by_id'] = Auth::user()->id;
                $dataApproval['kategori'] = 'APPROVAL';
                $dataApproval['from_kadisnav'] = 'SETUJUI';
                ApprovalRencanaKebutuhanProcess::create($dataApproval);
                DB::commit();
                return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['success' => 'Data Berhasil disetujui !']);
            } else if ($request->tindak_lanjut == 'TOLAK') {
                // APPROVAL PROCESS
                $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                $dataApproval['rencana_kebutuhan_id'] = $id;
                $dataApproval['user_peminta_id'] = $rencanaKebutuhan->created_by;
                $dataApproval['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
                $dataApproval['role_to_name'] = $rencanaKebutuhan->user->role->name ?? '';


                $dataApproval['type'] = 'Permintaan Ditolak Kadisnav';
                $dataApproval['status'] = '';

                $dataApproval['step'] = 0;
                $dataApproval['keterangan'] = $request->keterangan;

                $dataApproval['tindak_lanjut'] = $request->tindak_lanjut;
                $dataApproval['approve_by_id'] = Auth::user()->id;
                $dataApproval['kategori'] = 'APPROVAL';
                $dataPersetujuan['from_kadisnav'] = 'TOLAK';

                ApprovalRencanaKebutuhanProcess::create($dataApproval);

                RencanaKebutuhan::where('id', $id)
                    ->update(['status' => 'Ditolak']);
                DB::commit();
                return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['success' => 'Data Berhasil ditolak !']);
            } else if ($request->tindak_lanjut == 'DISPOSISI') {

                // update approval sebelumnya
                ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $id)
                    ->where(function ($q) {
                        $q->where('kategori', 'APPROVAL')
                            ->orWhere('kategori', 'DISPOSISI');
                    })
                    ->orderBy('id', 'desc')
                    ->first()
                    ->update([
                        'status' => 'done',
                        'tindak_lanjut' => null,

                        'keterangan' => $request->keterangan
                    ]);


                $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
                $dataPersetujuan['rencana_kebutuhan_id'] = $id;
                $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
                $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
                $dataPersetujuan['role_to_name'] = $request->disposisi_ke ?? '';
                $dataPersetujuan['type'] = Auth::user()->role->name . ' Meminta Disposisi';
                $dataPersetujuan['status'] = '';
                $dataPersetujuan['step'] = 0;
                $dataPersetujuan['keterangan'] = $request->keterangan;
                $dataPersetujuan['tindak_lanjut'] = 'DISPOSISI';
                $dataPersetujuan['approve_by_id'] = Auth::user()->id;
                $dataPersetujuan['kategori'] = 'DISPOSISI';
                $dataPersetujuan['from_kadisnav'] = 'DISPOSISI';
                $approval = ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);
                DB::commit();
                return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['success' => 'Data Berhasil di Disposisi !']);
            } else {
                dd('NOT YET AVAILABLE !');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function kabidLogistikSetuju(Request $request, $id)
    {
        $rencanaKebutuhan = RencanaKebutuhan::find($id);

        try {
            DB::beginTransaction();

            $persetujuanSebelumnya = ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $id)
                ->where('role_to_name', 'Kasie Pengadaan')
                ->where('status', 'done')
                ->where('kategori', 'PERSETUJUAN')
                ->where(function ($q) {
                    $q->where('from_kadisnav', 'not like', 'DISPOSISI')
                        ->orwhereNull('from_kadisnav');
                })
                ->orderBy('id', 'desc')
                ->first();




            // update approval sebelumnya
            ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $id)
                ->where(function ($q) {
                    $q->where('kategori', 'APPROVAL')
                        ->orWhere('kategori', 'DISPOSISI');
                })
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',
                    'tindak_lanjut' => null,
                    'keterangan' => $request->keterangan
                ]);


            if ($persetujuanSebelumnya) {
                // JIKA TIDAK ADA DISPOSISI MAKA SETUJUI AKAN KEUSER YANG MELAKUKAN DISPOSISI
                $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
                $dataPersetujuan['rencana_kebutuhan_id'] = $id;
                $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
                $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
                $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';
                $dataPersetujuan['type'] = 'Disetujui Kabid Logistik';
                $dataPersetujuan['status'] = 'done';
                $dataPersetujuan['step'] = 0;
                $dataPersetujuan['keterangan'] = $request->keterangan;
                $dataPersetujuan['tindak_lanjut'] = null;
                $dataPersetujuan['approve_by_id'] = Auth::user()->id;
                $dataPersetujuan['kategori'] = 'PERSETUJUAN';
                ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);

                $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                $dataApproval['rencana_kebutuhan_id'] = $id;
                $dataApproval['user_peminta_id'] = $rencanaKebutuhan->created_by;
                $dataApproval['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
                $dataApproval['role_to_name'] = 'Bendahara Materil' ?? '';
                $dataApproval['type'] = 'Menunggu Persetujuan Bendahara Materil';
                $dataApproval['status'] = '';
                $dataApproval['step'] = 0;
                $dataApproval['keterangan'] = $request->keterangan;
                $dataApproval['tindak_lanjut'] = null;
                $dataApproval['approve_by_id'] = 0;
                $dataApproval['kategori'] = 'APPROVAL';
                ApprovalRencanaKebutuhanProcess::create($dataApproval);
            } else {
                // JIKA TIDAK ADA DISPOSISI MAKA SETUJUI AKAN KASIE PENGADAAN
                $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
                $dataPersetujuan['rencana_kebutuhan_id'] = $id;
                $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
                $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
                $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';
                $dataPersetujuan['type'] = 'Disetujui Kabid Logistik';
                $dataPersetujuan['status'] = 'done';
                $dataPersetujuan['step'] = 0;
                $dataPersetujuan['keterangan'] = $request->keterangan;
                $dataPersetujuan['tindak_lanjut'] = null;
                $dataPersetujuan['approve_by_id'] = Auth::user()->id;
                $dataPersetujuan['kategori'] = 'PERSETUJUAN';
                ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);


                $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                $dataApproval['rencana_kebutuhan_id'] = $id;
                $dataApproval['user_peminta_id'] = $rencanaKebutuhan->created_by;
                $dataApproval['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
                $dataApproval['role_to_name'] = 'Kasie Pengadaan' ?? '';
                $dataApproval['type'] = 'Menunggu Persetujuan Kasie Pengadaan';
                $dataApproval['status'] = '';
                $dataApproval['step'] = 0;
                $dataApproval['keterangan'] = $request->keterangan;
                $dataApproval['tindak_lanjut'] = null;
                $dataApproval['approve_by_id'] = 0;
                $dataApproval['kategori'] = 'APPROVAL';
                ApprovalRencanaKebutuhanProcess::create($dataApproval);
            }



            // UPDATE STATUS PERMINTAAN BARANG


            DB::commit();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['success' => 'Data Berhasil Disetujui !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['failed' => $th->getMessage()]);
        }
    }
    public function kabidLogistikSetujuDisposisiKadisnav(Request $request, $id)
    {
        $rencanaKebutuhan = RencanaKebutuhan::find($id);

        try {
            DB::beginTransaction();



            // update approval sebelumnya
            ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $id)
                ->where(function ($q) {
                    $q->where('kategori', 'APPROVAL')
                        ->orWhere('kategori', 'DISPOSISI');
                })
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',
                    'tindak_lanjut' => 'DISPOSISI',

                    'keterangan' => $request->keterangan
                ]);



            // JIKA TIDAK ADA DISPOSISI MAKA SETUJUI AKAN KASIE PENGADAAN
            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['rencana_kebutuhan_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
            $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
            $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';
            $dataPersetujuan['type'] = 'Disetujui ' . Auth::user()->role->name;
            $dataPersetujuan['status'] = 'done';
            $dataPersetujuan['step'] = 0;
            $dataPersetujuan['keterangan'] = $request->keterangan;
            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            $dataPersetujuan['from_kadisnav'] = $rencanaKebutuhan->fromKadisnav();
            ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);


            $dataApproval['timestamp'] = date('Y-m-d H:i:s');
            $dataApproval['rencana_kebutuhan_id'] = $id;
            $dataApproval['user_peminta_id'] = $rencanaKebutuhan->created_by;
            $dataApproval['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
            $dataApproval['role_to_name'] = 'Kepala Distrik Navigasi' ?? '';
            $dataApproval['type'] = 'Menunggu Persetujuan Kepala Distrik Navigasi';
            $dataApproval['status'] = '';
            $dataApproval['step'] = 0;
            $dataApproval['keterangan'] = $request->keterangan;
            $dataApproval['tindak_lanjut'] = 'DISPOSISI';
            $dataApproval['approve_by_id'] = 0;
            $dataApproval['kategori'] = 'APPROVAL';
            $dataApproval['from_kadisnav'] = $rencanaKebutuhan->fromKadisnav();

            ApprovalRencanaKebutuhanProcess::create($dataApproval);






            DB::commit();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['success' => 'Data Berhasil Disetujui !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function kabidLogistikDisposisi(Request $request, $id)
    {
        $rencanaKebutuhan = RencanaKebutuhan::find($id);

        try {
            DB::beginTransaction();

            // update approval sebelumnya
            ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $id)
                ->where(function ($q) {
                    $q->where('kategori', 'APPROVAL')
                        ->orWhere('kategori', 'DISPOSISI');
                })
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',
                    'tindak_lanjut' => null,

                    'keterangan' => $request->keterangan
                ]);


            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['rencana_kebutuhan_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
            $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
            $dataPersetujuan['role_to_name'] = $request->disposisi_ke ?? '';
            $dataPersetujuan['type'] = Auth::user()->role->name . ' Meminta Disposisi';
            $dataPersetujuan['status'] = '';
            $dataPersetujuan['step'] = 0;
            $dataPersetujuan['keterangan'] = $request->keterangan;
            $dataPersetujuan['tindak_lanjut'] = 'DISPOSISI';
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'DISPOSISI';
            $dataPersetujuan['from_kadisnav'] = $rencanaKebutuhan->fromKadisnav();
            ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);




            DB::commit();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['success' => 'Data Berhasil di Desposisi ke ' . $request->disposisi_ke . ' !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function kasiePengadaanSetuju(Request $request, $id)
    {
        $rencanaKebutuhan = RencanaKebutuhan::find($id);
        // Check apakah sebelumnuya ada DISPOSISI

        try {

            DB::beginTransaction();





            $disposisiSebelumnya = ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $id)
                ->where('kategori', 'DISPOSISI')
                ->where('role_to_name', 'Kasie Pengadaan')
                ->where('status', '!=', 'done')
                ->orderBy('id', 'desc')
                ->first();

            // update approval sebelumnya
            ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $id)
                ->where(function ($q) {
                    $q->where('kategori', 'APPROVAL')
                        ->orWhere('kategori', 'DISPOSISI');
                })
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',
                    'tindak_lanjut' => null,

                    'keterangan' => $request->keterangan
                ]);

            if ($disposisiSebelumnya) {
                $roleDesposisi = $disposisiSebelumnya->user->role->name ?? '';
                // JIKA TIDAK ADA DISPOSISI MAKA SETUJUI AKAN KEUSER YANG MELAKUKAN DISPOSISI
                $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
                $dataPersetujuan['rencana_kebutuhan_id'] = $id;
                $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
                $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
                $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';
                $dataPersetujuan['type'] = 'Disetujui Kasie Pengadaan';
                $dataPersetujuan['status'] = 'done';
                $dataPersetujuan['step'] = 0;
                $dataPersetujuan['keterangan'] = $request->keterangan;
                $dataPersetujuan['tindak_lanjut'] = null;
                $dataPersetujuan['approve_by_id'] = Auth::user()->id;
                $dataPersetujuan['kategori'] = 'PERSETUJUAN';
                ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);

                $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                $dataApproval['rencana_kebutuhan_id'] = $id;
                $dataApproval['user_peminta_id'] = $rencanaKebutuhan->created_by;
                $dataApproval['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
                $dataApproval['role_to_name'] = $roleDesposisi ?? '';
                $dataApproval['type'] = 'Menunggu Persetujuan ' . $roleDesposisi;
                $dataApproval['status'] = '';
                $dataApproval['step'] = 0;
                $dataApproval['keterangan'] = $request->keterangan;
                $dataApproval['tindak_lanjut'] = null;
                $dataApproval['approve_by_id'] = 0;
                $dataApproval['kategori'] = 'APPROVAL';
                ApprovalRencanaKebutuhanProcess::create($dataApproval);
            } else {
                // CEK APAKAH SEBELUMNYA ADA PERSETUJUAN YANG BELUM DI SETUJUI KABIDLOG
                $kabidlogSetujui = ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $id)
                    ->where('role_to_name', 'Kabid Logistik')
                    ->where('type', 'Disetujui Kabid Logistik')
                    ->where('status', 'done')
                    ->orderBy('id', 'desc')
                    ->first();


                if ($kabidlogSetujui) {
                    // Jika sudah di setujui kabidlog baru lanjut ke benmat
                    // JIKA TIDAK ADA DISPOSISI MAKA SETUJUI AKAN KEBENDAHARA MATERIL
                    $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
                    $dataPersetujuan['rencana_kebutuhan_id'] = $id;
                    $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
                    $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
                    $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';
                    $dataPersetujuan['type'] = 'Disetujui Kasie Pengadaan';
                    $dataPersetujuan['status'] = 'done';
                    $dataPersetujuan['step'] = 0;
                    $dataPersetujuan['keterangan'] = $request->keterangan;
                    $dataPersetujuan['tindak_lanjut'] = null;
                    $dataPersetujuan['approve_by_id'] = Auth::user()->id;
                    $dataPersetujuan['kategori'] = 'PERSETUJUAN';
                    ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);

                    $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                    $dataApproval['rencana_kebutuhan_id'] = $id;
                    $dataApproval['user_peminta_id'] = $rencanaKebutuhan->created_by;
                    $dataApproval['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
                    $dataApproval['role_to_name'] = $rencanaKebutuhan->user->role->name ?? '';
                    $dataApproval['type'] = 'Perintah Pembuatan RAB';
                    $dataApproval['status'] = '';
                    $dataApproval['step'] = 0;
                    $dataApproval['keterangan'] = $request->keterangan;
                    $dataApproval['tindak_lanjut'] = null;
                    $dataApproval['approve_by_id'] = $rencanaKebutuhan->user->id ?? null;
                    $dataApproval['kategori'] = 'APPROVAL';
                    ApprovalRencanaKebutuhanProcess::create($dataApproval);
                } else {
                    // Jika kabid log belum setuju maka harus  dikembalikan ke kabid log
                    $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
                    $dataPersetujuan['rencana_kebutuhan_id'] = $id;
                    $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
                    $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
                    $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';
                    $dataPersetujuan['type'] = 'Disetujui ' . Auth::user()->role->name;
                    $dataPersetujuan['status'] = 'done';
                    $dataPersetujuan['step'] = 0;
                    $dataPersetujuan['keterangan'] = $request->keterangan;
                    $dataPersetujuan['tindak_lanjut'] = null;
                    $dataPersetujuan['approve_by_id'] = Auth::user()->id;
                    $dataPersetujuan['kategori'] = 'PERSETUJUAN';
                    ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);

                    $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                    $dataApproval['rencana_kebutuhan_id'] = $id;
                    $dataApproval['user_peminta_id'] = $rencanaKebutuhan->created_by;
                    $dataApproval['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
                    $dataApproval['role_to_name'] = 'Kabid Logistik' ?? '';
                    $dataApproval['type'] = 'Menunggu Persetujuan Kabid Logistik';
                    $dataApproval['status'] = '';
                    $dataApproval['step'] = 0;
                    $dataApproval['keterangan'] = $request->keterangan;
                    $dataApproval['tindak_lanjut'] = null;
                    $dataApproval['approve_by_id'] = 0;
                    $dataApproval['kategori'] = 'APPROVAL';
                    ApprovalRencanaKebutuhanProcess::create($dataApproval);

                }
            }





            DB::commit();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['success' => 'Data Berhasil Disetujui !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function kasiePengadaanSetujuDisposisiKadisnav(Request $request, $id)
    {
        $rencanaKebutuhan = RencanaKebutuhan::find($id);

        try {
            DB::beginTransaction();



            // update approval sebelumnya
            ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $id)
                ->where(function ($q) {
                    $q->where('kategori', 'APPROVAL')
                        ->orWhere('kategori', 'DISPOSISI');
                })
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',
                    'tindak_lanjut' => 'DISPOSISI',

                    'keterangan' => $request->keterangan
                ]);



            // JIKA TIDAK ADA DISPOSISI MAKA SETUJUI AKAN KASIE PENGADAAN
            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['rencana_kebutuhan_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
            $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
            $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';
            $dataPersetujuan['type'] = 'Disetujui ' . Auth::user()->role->name;
            $dataPersetujuan['status'] = 'done';
            $dataPersetujuan['step'] = 0;
            $dataPersetujuan['keterangan'] = $request->keterangan;
            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            $dataPersetujuan['from_kadisnav'] = $rencanaKebutuhan->fromKadisnav();
            ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);


            $dataApproval['timestamp'] = date('Y-m-d H:i:s');
            $dataApproval['rencana_kebutuhan_id'] = $id;
            $dataApproval['user_peminta_id'] = $rencanaKebutuhan->created_by;
            $dataApproval['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
            $dataApproval['role_to_name'] = $request->role_to_name ?? '';
            $dataApproval['type'] = 'Menunggu Persetujuan ' . $request->role_to_name;
            $dataApproval['status'] = '';
            $dataApproval['step'] = 0;
            $dataApproval['keterangan'] = $request->keterangan;
            $dataApproval['tindak_lanjut'] = 'DISPOSISI';
            $dataApproval['approve_by_id'] = 0;
            $dataApproval['kategori'] = 'APPROVAL';
            $dataApproval['from_kadisnav'] = $rencanaKebutuhan->fromKadisnav();

            ApprovalRencanaKebutuhanProcess::create($dataApproval);






            DB::commit();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['success' => 'Data Berhasil Disetujui !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function bendaharaMaterilSetuju(Request $request, $id)
    {
        $rencanaKebutuhan = RencanaKebutuhan::find($id);

        try {
            DB::beginTransaction();

            // update approval sebelumnya
            ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $id)
                ->where(function ($q) {
                    $q->where('kategori', 'APPROVAL')
                        ->orWhere('kategori', 'DISPOSISI');
                })
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',
                    'tindak_lanjut' => null,

                    'keterangan' => $request->keterangan
                ]);


            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['rencana_kebutuhan_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
            $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
            $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';
            $dataPersetujuan['type'] = 'Disetujui Bendahara Materil';
            $dataPersetujuan['status'] = 'done';
            $dataPersetujuan['step'] = 0;
            $dataPersetujuan['keterangan'] = $request->keterangan;
            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);


            $dataApproval['timestamp'] = date('Y-m-d H:i:s');
            $dataApproval['rencana_kebutuhan_id'] = $id;
            $dataApproval['user_peminta_id'] = $rencanaKebutuhan->created_by;
            $dataApproval['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
            $dataApproval['role_to_name'] = 'Pengelola Gudang' ?? '';
            $dataApproval['type'] = 'Menunggu Persetujuan Pengelola Gudang';
            $dataApproval['status'] = '';
            $dataApproval['step'] = 0;
            $dataApproval['keterangan'] = $request->keterangan;
            $dataApproval['tindak_lanjut'] = null;
            $dataApproval['approve_by_id'] = 0;
            $dataApproval['kategori'] = 'APPROVAL';
            ApprovalRencanaKebutuhanProcess::create($dataApproval);


            DB::commit();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['success' => 'Data Berhasil Disetujui !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function staffSeksiPengadaanSetuju(Request $request, $id)
    {
        $rencanaKebutuhan = RencanaKebutuhan::find($id);

        $persetujuanSebelumnya = ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $id)
            ->where('role_to_name', 'Kasie Pengadaan')
            ->where('status', 'done')
            ->where('kategori', 'PERSETUJUAN')
            ->where(function ($q) {
                $q->where('from_kadisnav', 'not like', 'DISPOSISI')
                    ->orwhereNull('from_kadisnav');
            })
            ->orderBy('id', 'desc')
            ->first();

        $disposisiSebelumnya = ApprovalRencanaKebutuhanProcess::where('kategori', 'DISPOSISI')
            ->where('role_to_name', Auth::user()->role->name)
            ->where('status', '!=', 'done')
            ->orderBy('id', 'desc')
            ->first();

        try {
            DB::beginTransaction();

            // update approval sebelumnya
            ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $id)
                ->where(function ($q) {
                    $q->where('kategori', 'APPROVAL')
                        ->orWhere('kategori', 'DISPOSISI');
                })
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',
                    'tindak_lanjut' => null,

                    'keterangan' => $request->keterangan
                ]);


            if ($persetujuanSebelumnya) {
                // JIKA TIDAK ADA DISPOSISI MAKA SETUJUI AKAN KEUSER YANG MELAKUKAN DISPOSISI
                $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
                $dataPersetujuan['rencana_kebutuhan_id'] = $id;
                $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
                $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
                $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';
                $dataPersetujuan['type'] = 'Disetujui ' . Auth::user()->role->name;
                $dataPersetujuan['status'] = 'done';
                $dataPersetujuan['step'] = 0;
                $dataPersetujuan['keterangan'] = $request->keterangan;
                $dataPersetujuan['tindak_lanjut'] = null;
                $dataPersetujuan['approve_by_id'] = Auth::user()->id;
                $dataPersetujuan['kategori'] = 'PERSETUJUAN';
                ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);

                $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                $dataApproval['rencana_kebutuhan_id'] = $id;
                $dataApproval['user_peminta_id'] = $rencanaKebutuhan->created_by;
                $dataApproval['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
                $dataApproval['role_to_name'] = 'Bendahara Materil' ?? '';
                $dataApproval['type'] = 'Menunggu Persetujuan Bendahara Materil';
                $dataApproval['status'] = '';
                $dataApproval['step'] = 0;
                $dataApproval['keterangan'] = $request->keterangan;
                $dataApproval['tindak_lanjut'] = null;
                $dataApproval['approve_by_id'] = 0;
                $dataApproval['kategori'] = 'APPROVAL';
                ApprovalRencanaKebutuhanProcess::create($dataApproval);
            } else {
                $roleDesposisi = $disposisiSebelumnya->user->role->name ?? '';
                // JIKA TIDAK ADA DISPOSISI MAKA SETUJUI AKAN KEUSER YANG MELAKUKAN DISPOSISI
                $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
                $dataPersetujuan['rencana_kebutuhan_id'] = $id;
                $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
                $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
                $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';
                $dataPersetujuan['type'] = 'Disetujui ' . Auth::user()->role->name;
                $dataPersetujuan['status'] = 'done';
                $dataPersetujuan['step'] = 0;
                $dataPersetujuan['keterangan'] = $request->keterangan;
                $dataPersetujuan['tindak_lanjut'] = null;
                $dataPersetujuan['approve_by_id'] = Auth::user()->id;
                $dataPersetujuan['kategori'] = 'PERSETUJUAN';
                ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);

                $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                $dataApproval['rencana_kebutuhan_id'] = $id;
                $dataApproval['user_peminta_id'] = $rencanaKebutuhan->created_by;
                $dataApproval['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
                $dataApproval['role_to_name'] = $roleDesposisi ?? '';
                $dataApproval['type'] = 'Menunggu Persetujuan ' . $roleDesposisi;
                $dataApproval['status'] = '';
                $dataApproval['step'] = 0;
                $dataApproval['keterangan'] = $request->keterangan;
                $dataApproval['tindak_lanjut'] = null;
                $dataApproval['approve_by_id'] = 0;
                $dataApproval['kategori'] = 'APPROVAL';
                ApprovalRencanaKebutuhanProcess::create($dataApproval);
            }

            DB::commit();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['success' => 'Data Berhasil Disetujui !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function pengelolaGudangSiap(Request $request, $id)
    {
        $rencanaKebutuhan = RencanaKebutuhan::find($id);

        try {
            DB::beginTransaction();

            ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $id)
                ->where('type', 'Menunggu Persetujuan Pengelola Gudang')
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',

                ]);
            RencanaKebutuhan::where('id', $id)
                ->update(['status' => 'Disetujui']);


            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['rencana_kebutuhan_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
            $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
            $dataPersetujuan['role_to_name'] ='';
            $dataPersetujuan['type'] = 'Rencana Kebutuhan Telah di Setujui';
            $dataPersetujuan['status'] = 'done';
            $dataPersetujuan['step'] = 0;
            $dataPersetujuan['keterangan'] = $request->keterangan;
            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);




            DB::commit();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['success' => 'Rencana Kebutuhan disetujui !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function serahkanBarang(Request $request, $id)
    {
        $rencanaKebutuhan = RencanaKebutuhan::find($id);

        try {
            DB::beginTransaction();

            ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $id)
                ->where('type', 'Menunggu Barang Diserahkan')
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',

                ]);


            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['rencana_kebutuhan_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
            $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
            $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';
            $dataPersetujuan['type'] = 'Barang Telah diserahkan Pengelola Gudang';
            $dataPersetujuan['status'] = 'done';
            $dataPersetujuan['step'] = 0;
            $dataPersetujuan['keterangan'] = 'Barang sudah diserahkan mohon konfirmasi barang diterima.';
            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);

            $dataApproval['timestamp'] = date('Y-m-d H:i:s');
            $dataApproval['rencana_kebutuhan_id'] = $id;
            $dataApproval['user_peminta_id'] = $rencanaKebutuhan->created_by;
            $dataApproval['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
            $dataApproval['role_to_name'] = $rencanaKebutuhan->user->role->name ?? '';
            $dataApproval['type'] = 'Menunggu Barang Diterima';
            $dataApproval['status'] = '';
            $dataApproval['step'] = 0;
            $dataApproval['keterangan'] = $request->keterangan;
            $dataApproval['tindak_lanjut'] = null;
            $dataApproval['approve_by_id'] = 0;
            $dataApproval['kategori'] = 'APPROVAL';
            ApprovalRencanaKebutuhanProcess::create($dataApproval);


            DB::commit();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['success' => 'Barang berhasil diserahkan !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function terimaBarang(Request $request, $id)
    {
        $rencanaKebutuhan = RencanaKebutuhan::find($id);

        try {
            DB::beginTransaction();

            ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $id)
                ->where('type', 'Menunggu Barang Diterima')
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',

                ]);


            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['rencana_kebutuhan_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
            $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
            $dataPersetujuan['role_to_name'] = '' ?? '';
            $dataPersetujuan['type'] = 'Barang Telah diterima';
            $dataPersetujuan['status'] = 'done';
            $dataPersetujuan['step'] = 0;
            $dataPersetujuan['keterangan'] = 'Selesai';
            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);


            foreach ($rencanaKebutuhan->barang_diminta as $key => $value) {

                $barangPersediaan = BarangPersediaan::where('id', $value->barang_persediaan_id)
                    ->first();


                BarangKeluar::create([
                    'timestamp' => date('Y-m-d H:i:s'),
                    'barang_keluar_id' => $value->barang_persediaan_id,
                    'permintaan_id' => $id,
                    'harga_perolehan' => $barangPersediaan->harga_perolehan,
                    'jumlah' => $value->jumlah_disetujui,
                    'tahun_perolehan' => $barangPersediaan->tahun_perolehan,
                    'sub_sub_kategori' => $barangPersediaan->sub_sub_kategori,
                ]);
            }



            // UPDATE STATUS PERMINTAAN BARANG
            RencanaKebutuhan::where('id', $id)
                ->update([
                    'bast_at' => date('Y-m-d H:i:s'),
                    'nomor_bast' => $this->generateNomorBast(),
                    'status' => 'Selesai'
                ]);
            DB::commit();
            return redirect()->route('permintaan-barang.detail', $rencanaKebutuhan->id)->with(['success' => 'Sukses Terima barang !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('permintaan-barang.detail', $rencanaKebutuhan->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function pengelolaGudangsetuju(Request $request, $id)
    {
        $rencanaKebutuhan = RencanaKebutuhan::find($id);

        try {
            DB::beginTransaction();


            // PERSETUJUAN PROCESS 2
            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['rencana_kebutuhan_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
            $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';



            $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';


            $dataPersetujuan['type'] = 'Disetujui Pengelola Gudang';
            $dataPersetujuan['status'] = 'done';

            $dataPersetujuan['step'] = 3;
            $dataPersetujuan['keterangan'] = $request->keterangan;

            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);


            // APPROVAL PROCESS 2
            $dataApproval['timestamp'] = date('Y-m-d H:i:s');
            $dataApproval['rencana_kebutuhan_id'] = $id;
            $dataApproval['user_peminta_id'] = $rencanaKebutuhan->created_by;
            $dataApproval['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';


            $dataApproval['role_to_name'] = 'Kasie Pengadaan' ?? '';


            $dataApproval['type'] = 'Disetujui Pengelola Gudang';
            $dataApproval['status'] = 'done';

            $dataApproval['step'] = 3;
            $dataApproval['keterangan'] = $request->keterangan;

            $dataApproval['tindak_lanjut'] = null;
            $dataApproval['approve_by_id'] = Auth::user()->id;
            $dataApproval['kategori'] = 'APPROVAL';
            ApprovalRencanaKebutuhanProcess::create($dataApproval);

            // UPDATE STATUS PERMINTAAN BARANG


            DB::commit();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['success' => 'Data Berhasil Disetujui !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('rk-approval.review', $rencanaKebutuhan->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function kirimKurir(Request $request, $id)
    {
        $rencanaKebutuhan = RencanaKebutuhan::find($id);

        try {
            DB::beginTransaction();

            ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $id)
                ->where('type', 'Menunggu Barang Diterima')
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',

                ]);



            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['rencana_kebutuhan_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
            $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
            $dataPersetujuan['role_to_name'] = Auth::user()->role->name ?? '';
            $dataPersetujuan['type'] = 'Barang Dijemput Kurir';
            $dataPersetujuan['status'] = 'done';
            $dataPersetujuan['step'] = 0;
            $dataPersetujuan['keterangan'] = 'Barang Dijemput Kurir';
            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);

            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['rencana_kebutuhan_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
            $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
            $dataPersetujuan['role_to_name'] = 'Kurir/Offsetter' ?? '';
            $dataPersetujuan['type'] = 'Menunggu Barang diterima Kurir';
            $dataPersetujuan['status'] = '';
            $dataPersetujuan['step'] = 0;
            $dataPersetujuan['keterangan'] = $request->keterangan;
            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = $request->kurir_id;
            $dataPersetujuan['kategori'] = 'APPROVAL';
            ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);



            DB::commit();
            return redirect()->route('permintaan-barang.detail', $rencanaKebutuhan->id)->with(['success' => 'Kurir telah dipilih !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('permintaan-barang.detail', $rencanaKebutuhan->id)->with(['failed' => $th->getMessage()]);
        }
    }

    public function terimaBarangByKurir(Request $request, $id)
    {
        $rencanaKebutuhan = RencanaKebutuhan::find($id);

        try {
            DB::beginTransaction();

            ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $id)
                ->where('type', 'Menunggu Barang diterima Kurir')
                ->orderBy('id', 'desc')
                ->first()
                ->update([
                    'status' => 'done',

                ]);


            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['rencana_kebutuhan_id'] = $id;
            $dataPersetujuan['user_peminta_id'] = $rencanaKebutuhan->created_by;
            $dataPersetujuan['user_peminta_name'] = $rencanaKebutuhan->user->name ?? '';
            $dataPersetujuan['role_to_name'] = ' ' ?? '';
            $dataPersetujuan['type'] = 'Barang Telah diterima';
            $dataPersetujuan['status'] = 'done';
            $dataPersetujuan['step'] = 0;
            $dataPersetujuan['keterangan'] = 'Selesai';
            $dataPersetujuan['tindak_lanjut'] = null;
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = 'PERSETUJUAN';
            ApprovalRencanaKebutuhanProcess::create($dataPersetujuan);


            foreach ($rencanaKebutuhan->barang_diminta as $key => $value) {

                $barangPersediaan = BarangPersediaan::where('id', $value->barang_persediaan_id)
                    ->first();


                BarangKeluar::create([
                    'timestamp' => date('Y-m-d H:i:s'),
                    'barang_keluar_id' => $value->barang_persediaan_id,
                    'permintaan_id' => $id,
                    'harga_perolehan' => $barangPersediaan->harga_perolehan,
                    'jumlah' => $value->jumlah_disetujui,
                    'tahun_perolehan' => $barangPersediaan->tahun_perolehan,
                    'sub_sub_kategori' => $barangPersediaan->sub_sub_kategori,
                ]);
            }


            // UPDATE STATUS PERMINTAAN BARANG
            RencanaKebutuhan::where('id', $id)
                ->update([
                    'bast_at' => date('Y-m-d H:i:s'),
                    'nomor_bast' => $this->generateNomorBast(),
                    'status' => 'Selesai'
                ]);
            DB::commit();
            return redirect()->route('permintaan-barang.detail', $rencanaKebutuhan->id)->with(['success' => 'Sukses Terima barang !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('permintaan-barang.detail', $rencanaKebutuhan->id)->with(['failed' => $th->getMessage()]);
        }
    }

}
