<?php

namespace App\Http\Controllers;

use App\BarangPersediaan;
use App\KategoriBarang;
use App\PermintaanBarang;
use App\PermintaanBarangDetail;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermintaanBarangController extends Controller
{
    public function index(){
        $data['page_title'] = "Permintaan Barang";
        $data['permintaan_barang'] = PermintaanBarang::where('user_id',Auth::user()->id)
            ->get();
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
                $generatePermintaanBarang['nomor_upp3'] = $this->generateNomorUpp3();
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
            $generatePermintaanBarang['nomor_upp3'] = $this->generateNomorUpp3();
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
                $BarangPersediaan = BarangPersediaan::select('*');
            }else{
                $BarangPersediaan = BarangPersediaan::where('kategori_barang_id', $request->kategori);
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
            $data['barang_persediaan'] = [];
        }


        return view('permintaan-barang.create', $data);
    }
    public function detail($id)
    {
        $permintaanBarang =
        PermintaanBarang::where('id', $id)
        ->first();
        $data['page_title'] = $permintaanBarang->nomor_nota_dinas;
        $data['data'] = $permintaanBarang;
        return view('permintaan-barang.detail', $data);
    }


    public function addBarang(Request $request,$id, $permintaanBarangId){

        try {
            PermintaanBarangDetail::updateOrCreate(
                ['barang_persediaan_id'=>$id, 'permintaan_barang_id' => $permintaanBarangId]
                ,
                [
                    'barang_persediaan_id' => $id,
                    'permintaan_barang_id' => $permintaanBarangId,
                    'jumlah' => $request->jumlah,
                    'berita_tambahan' => $request->berita_tambahan,
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
            $dataPengajuan['status'] = 'diproses';
            $dataPengajuan['perihal'] = $request->perihal;
            PermintaanBarang::where('id', $id)
                ->update($dataPengajuan);
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

    private function generateNomorUpp3()
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

        $roleName = Auth::user()->role->name;
        $kode = $this->getKode($roleName);

        $year_now = date('Y');
        $month_now = date('m');
        $obj = DB::table('permintaan_barang')
        ->select('nomor_upp3')
        ->latest('id')
        ->where('nomor_upp3', 'like', '%' . $month_now . '/' . $kode . '%')
        ->first();

        if ($obj) {
            $increment = explode('/', $obj->nomor_upp3);
            $increment = explode('/', $increment[0]);
            $increment = explode('.', $increment[0]);
            $removed1char = substr($increment[1], 1);
            $generateOrder_nr = 'UPP3.' . str_pad($removed1char + 1, 3, "0", STR_PAD_LEFT) . '/' . $month_now . '/' . $kode . '-' . $year_now;
        } else {
            $generateOrder_nr = 'UPP3.' . str_pad(1, 3, "0", STR_PAD_LEFT) . '/' . $month_now . '/' . $kode . '-' . $year_now;
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

        if ($roleName == 'Nakhoda') {
            $kode = 'KNK';
        } else if ($roleName == 'Manager VTS') {
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
