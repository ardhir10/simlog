<?php

namespace App\Http\Controllers;

use App\BarangKeluar;
use App\BarangMasuk;
use App\PermintaanBarang;
use App\RencanaKebutuhanTahunan;
use Illuminate\Http\Request;

class ApiDashboardController extends Controller
{
    public function nilaiBarangMasuk(Request $request){
        $year = $request->year;
        $month = $request->month;
        $dateRange = $year.'-'.$month;

        if($month == 'all'){
            $barangMasuk = BarangMasuk::get();
        }else{
            $barangMasuk = BarangMasuk::where('timestamp','ilike', $dateRange.'%')->get();
        }
        $total = 0;
        foreach ($barangMasuk as $key => $value) {
            $total+= $value->harga_perolehan* $value->jumlah;
        }
        $data['total'] = number_format($total,0,',','.');
        $data['request'] = $request->all();
        $data['dateRange'] = $dateRange;
        return  response()->json($data, 200);
    }

    public function nilaiBarangKeluar(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $dateRange = $year . '-' . $month;

        if ($month == 'all') {
            $barangKeluar = BarangKeluar::get();
        } else {
            $barangKeluar = BarangKeluar::where('timestamp', 'ilike', $dateRange . '%')->get();
        }
        $total = 0;
        foreach ($barangKeluar as $key => $value) {
            $total += $value->harga_perolehan * $value->jumlah;
        }
        $data['total'] = number_format($total, 0, ',', '.');
        $data['request'] = $request->all();
        $data['dateRange'] = $dateRange;
        return  response()->json($data, 200);
    }

    public function saldoBarang(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $dateRange = $year . '-' . $month;

        if ($month == 'all') {
            $barangMasuk = BarangMasuk::get();
            $barangKeluar = BarangKeluar::get();
        } else {
            $barangMasuk = BarangMasuk::where('timestamp', 'ilike', $dateRange . '%')->get();
            $barangKeluar = BarangKeluar::where('timestamp', 'ilike', $dateRange . '%')->get();
        }

        $totalBarangMasuk = 0;
        foreach ($barangMasuk as $key => $value) {
            $totalBarangMasuk += $value->harga_perolehan * $value->jumlah;
        }

        $totalBarangKeluar = 0;
        foreach ($barangKeluar as $key => $value) {
            $totalBarangKeluar += $value->harga_perolehan * $value->jumlah;
        }

        $total = $totalBarangMasuk-$totalBarangKeluar;
        $data['total'] = number_format($total, 0, ',', '.');
        $data['request'] = $request->all();
        $data['dateRange'] = $dateRange;
        return  response()->json($data, 200);
    }

    public function permintaanDisetujui(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $dateRange = $year . '-' . $month;

        if ($month == 'all') {
            $barangKeluar = PermintaanBarang:: where(function($q){
                $q->orWhere('status', 'Disetujui')
                ->orWhere('status', 'Selesai')
                ;
            })
            ->get();
        } else {
            $barangKeluar = PermintaanBarang::where('tanggal_permintaan', 'ilike', $dateRange . '%')
            ->where(function($q){
                $q->where('status', 'Diproses')
                ->orWhere('status', 'Disetujui')
                ->orWhere('status', 'Selesai')
                ;
            })
            ->get();
        }

        $data['total'] = $barangKeluar->count();
        $data['request'] = $request->all();
        $data['dateRange'] = $dateRange;
        return  response()->json($data, 200);
    }

    public function permintaanDitolak(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $dateRange = $year . '-' . $month;

        if ($month == 'all') {
            $barangKeluar = PermintaanBarang::where(function ($q) {
                $q->where('status', 'Ditolak');
            })
                ->get();
        } else {
            $barangKeluar = PermintaanBarang::where('tanggal_permintaan', 'ilike', $dateRange . '%')
                ->where(function ($q) {
                    $q->where('status', 'Ditolak');
                })
                ->get();
        }

        $data['total'] = $barangKeluar->count();
        $data['request'] = $request->all();
        $data['dateRange'] = $dateRange;
        return  response()->json($data, 200);
    }

