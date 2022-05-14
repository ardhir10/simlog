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

        $dataChart = BarangPersediaan::get();

        $kategori = [];
        $saldo = [];

        $dataBarangWithSaldo = [];
        foreach ($dataChart as $key => $dc) {
            $barangMasuk = BarangMasuk::where('barang_id', $dc->id)->get();
            $barangKeluar = BarangKeluar::where('barang_keluar_id', $dc->id)->get();


            $totalBarangMasuk = 0;
            foreach ($barangMasuk as $key => $value) {
                $totalBarangMasuk += $value->harga_perolehan * $value->jumlah;
            }

            $totalBarangKeluar = 0;
            foreach ($barangKeluar as $key => $value) {
                $totalBarangKeluar += $value->harga_perolehan * $value->jumlah;
            }
            $total = $totalBarangMasuk - $totalBarangKeluar;

            if(($dataBarangWithSaldo[$dc->kategori_barang->nama_kategori ?? null]??null) == null){
                $dataBarangWithSaldo[$dc->kategori_barang->nama_kategori ?? null] = $total;
            }else{
                $dataBarangWithSaldo[$dc->kategori_barang->nama_kategori ?? null] += $total;
            }

            $saldo[] = $total;
            $kategori[] = $dc->kategori_barang->nama_kategori ?? null;
        }

        $data['kategori']= array_keys($dataBarangWithSaldo);
        $data['saldo']= array_values($dataBarangWithSaldo);

        // dd(array_values($dataBarangWithSaldo));


        $data['request'] = $request;


        return view('dashboard.index', $data);

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
