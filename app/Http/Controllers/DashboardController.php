<?php

namespace App\Http\Controllers;

use App\BarangKeluar;
use App\BarangMasuk;
use App\BarangPersediaan;
use App\LaporanPengawasan;
use App\MenaraSuar;
use App\PelampungSuar;
use App\Perairan;
use App\RambuSuar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class DashboardController extends Controller
{
    public function __construct()
    {
    }
    public function index(Request $request)
    {

        $dataChart = BarangPersediaan::distinct('kategori_barang_id')
        
            ->get();

        $kategori = [];
        $saldo = [];
        foreach ($dataChart as $key => $dc) {
            // $barangMasuk = BarangMasuk::where('barang_id', $dc->id)->get();
            // $barangKeluar = BarangKeluar::where('barang_keluar_id', $dc->id)->get();


            // $totalBarangMasuk = 0;
            // foreach ($barangMasuk as $key => $value) {
            //     $totalBarangMasuk += $value->harga_perolehan * $value->jumlah;
            // }

            // $totalBarangKeluar = 0;
            // foreach ($barangKeluar as $key => $value) {
            //     $totalBarangKeluar += $value->harga_perolehan * $value->jumlah;
            // }
            // $total = $totalBarangMasuk - $totalBarangKeluar;

            $saldo[] = $dc->stokBarang()*$dc->harga_perolehan;
            $kategori[] = $dc->kategori_barang->nama_kategori ?? null;
        }

        $data['kategori']= $kategori;
        $data['saldo']= $saldo;



        $data['request'] = $request;


        return view('dashboard.index', $data);

    }

    public function index2(Request $request)
    {
        $kd_dokter = Auth::user()->fs_kd_peg;


        $date_from = date('Y-m-d', strtotime($request->date_from)) ?? date('Y-m-d');
        $date_to = date('Y-m-d', strtotime($request->date_to)) ?? date('Y-m-d');
        $data['perairan'] = Perairan::orderBy('id', 'desc')->get();

        if($request->type == 'tahun'){
            $dataBulan = [
                date('Y') . '-01',
                date('Y') . '-02',
                date('Y') . '-03',
                date('Y') . '-04',
                date('Y') . '-05',
                date('Y') . '-06',
                date('Y') . '-07',
                date('Y') . '-08',
                date('Y') . '-09',
                date('Y') . '-10',
                date('Y') . '-11',
                date('Y') . '-12',
            ];
        }else{
            $dataBulan = [];
            for ($i=1; $i <= date('t') ; $i++) {
                if(strlen($i)<2){
                    $i= '0'.$i;
                }
                $dataBulan[] = date('Y-') .date('m-').$i;
            }
        }


        $dataChart =[];
        foreach ($dataBulan as $key => $value) {
            $dataLaporan = LaporanPengawasan::where('tanggal_laporan','like','%'.$value.'%')->get();
            $totalData = $dataLaporan->count();
            $keandalan = 0;
            $kondisiTeknis = 0;
            $kelainan = 0;
            if ($totalData != 0) {
                foreach ($dataLaporan as $v) {
                    $keandalan += $v->keandalan();
                    $kondisiTeknis += $v->kondisiTeknis();
                    $kelainan += $v->kelainan();
                }
                $keandalan = $keandalan / $totalData;
                $kondisiTeknis = $kondisiTeknis / $totalData;
                $kelainan = $kelainan / $totalData;
            }
            $dataChart['keandalan'][] =  (float)number_format($keandalan,2);
            $dataChart['kondisiTeknis'][] =  (float)number_format($kondisiTeknis,2);
            $dataChart['kelainan'][] =  (float)number_format($kelainan,2);
        }

        $data['data_chart'] = $dataChart;
        $data['data_bulan'] = $dataBulan;

        return view('dashboard.index-2', $data);
    }



    private function filterReport($model, $request,$typeSbnp)
    {
        if ($request->penyelenggara != '') {
            $model = $model->where('adm_type_penyelenggara', $request->penyelenggara);
        }

        if ($request->perairan != '') {
            $model = $model->where('adm_perairan_id', $request->perairan);
        }

        if($request->sbnp != ''){
            if ($request->sbnp == $typeSbnp) {
                return $model = $model->get();
            } else {
                $model= $model->where('id', 0);
                return $model = $model->get();
            }
        }else{
            return $model = $model->get();

        }

    }


}