    public function permintaanDalamproses(Request $request)
    {

            $pd = PermintaanBarang::where(function ($q) {
                $q->orWhere('status', 'Diproses');
            })
            ->get();


        $data['total'] = $pd->count();
        return  response()->json($data, 200);
    }

    public function nilaiDistribusi(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $dateRange = $year . '-' . $month;

        if ($month == 'all') {
            $permintaanBarang = PermintaanBarang::whereHas('laporanDistribusi')->get();
        } else {
            $permintaanBarang = PermintaanBarang::whereHas('laporanDistribusi',function($q) use($dateRange){
                $q->where('tanggal_waktu','ilike', $dateRange.'%');
            })
            ->get();
        }
        $total =0;
        foreach ($permintaanBarang as $key => $value) {
            foreach ($value->barang_diminta as $key => $v) {
                $total += 1;
            }
        }
        $data['total'] = $total;


        return  response()->json($data, 200);
    }

    public function nilaiBelumDistribusi(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $dateRange = $year . '-' . $month;

        if ($month == 'all') {
            $permintaanBarang = PermintaanBarang::doesntHave('laporanDistribusi')->get();
        } else {
            $permintaanBarang = PermintaanBarang::doesntHave('laporanDistribusi')
            ->where('tanggal_permintaan', 'ilike', $dateRange . '%')
            ->get();
        }
        $total = 0;
        foreach ($permintaanBarang as $key => $value) {
            foreach ($value->barang_diminta as $key => $v) {
                $total += 1;
            }
        }
        $data['total'] = $total;


        return  response()->json($data, 200);
    }

    public function rencanaTahunan(Request $request)
    {
        $year = $request->year ?? date('Y');
        $month = $request->month;
        $dateRange = $year . '-' . $month;
        $category = [
            '01',
            '02',
            '03',
            '04',
            '05',
            '06',
            '07',
            '08',
            '09',
            '10',
            '11',
            '12'
        ];
        $rkt = RencanaKebutuhanTahunan::where('tahun', $year)->first();

        $series = [
            ['01' => ['rkt'=> $rkt->p01]],
            ['02' => ['rkt'=> $rkt->p02]],
            ['03' => ['rkt'=> $rkt->p03]],
            ['04' => ['rkt'=> $rkt->p04]],
            ['05' => ['rkt'=> $rkt->p05]],
            ['06' => ['rkt'=> $rkt->p06]],
            ['07' => ['rkt'=> $rkt->p07]],
            ['08' => ['rkt'=> $rkt->p08]],
            ['09' => ['rkt'=> $rkt->p09]],
            ['10' => ['rkt'=> $rkt->p10]],
            ['11' => ['rkt'=> $rkt->p11]],
            ['12' => ['rkt'=> $rkt->p11]],
        ];



        $seriesRk = [];
        $seriesBk = [];
        foreach ($category as $key => $value) {
            $totalBk = 0;
            $bk = BarangKeluar::where('sub_sub_kategori', $value)->get();
            foreach ($bk as $key => $vbk) {
                $totalBk+= $vbk->harga_perolehan + $vbk->jumlah;
            }
            $seriesBk[] = $totalBk;
            $seriesRk[] = $rkt->{'p' . $value};
        }

        $category = array_map(function($a){
            return $this->peruntukkan($a);
        },$category);

        $data['category'] = $category;
        $data['series'] = [
            'rk'=> $seriesRk,
            'bk'=> $seriesRk,
        ];

        return  response()->json($data, 200);
    }

    private function peruntukkan($a)
    {
        // PENGGUNANYA
        switch ($a) {
            case '01':
                return 'Umum';
                break;
            case '02':
                return 'Sie Kepeg & Umum';
                break;
            case '03':
                return 'Sie Keuangan';
                break;
            case '04':
                return 'Sie Pengadaan';
                break;
            case '05':
                return 'Sie Inventaris';
                break;
            case '06':
                return 'SieSarPras';
                break;
            case '07':
                return 'Sie Program & Evaluasi';
                break;
            case '08':
                return 'SBNP';
                break;
            case '09':
                return 'Telkompel';
                break;
            case '10':
                return 'Pengla';
                break;
            case '11':
                return 'KNK';
                break;
            case '12':
                return 'Bengkel';
                break;
            default:
                return '';
                break;
        }
    }
}
