<?php

namespace App\Http\Controllers;

use App\BarangKeluar;
use App\BarangMasuk;
use App\PermintaanBarang;
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
}
