<?php

namespace App\Http\Controllers;

use App\ApprovalProcess;
use App\BarangPersediaan;
use App\KategoriBarang;
use App\LaporanDistribusi;
use App\PermintaanBarang;
use App\PermintaanBarangDetail;
use App\User;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermintaanBarangController extends Controller
{
    public function index(){
        $data['page_title'] = "Permintaan Barang";

        if ((Auth::user()->role->name ?? null) == 'Kepala Distrik Navigasi') {
            $data['permintaan_barang'] = PermintaanBarang::orderBy('id', 'desc')
            ->get();
        }
        else if ((Auth::user()->role->name ?? null) == 'Pengelola Gudang') {
            $data['permintaan_barang'] = PermintaanBarang::orderBy('id', 'desc')
                ->get();
        } else if ((Auth::user()->role->name ?? null) == 'Kabid Logistik') {
            $data['permintaan_barang'] = PermintaanBarang::orderBy('id', 'desc')
                ->get();
        }
        else if (
            (Auth::user()->role->name ?? null) == 'Admin SIMLOG' ||
            (Auth::user()->role->name ?? null) == 'Kasie Pengadaan' ||
            (Auth::user()->role->name ?? null) == 'Bendahara Materil' ||
            (Auth::user()->role->name ?? null) == 'Staff Seksi Pengadaan' ||
            (Auth::user()->role->name ?? null) == 'Kasie Inventaris' ||
            (Auth::user()->role->name ?? null) == 'Kabid Operasi' ||
            (Auth::user()->role->name ?? null) == 'Kasie Program' ||
            (Auth::user()->role->name ?? null) == 'Kasie Sarpras' ||
            (Auth::user()->role->name ?? null) == 'Kabag Tata Usaha' ||
            (Auth::user()->role->name ?? null) == 'Kasie Kepeg & Umum' ||
            (Auth::user()->role->name ?? null) == 'Kasie Keuangan'
            ) {
            $data['permintaan_barang'] = PermintaanBarang::orderBy('id', 'desc')
                ->get();
        } else if ((Auth::user()->role->name ?? null) == 'Kurir/Offsetter') {
            $data['permintaan_barang'] = PermintaanBarang::whereHas('approvals',function($q){
                $q->where('approve_by_id',Auth::user()->id);
            })->orderBy('id', 'desc')
                ->get();
        }

        else{
            $data['permintaan_barang'] = PermintaanBarang::where('user_id', Auth::user()->id)
                ->orderBy('id', 'desc')
            ->get();
        }

        return view('permintaan-barang.index',$data);
    }

    public function create(Request $request){
        $data['page_title'] = "Form Permintaan Barang";
        $userId = Auth::user()->id;
        // --- Saat Awal Masuk Langsung Create Form Pemintaan Barang


        $permintaanBarang = PermintaanBarang::where('user_id', $userId)
            ->where('is_draft',true)
            ->first();
        if($permintaanBarang){


            // Jika sudah ada dan tanggal permintaan berbeda akan update data
            if(date('Y-m-d',strtotime($permintaanBarang->tanggal_permintaan)) != date('Y-m-d')){
                $generatePermintaanBarang['user_id'] = $userId;
                $generatePermintaanBarang['is_draft'] = true;
                $generatePermintaanBarang['nomor_nota_dinas'] = $this->generateNomorNotaDinas();
                $generatePermintaanBarang['nomor_upp3'] = $this->generateNomorUpp3($generatePermintaanBarang['nomor_nota_dinas']);
                $generatePermintaanBarang['tanggal_permintaan'] = date('Y-m-d H:i:s');
                $generatePermintaanBarang['perihal'] = null;
                $generatePermintaanBarang['status'] = 'draft';
                PermintaanBarang::where('id',$permintaanBarang->id)
                    ->update($generatePermintaanBarang);

                $permintaanBarang = PermintaanBarang::where('user_id', $userId)
                ->where('is_draft', true)
                ->first();
            }

            $data['data'] = $permintaanBarang;
        }else{
            $generatePermintaanBarang['user_id'] = $userId;
            $generatePermintaanBarang['is_draft'] = true;
            $generatePermintaanBarang['nomor_nota_dinas'] = $this->generateNomorNotaDinas();
            $generatePermintaanBarang['nomor_upp3'] = $this->generateNomorUpp3($generatePermintaanBarang['nomor_nota_dinas']);
            $generatePermintaanBarang['tanggal_permintaan'] = date('Y-m-d H:i:s');
            $generatePermintaanBarang['perihal'] = null;
            $generatePermintaanBarang['status'] = 'draft';
            $permintaanBarang = PermintaanBarang::create($generatePermintaanBarang);
            $data['data'] = $permintaanBarang;
        }
        $data['barang_diminta'] = PermintaanBarangDetail::where('permintaan_barang_id', $permintaanBarang->id)->get();
        $data['kategori_barang'] = KategoriBarang::orderBy('id','desc')->get();

        if($request->kategori){
            if($request->kategori == 'all'){
                $BarangPersediaan = BarangPersediaan::select('*')
                ->where(function($q){
                    $q->where('sub_sub_kategori',User::where('id', Auth::user()->id)->first()->getSubKategoriKode() ?? null)
                        ->orWhere('sub_sub_kategori','01');

                });
            }else{
                $BarangPersediaan = BarangPersediaan::where('kategori_barang_id', $request->kategori)
                    ->where(function ($q) {
                        $q->where('sub_sub_kategori', User::where('id', Auth::user()->id)->first()->getSubKategoriKode() ?? null)
                            ->orWhere('sub_sub_kategori', '01');
                    });;
            }
            $namaKode = $request->namakode;
            if($request->namakode){
                $BarangPersediaan->where(function($q) use($namaKode){
                    $q->where('nama_barang','ilike','%'. $namaKode.'%')
                    ->orWhere('kode_barang','ilike','%'. $namaKode.'%');
                });
            }

            $data['barang_persediaan'] = $BarangPersediaan->orderBy('id', 'desc')->get();
        }else{
            $BarangPersediaan = BarangPersediaan::select('*')
                ->where(function ($q) {
                    $q->where('sub_sub_kategori', User::where('id', Auth::user()->id)->first()->getSubKategoriKode() ?? null)
                        ->orWhere('sub_sub_kategori', '01');
                })
            ->get();
            $data['barang_persediaan'] = $BarangPersediaan;
        }


        return view('permintaan-barang.create', $data);
    }
    public function detail($id)
    {
        $permintaanBarang =
            PermintaanBarang::where('id', $id)
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
                // return $query
                // ->distinct('step');
                $query
                    ->orderBy('id', 'asc');
            }])
            ->first();
        $data['page_title'] = $permintaanBarang->nomor_nota_dinas;
        $data['data'] = $permintaanBarang;
        $data['kurir'] = User::whereHas('role',function($q){
            $q->where('name', 'Kurir/Offsetter');
        })->get();
        $data['laporan_distribusi'] = LaporanDistribusi::where('permintaan_barang_id',$id)->orderBy('id','desc')->first();
        return view('permintaan-barang.detail', $data);
    }


    public function addBarang(Request $request,$id, $permintaanBarangId){
        $dataBarangPersediaan = BarangPersediaan::where('id',$id)
            ->first();
        try {
            PermintaanBarangDetail::updateOrCreate(
                ['barang_persediaan_id'=>$id, 'permintaan_barang_id' => $permintaanBarangId]
                ,
                [
                    'barang_persediaan_id' => $id,
                    'permintaan_barang_id' => $permintaanBarangId,
                    'jumlah' => $request->jumlah,
                    'berita_tambahan' => $request->berita_tambahan,
                    // 'harga_perolehan' => $dataBarangPersediaan->harga_perolehan,
                ]
            );
            return redirect()->route('permintaan-barang.create')->with(['success' => 'Barang Berhasil Ditambahkan !']);
        } catch (\Throwable $th) {
            return redirect()->route('permintaan-barang.create')->with(['failed' => $th->getMessage()]);
        }
    }

    public function deleteBarang(Request $request,$id){
        try {
            PermintaanBarangDetail::where('id',$id)->delete();
            return redirect()->route('permintaan-barang.create')->with(['success' => 'Barang Berhasil dihapus !']);
        } catch (\Throwable $th) {
            return redirect()->route('permintaan-barang.create')->with(['failed' => $th->getMessage()]);
        }
    }

    public function batalkanPermintaan(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            PermintaanBarangDetail::where('permintaan_barang_id', $id)->delete();
            PermintaanBarang::where('id', $id)->delete();
            DB::commit();
            return redirect()->route('permintaan-barang.index')->with(['failed' => 'Permintaan dibatalkan !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('permintaan-barang.index')->with(['failed' => $th->getMessage()]);
        }
    }

    public function ajukanPermintaan(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $dataPengajuan['is_draft'] = false;
            $dataPengajuan['status'] = 'Diproses';
            $dataPengajuan['perihal'] = $request->perihal;
            PermintaanBarang::where('id', $id)
                ->update($dataPengajuan);

            $dataPermintaanBarang = PermintaanBarang::where('id', $id)->first();
            // --- APPROVAL PROCESS 1
            $dataApproval['timestamp'] = date('Y-m-d H:i:s');
            $dataApproval['permintaan_barang_id'] = $id;
            $dataApproval['user_peminta_id'] = $dataPermintaanBarang->user_id;
            $dataApproval['user_peminta_name'] = $dataPermintaanBarang->user->name ?? '';

            $dataApproval['role_to_name'] = 'Kepala Distrik Navigasi';

            $dataApproval['type'] = 'Dalam Proses Approval';
            $dataApproval['status'] = '';

            $dataApproval['step'] = 1;
            $dataApproval['keterangan'] = '';
            $dataApproval['kategori'] = 'APPROVAL';

            ApprovalProcess::create($dataApproval);

            // Saat permintaan diajukan akan ter record
            DB::commit();
            return redirect()->route('permintaan-barang.index')->with(['success' => 'Data Berhasil diajukan !']);
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('permintaan-barang.index')->with(['failed' => $th->getMessage()]);
        }
    }




    private function generateNomorNotaDinas(){


        $roleName = Auth::user()->role->name;
        $kode = $this->getKode($roleName);

        $year_now = date('Y');
        $month_now = date('m');
        $obj = DB::table('permintaan_barang')
            ->select('nomor_nota_dinas')
            ->latest('id')
            ->where('nomor_nota_dinas', 'like', '%'.$month_now.'/'.$kode . '%')
            ->first();

        if ($obj) {
            $increment = explode('/', $obj->nomor_nota_dinas);
            $increment = explode('/', $increment[0]);
            $increment = explode('.', $increment[0]);
            $removed1char = substr($increment[1], 1);
            $generateOrder_nr = 'PL.'. str_pad($removed1char + 1, 3, "0", STR_PAD_LEFT).'/'.$month_now .'/'. $kode.'-'.$year_now;
        } else {
            $generateOrder_nr = 'PL.'. str_pad(1, 3, "0", STR_PAD_LEFT).'/'.$month_now .'/'. $kode.'-'.$year_now;
        }
        return $generateOrder_nr;
    }

    public function pdfNotaDinas(Request $request, $id)
    {
        $data['title'] = 'Nota Tagih';

        $permintaanBarang = PermintaanBarang::where('id', $id)
            ->first();
        $data['data'] = $permintaanBarang;
        if ($request->v == 'html') {
            return view('pdf.nota-dinas', $data);
        }

        $pdf = PDF::loadView('pdf.nota-dinas', $data)->setOptions(['isRemoteEnabled' => true])->setPaper('a4', 'potrait');
        return $pdf->stream($permintaanBarang->nomor_nota_dinas . ' (Nota Dinas).pdf');
    }

    public function pdfUpp3(Request $request, $id)
    {
        $data['title'] = 'Nota Tagih';

        $permintaanBarang = PermintaanBarang::where('id', $id)
            ->first();
        $data['data'] = $permintaanBarang;
        if ($request->v == 'html') {
            return view('pdf.upp3', $data);
        }


        $pdf = PDF::loadView('pdf.upp3', $data)->setOptions(['isRemoteEnabled' => true, "isPhpEnabled"=>true])->setPaper('a4', 'potrait');
        // $font = Font_Metrics::get_font("helvetica", "bold");

        return $pdf->stream($permintaanBarang->nomor_upp3 . ' (UPP3).pdf');
    }

    public function pdfUpp4(Request $request, $id)
    {
        $data['title'] = 'UPP4';

        $permintaanBarang = PermintaanBarang::where('id', $id)
            ->first();
        $data['data'] = $permintaanBarang;
        if ($request->v == 'html') {
            return view('pdf.upp4', $data);
        }


        $pdf = PDF::loadView('pdf.upp4', $data)->setOptions(['isRemoteEnabled' => true, "isPhpEnabled" => true])->setPaper('a4', 'potrait');
        // $font = Font_Metrics::get_font("helvetica", "bold");

        return $pdf->stream($permintaanBarang->nomor_upp3 . ' (UPP3).pdf');
    }

    public function pdfBast(Request $request, $id)
    {
        $data['title'] = 'BAST';

        $permintaanBarang = PermintaanBarang::where('id', $id)
            ->first();
        $data['data'] = $permintaanBarang;
        if ($request->v == 'html') {
            return view('pdf.bast', $data);
        }


        $pdf = PDF::loadView('pdf.bast', $data)->setOptions(['isRemoteEnabled' => true, "isPhpEnabled" => true])->setPaper('a4', 'potrait');
        // $font = Font_Metrics::get_font("helvetica", "bold");

        return $pdf->stream($permintaanBarang->nomor_upp3 . ' (UPP3).pdf');
    }

    private function generateNomorUpp3($notaDinas)
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
        $kode = $this->getKode($roleName);

        $year_now = date('Y');
        $month_now = date('m');
        $obj = DB::table('permintaan_barang')
        ->select('nomor_upp3')
        ->latest('id')
        ->where('created_at','ilike',$year_now.'%')
        ->first();

        if ($obj) {
            $increment = explode('.', $obj->nomor_upp3);
            $removed1char = substr($increment[2], 1);
            $generateOrder_nr = 'L.'.$urutNotaDinas.'.'. str_pad($removed1char + 1, 4, "0", STR_PAD_LEFT).'.'.$this->bagianBidangUpp3($roleName);
        } else {
            $generateOrder_nr = 'L.'.$urutNotaDinas.'.'. str_pad(1, 4, "0", STR_PAD_LEFT).'.'.$this->bagianBidangUpp3($roleName);
        }
        return $generateOrder_nr;
    }


    private function getKode($roleName){

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

    private function bagianBidangUpp3($roleName)
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





}
