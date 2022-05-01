<?php

namespace App\Http\Controllers;

use App\ApprovalRetur;
use App\PermintaanBarangDetail;
use App\ReturBarang;
use App\ReturDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;


class ReturBarangController extends Controller
{
    public function index()
    {
        $data['page_title'] = 'Retur Barang';

        $data['retur_barang'] = ReturBarang::orderBy('id', 'desc')->get();
        return view('retur-barang.index', $data);
    }

    public function approvalReview($id)
    {
        $data['page_title'] = 'Retur Barang ';
        $data['data'] = ReturBarang::find($id);

        $notaDinas = [];
        $nomorUpp4 = [];
        foreach ($data['data']->retur_detail as $key => $value) {
            $notaDinas[] = $value->permintaanBarang->nomor_nota_dinas;
            $nomorUpp4[] = $value->permintaanBarang->nomor_upp4;
        }
        $data['nota_dinas'] = $notaDinas;
        $data['upp4'] = $nomorUpp4;

        return view('retur-approval.review', $data);
    }

    public function create()
    {
        $data['page_title'] = 'Retur Barang';

        $userId = Auth::user()->id;

        // check apakah user ini sudah buat draft
        $isDraft = ReturBarang::where('user_id',$userId)
        ->where('is_draft',true)
        ->first();

        if(!$isDraft){
            // Buat Draft Dulu
            $dataDraftReturBarang['user_id'] = $userId;
            $dataDraftReturBarang['status'] = 'draft';
            $dataDraftReturBarang['is_draft'] = true;
            ReturBarang::create($dataDraftReturBarang);
        }
        $dataReturBarang = ReturBarang::where('user_id',$userId)
        ->where('is_draft',true)
        ->first();
        $data['data'] = $dataReturBarang;

        if (Auth::user()->role->name == 'Kurir/Offsetter') {
            # code...
            $data['list_barang_belum_distribusi'] = PermintaanBarangDetail::where(function ($q) {
                $q->where('status', '!=', 'done')
                ->orwhereRaw(DB::raw('status is null'));
            })
            ->whereHas('permintaanBarang', function ($p) use ($userId) {
                $p->whereHas('approvals',function($k) use($userId){
                    $k->where('approve_by_id',$userId);
                })
                ->whereStatus('Selesai');
            })
            ->get();

        }else{
            $data['list_barang_belum_distribusi'] = PermintaanBarangDetail::where(function ($q) {
                $q->where('status', '!=', 'done')
                    ->orwhereRaw(DB::raw('status is null'));
            })
                ->whereHas('permintaanBarang', function ($p) use ($userId) {
                    $p->where('user_id', $userId)
                        ->whereStatus('Selesai');
                })
                ->get();
        }


        return view('retur-barang.create', $data);
    }

    public function tambahBarang(Request $request){

        try {
            $data['timestamp'] = date('Y-m-d H:i:s');
            $data['retur_id'] = $request->retur_id;
            $data['permintaan_barang_id'] = $request->permintaan_barang_id;
            $data['permintaan_barang_detail_id'] = $request->permintaan_barang_detail_id;
            $data['barang_id'] = $request->barang_id;
            $data['user_id'] = Auth::user()->id;
            $data['nama_barang'] = $request->nama_barang;
            $data['kode_barang'] = $request->kode_barang;
            $data['nomor_nota_dinas'] = $request->nomor_nota_dinas;
            $data['nomor_upp3'] = $request->nomor_upp3;
            $data['nomor_upp4'] = $request->nomor_upp4;
            $data['jumlah_retur'] = $request->jumlah_retur;
            $data['status'] = '';

            ReturDetail::updateOrCreate(
            [
                'retur_id'=> $request->retur_id,
                'permintaan_barang_id'=> $request->permintaan_barang_id,
                'permintaan_barang_detail_id'=> $request->permintaan_barang_detail_id,
                'barang_id'=> $request->barang_id,
                'user_id'=> Auth::user()->id
            ]
            ,$data);
            return redirect()->route('retur-barang.create')->with(['success' => 'Barang Berhasil ditambahkan !']);
        } catch (\Throwable $th) {
            return redirect()->route('retur-barang.create')->with(['failed' => $th->getMessage()]);
        }




    }

    public function store(Request $request,$id){
        $roleName = Auth::user()->role->name;
        $kode = $this->getKode($roleName);
        $returBarang = ReturBarang::where('id', $id)->first();
        try {
            DB::beginTransaction();
            ReturBarang::where('id',$id)->update([
                'timestamp' => date('Y-m-d H:i:s'),
                'nomor_retur' => $this->generateNomorRetur(),
                'instalasi' => $kode,
                'perihal' => $request->perihal,
                'alasan_retur' => $request->alasan_retur,
                'keterangan' => $request->keterangan,
                'status' => 'Dalam Proses',
                'is_draft' => false,
            ]);

            ReturDetail::where('retur_id',$id)->update([
                'status'=>'pending'
            ]);

            // APPROVAL DISETUJUI KASIE PENGADAAN


            // Buat Approval Persetujuan Retur
            // $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            // $dataPersetujuan['retur_id'] = $id;
            // $dataPersetujuan['user_id'] = Auth::user()->id;
            // $dataPersetujuan['user_name'] = Auth::user()->name;
            // $dataPersetujuan['role_to_name'] = '';
            // $dataPersetujuan['type'] = 'Retur Diajukan';
            // $dataPersetujuan['status'] = 'done';
            // $dataPersetujuan['keterangan'] = $request->keterangan ?? '';
            // $dataPersetujuan['tindak_lanjut'] = '';
            // $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            // $dataPersetujuan['kategori'] = "PERSETUJUAN";
            // ApprovalRetur::create($dataPersetujuan);

            $dataPersetujuan2['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan2['retur_id'] = $id;
            $dataPersetujuan2['user_id'] = Auth::user()->id;
            $dataPersetujuan2['user_name'] = Auth::user()->name;
            $dataPersetujuan2['role_to_name'] = 'Bendahara Materil';
            $dataPersetujuan2['type'] = 'Menunggu Persetujuan Bendahara Materil';
            $dataPersetujuan2['status'] = '';
            $dataPersetujuan2['keterangan'] = $request->keterangan ?? '';
            $dataPersetujuan2['tindak_lanjut'] = '';
            $dataPersetujuan2['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan2['kategori'] = "APPROVAL";
            ApprovalRetur::create($dataPersetujuan2);

            // Update Retur Barang
            DB::commit();
            return redirect()->route('retur-barang.index')->with(['success' => 'Retur Berhasil Dibuat !']);

        } catch (\Throwable $th) {
            //throw $th;

            DB::rollBack();
            return redirect()->route('retur-barang.index')->with(['failed' => 'Gagal dibuat !']);
        }

    }

    public function approvalTindakLanjut(Request $request, $id)
    {

        $returBarang = ReturBarang::find($id);
        // update approval sebelumnya
        ApprovalRetur::where('retur_id', $id)
            ->orderBy('id', 'desc')
            ->first()
            ->update([
                'status' => 'done',
                'tindak_lanjut' => '',
                'keterangan' => $request->keterangan ?? ''
            ]);

        if ($request->tindak_lanjut == 'SETUJUI') {
            DB::beginTransaction();


            if (Auth::user()->role->name == 'Bendahara Materil') {

                // APPROVAL BENDAHARA MATERIL
                $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                $dataApproval['retur_id'] = $id;
                $dataApproval['user_id'] = Auth::user()->id;
                $dataApproval['user_name'] = Auth::user()->name ?? '';
                $dataApproval['role_to_name'] = 'Bendahara Materil' ?? '';

                $dataApproval['type'] = 'Disetujui Bendahara Materil';
                $dataApproval['status'] = 'done';

                $dataApproval['keterangan'] = $request->keterangan ?? '';

                $dataApproval['tindak_lanjut'] = $request->tindak_lanjut;
                $dataApproval['approve_by_id'] = Auth::user()->id;
                $dataApproval['kategori'] = 'PERSETUJUAN';
                ApprovalRetur::create($dataApproval);

                // Buat Approval Persetujuan
                $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
                $dataPersetujuan['retur_id'] = $id;
                $dataPersetujuan['user_id'] = Auth::user()->id;
                $dataPersetujuan['user_name'] = Auth::user()->name;
                $dataPersetujuan['role_to_name'] = 'Pengelola Gudang';
                $dataPersetujuan['type'] = 'Menunggu Persetujuan Pengelola Gudang';
                $dataPersetujuan['status'] = '';
                $dataPersetujuan['keterangan'] = $request->keterangan ?? '';
                $dataPersetujuan['tindak_lanjut'] = '';
                $dataPersetujuan['approve_by_id'] = Auth::user()->id;
                $dataPersetujuan['kategori'] = "APPROVAL";
                ApprovalRetur::create($dataPersetujuan);
            }
            ReturBarang::where('id', $id)
                ->update(['status' => 'Dalam Proses']);


            DB::commit();
            return redirect()->route('retur-barang.approval-review', $id)->with(['success' => 'Data Berhasil Disetujui !']);
        } elseif ($request->tindak_lanjut == 'TOLAK') {
            DB::beginTransaction();
            // APPROVAL PROCESS
            $dataApproval['timestamp'] = date('Y-m-d H:i:s');
            $dataApproval['retur_id'] = $id;
            $dataApproval['user_id'] = Auth::user()->id;
            $dataApproval['user_name'] = Auth::user()->name ?? '';
            $dataApproval['role_to_name'] = '' ?? '';


            $dataApproval['type'] = 'Permintaan Ditolak '. Auth::user()->role->name ??null;
            $dataApproval['status'] = '';

            $dataApproval['keterangan'] = $request->keterangan ?? 'Ditolak';

            $dataApproval['tindak_lanjut'] = $request->tindak_lanjut;
            $dataApproval['approve_by_id'] = Auth::user()->id;
            $dataApproval['kategori'] = 'APPROVAL';

            ApprovalRetur::create($dataApproval);

            ReturBarang::where('id', $id)
                ->update(['status' => 'Ditolak']);
            DB::commit();
            return redirect()->route('retur-barang.approval-review', $id)->with(['success' => 'Data Berhasil ditolak !']);

        } elseif ($request->tindak_lanjut == 'BARANG SIAP') {
            DB::beginTransaction();
            // APPROVAL Pengelola Gudang
            $dataApproval['timestamp'] = date('Y-m-d H:i:s');
            $dataApproval['retur_id'] = $id;
            $dataApproval['user_id'] = Auth::user()->id;
            $dataApproval['user_name'] = Auth::user()->name ?? '';
            $dataApproval['role_to_name'] = 'Pengelola Gudang' ?? '';

            $dataApproval['type'] = 'Pengelola Gudang Siap untuk menerima barang';
            $dataApproval['status'] = 'done';

            $dataApproval['keterangan'] = $request->keterangan ?? '';

            $dataApproval['tindak_lanjut'] = $request->tindak_lanjut;
            $dataApproval['approve_by_id'] = Auth::user()->id;
            $dataApproval['kategori'] = 'PERSETUJUAN';
            ApprovalRetur::create($dataApproval);

            // Buat Approval Persetujuan
            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['retur_id'] = $id;
            $dataPersetujuan['user_id'] = Auth::user()->id;
            $dataPersetujuan['user_name'] = Auth::user()->name;
            $dataPersetujuan['role_to_name'] = $returBarang->user->role->name ?? null;
            $dataPersetujuan['type'] = 'Menunggu Barang Diserahkan '. $returBarang->user->role->name ?? null;
            $dataPersetujuan['status'] = '';
            $dataPersetujuan['keterangan'] = $request->keterangan ?? '';
            $dataPersetujuan['tindak_lanjut'] = '';
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = "APPROVAL";
            ApprovalRetur::create($dataPersetujuan);
            ReturBarang::where('id', $id)
                ->update(['status' => 'Dalam Proses']);


            DB::commit();
            return redirect()->route('retur-barang.approval-review', $id)->with(['success' => 'Siap menerima barang !']);
        } elseif ($request->tindak_lanjut == 'SERAHKAN BARANG') {
            DB::beginTransaction();

            // APPROVAL
            $dataApproval['timestamp'] = date('Y-m-d H:i:s');
            $dataApproval['retur_id'] = $id;
            $dataApproval['user_id'] = Auth::user()->id;
            $dataApproval['user_name'] = Auth::user()->name ?? '';
            $dataApproval['role_to_name'] = '' ?? '';

            $dataApproval['type'] = 'Barang diserahkan';
            $dataApproval['status'] = 'done';

            $dataApproval['keterangan'] = $request->keterangan ?? '';

            $dataApproval['tindak_lanjut'] = $request->tindak_lanjut;
            $dataApproval['approve_by_id'] = Auth::user()->id;
            $dataApproval['kategori'] = 'PERSETUJUAN';
            ApprovalRetur::create($dataApproval);

            // Buat Approval Persetujuan
            $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
            $dataPersetujuan['retur_id'] = $id;
            $dataPersetujuan['user_id'] = Auth::user()->id;
            $dataPersetujuan['user_name'] = Auth::user()->name;
            $dataPersetujuan['role_to_name'] = 'Pengelola Gudang' ?? null;
            $dataPersetujuan['type'] = 'Menunggu Barang Diterima Pengelola Gudang' ?? null;
            $dataPersetujuan['status'] = '';
            $dataPersetujuan['keterangan'] = $request->keterangan ?? '';
            $dataPersetujuan['tindak_lanjut'] = '';
            $dataPersetujuan['approve_by_id'] = Auth::user()->id;
            $dataPersetujuan['kategori'] = "APPROVAL";
            ApprovalRetur::create($dataPersetujuan);

            ReturBarang::where('id', $id)
                ->update([
                    'status' => 'Dalam Proses',
                    'nomor_bast' => $this->generateNomorBast()
                ]);

            DB::commit();
            return redirect()->route('retur-barang.approval-review', $id)->with(['success' => 'Barang diserahkan !']);
        } elseif ($request->tindak_lanjut == 'TERIMA BARANG') {
            DB::beginTransaction();

            // APPROVAL
            $dataApproval['timestamp'] = date('Y-m-d H:i:s');
            $dataApproval['retur_id'] = $id;
            $dataApproval['user_id'] = Auth::user()->id;
            $dataApproval['user_name'] = Auth::user()->name ?? '';
            $dataApproval['role_to_name'] = '' ?? '';

            $dataApproval['type'] = 'Barang Diterima Pengelola Gudang';
            $dataApproval['status'] = 'done';

            $dataApproval['keterangan'] = $request->keterangan ?? '';

            $dataApproval['tindak_lanjut'] = $request->tindak_lanjut;
            $dataApproval['approve_by_id'] = Auth::user()->id;
            $dataApproval['kategori'] = 'PERSETUJUAN';
            ApprovalRetur::create($dataApproval);


            ReturBarang::where('id', $id)
                ->update([
                    'status' => 'Selesai',
                    'nomor_bast' => $this->generateNomorBast()
                ]);

            ReturDetail::where('retur_id',$id)
                ->update(['status'=>'done']);
            DB::commit();
            return redirect()->route('retur-barang.approval-review', $id)->with(['success' => 'Barang Diterima !']);
        }else {
            dd('NOT AVAILABLE');
        }
    }

    public function cetakBast(Request $request, $id)
    {
        $data['title'] = 'BAST';

        $retur = ReturBarang::where('id', $id)
            ->first();
        $data['data'] = $retur;
        $data['pihak_pertama'] = ApprovalRetur::where('retur_id', $id)
        ->where('type', 'Barang diserahkan')
        ->first();
        $data['bast'] = ApprovalRetur::where('retur_id', $id)
        ->where('type', 'Barang Diterima Pengelola Gudang')
        ->first();


        if ($request->v == 'html') {
            return view('pdf.bast-retur', $data);
        }




        $pdf = PDF::loadView('pdf.bast-retur', $data)->setOptions(['isRemoteEnabled' => true, "isPhpEnabled" => true])->setPaper('a4', 'potrait');
        // $font = Font_Metrics::get_font("helvetica", "bold");

        return $pdf->stream($retur->nomor_bast . ' (BAST).pdf');
    }
    public function batalkan($id)
    {
        try {
            DB::beginTransaction();
            ReturDetail::where('retur_id', $id)->delete();
            ReturBarang::whereId($id)->delete();
            DB::commit();
            return redirect()->route('retur-barang.index')->with(['success' => 'Retur Dibatalkan !']);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('retur-barang.index')->with(['failed' => 'Gagal dibatalkan !']);
        }
    }



    public function hapusBarang($id)
    {
        try {
            DB::beginTransaction();
            ReturDetail::where('id', $id)->delete();
            DB::commit();
            return redirect()->route('retur-barang.create')->with(['success' => 'Barang Berhasil Dihapus !']);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('retur-barang.create')->with(['failed' => 'Gagal dihapus !']);
        }
    }

    private function generateNomorRetur()
    {



        $roleName = Auth::user()->role->name;
        $kode = $this->getKode($roleName);

        $year_now = date('Y');
        $month_now = date('m');
        $obj = DB::table('retur_barang')
        ->select('nomor_retur')
        ->latest('id')
            ->where('nomor_retur', 'ilike', '%'.$year_now . '%')
            ->first();

        if ($obj) {
            $increment = explode('.', $obj->nomor_retur);
            $removed1char = substr($increment[1], 1);
            $increment = explode('/', $removed1char);
            $removed1char = substr($increment[0], 1);


            $generateOrder_nr =
            'R.' . str_pad($removed1char + 1, 3, "0", STR_PAD_LEFT) .'/' . $month_now . '/SIM/' . $kode . '-' . $year_now;
        } else {
            $generateOrder_nr = 'R.001/'.$month_now.'/SIM/'.$kode.'-'.$year_now;
        }
        return $generateOrder_nr;
    }


    private function generateNomorBast()
    {
        $roleName = Auth::user()->role->name;
        $kode = $this->getKode($roleName);

        $year_now = date('Y');
        $month_now = date('m');
        $obj = DB::table('retur_barang')
        ->select('nomor_bast')
        ->latest('id')
            ->where('nomor_bast', 'ilike', '%' . $year_now . '%')
            ->first();

        if ($obj) {

            $increment = explode('/', $obj->nomor_bast);
            $removed1char = substr($increment[0], 1);


            $generateOrder_nr =
                 str_pad($removed1char + 1, 3, "0", STR_PAD_LEFT) . '/BASTR/' . $kode .'/'.$this->getRomawi($month_now). '-' . $year_now;
        } else {
            $generateOrder_nr = '001/BASTR/' . $kode .'/'.$this->getRomawi($month_now). '-' . $year_now;
        }
        return $generateOrder_nr;
    }
    private function getRomawi($bln)
    {
        switch ($bln) {
            case 1:
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }
    private function getKode($roleName)
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

        if ($roleName == 'Nakhoda') {
            $kode = 'KNK';
        } else if ($roleName == 'Kepala VTS') {
            $kode = 'VTS';
        } else if ($roleName == 'Kepala SROP') {
            $kode = 'SROP';
        } else if (
            $roleName == 'Kepala Distrik Navigasi' ||
            $roleName == 'Kabag Tata Usaha' ||
            $roleName == 'Subbag Kepegawaian dan Umum' ||
            $roleName == 'Subbag Keuangan'
        ) {
            $kode = 'TU';
        } else if (
            $roleName == 'Kabid Operasi' ||
            $roleName == 'Seksi Program' ||
            $roleName == 'Seksi Sarana Prasarana'
        ) {
            $kode = 'OPS';
        } else if (
            $roleName == 'Kabid Logistik' ||
            $roleName == 'Seksi Pengadaan' ||
            $roleName == 'Seksi Inventaris'
        ) {
            $kode = 'LOG';
        } else if (
            $roleName == 'Kepala Kelompok Pengamatan Laut'
        ) {
            $kode = 'PENGLA';
        } else if (
            $roleName == 'Kepala Kelompok Bengkel'
        ) {
            $kode = 'BKL';
        } else if (
            $roleName == 'Kepala Kelompok SBNP'
        ) {
            $kode = 'SBNP';
        } else {
            $kode = 'NONUSER';
        }

        return $kode;
    }
}
