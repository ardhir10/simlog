<?php

namespace App\Http\Controllers;

use App\BarangPersediaan;
use App\Exports\BarangExport;
use App\KategoriBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request){
        $data['page_title'] = "Laporan Stock Opname";
        $data['kategori'] = KategoriBarang::get();

        Cache::forget('laporan');
        $value = Cache::remember('laporan', 60 * 60, function ()  use($request){
            if (count($request->all())) {
                # code...
                if ($request->posisi && $request->year) {
                    $barangPersediaan = BarangPersediaan::where('kategori_barang_id', $request->posisi)
                    ->where('tahun_perolehan', $request->year)
                    ->distinct('nama_barang', 'harga_perolehan')->get();
                } elseif ($request->posisi) {
                    $barangPersediaan = BarangPersediaan::where('kategori_barang_id', $request->posisi)->distinct('nama_barang', 'harga_perolehan')->get();
                } elseif ($request->year) {
                    $barangPersediaan = BarangPersediaan::where('tahun_perolehan', $request->year)
                    ->distinct('nama_barang', 'harga_perolehan')->get();
                } else {
                    $barangPersediaan = BarangPersediaan::distinct('nama_barang', 'harga_perolehan')->get();
                }
                // $barangPersediaan->distinct('nama_barang', 'harga_perolehan')->get();
                // $barangPersediaan->distinct('nama_barang', 'harga_perolehan')->get();
                $laporans = [];
                $grandTotal = 0;
                foreach ($barangPersediaan as $key => $bp) {
                    $dataLaporan['kode_barang'] = $bp->kode_barang;
                    $dataLaporan['nama_barang'] = $bp->nama_barang;
                    $dataLaporan['tahun_perolehan'] = $bp->tahun_perolehan;
                    $dataLaporan['satuan'] = $bp->satuan->nama_satuan;
                    $dataLaporan['mata_uang'] = $bp->mata_uang;
                    $dataLaporan['harga_perolehan'] = $bp->harga_perolehan;
                    $dataLaporan['posisi_barang'] = $bp->kategori_barang->nama_kategori;

                    $dataLaporan['umum'] = $this->getTotalByPeruntukkan($bp->nama_barang, '01', $dataLaporan['harga_perolehan'], $bp->id);
                    $dataLaporan['sbnp'] = $this->getTotalByPeruntukkan($bp->nama_barang, '08', $dataLaporan['harga_perolehan'], $bp->id);
                    $dataLaporan['telkompel'] = $this->getTotalByPeruntukkan($bp->nama_barang, '09', $dataLaporan['harga_perolehan'], $bp->id);
                    $dataLaporan['pengla'] = $this->getTotalByPeruntukkan($bp->nama_barang, '10', $dataLaporan['harga_perolehan'], $bp->id);
                    $dataLaporan['knk'] = $this->getTotalByPeruntukkan($bp->nama_barang, '11', $dataLaporan['harga_perolehan'], $bp->id);
                    $dataLaporan['bengkel'] = $this->getTotalByPeruntukkan($bp->nama_barang, '12', $dataLaporan['harga_perolehan'], $bp->id);
                    $dataLaporan['siekepeg'] = $this->getTotalByPeruntukkan($bp->nama_barang, '02', $dataLaporan['harga_perolehan'], $bp->id);
                    $dataLaporan['siekeuangan'] = $this->getTotalByPeruntukkan($bp->nama_barang, '03', $dataLaporan['harga_perolehan'], $bp->id);
                    $dataLaporan['siepengadaan'] = $this->getTotalByPeruntukkan($bp->nama_barang, '04', $dataLaporan['harga_perolehan'], $bp->id);
                    $dataLaporan['sieinventaris'] = $this->getTotalByPeruntukkan($bp->nama_barang, '05', $dataLaporan['harga_perolehan'], $bp->id);
                    $dataLaporan['sarpras'] = $this->getTotalByPeruntukkan($bp->nama_barang, '06', $dataLaporan['harga_perolehan'], $bp->id);
                    $dataLaporan['sieprogram'] = $this->getTotalByPeruntukkan($bp->nama_barang, '07', $dataLaporan['harga_perolehan'], $bp->id);
                    $stokTotal =
                    $dataLaporan['umum']
                        + $dataLaporan['sbnp']
                        + $dataLaporan['telkompel']
                        + $dataLaporan['pengla']
                        + $dataLaporan['knk']
                        + $dataLaporan['bengkel']
                        + $dataLaporan['siekepeg']
                        + $dataLaporan['siekeuangan']
                        + $dataLaporan['siepengadaan']
                        + $dataLaporan['sieinventaris']
                        + $dataLaporan['sarpras']
                        + $dataLaporan['sieprogram'];

                    $dataLaporan['stock'] = $stokTotal;
                    $dataLaporan['jumlah_total'] = $stokTotal * $bp->harga_perolehan;


                    $grandTotal += $dataLaporan['jumlah_total'];

                    $laporans[] = $dataLaporan;
                }
                return ['laporan' => $laporans, 'grandTotal' => $grandTotal];

            }else{
                return ['laporan' => [], 'grandTotal' => 0];

            }



        });





        $data['laporans'] = $value['laporan'];
        $data['grand_total'] = number_format($value['grandTotal']);
        return view('laporan.index',$data);
    }

    public function exportExcel(){

        $value = Cache::remember('laporan', 60 * 60, function () {
            $barangPersediaan = BarangPersediaan::distinct('nama_barang', 'harga_perolehan')->get();
            $laporans = [];
            $grandTotal = 0;
            foreach ($barangPersediaan as $key => $bp) {
                $dataLaporan['kode_barang'] = $bp->kode_barang;
                $dataLaporan['nama_barang'] = $bp->nama_barang;
                $dataLaporan['tahun_perolehan'] = $bp->tahun_perolehan;
                $dataLaporan['satuan'] = $bp->satuan->nama_satuan;
                $dataLaporan['mata_uang'] = $bp->mata_uang;
                $dataLaporan['harga_perolehan'] = $bp->harga_perolehan;
                $dataLaporan['posisi_barang'] = $bp->kategori_barang->nama_kategori;

                $dataLaporan['umum'] = $this->getTotalByPeruntukkan($bp->nama_barang, '01', $dataLaporan['harga_perolehan'], $bp->id);
                $dataLaporan['sbnp'] = $this->getTotalByPeruntukkan($bp->nama_barang, '08', $dataLaporan['harga_perolehan'], $bp->id);
                $dataLaporan['telkompel'] = $this->getTotalByPeruntukkan($bp->nama_barang, '09', $dataLaporan['harga_perolehan'], $bp->id);
                $dataLaporan['pengla'] = $this->getTotalByPeruntukkan($bp->nama_barang, '10', $dataLaporan['harga_perolehan'], $bp->id);
                $dataLaporan['knk'] = $this->getTotalByPeruntukkan($bp->nama_barang, '11', $dataLaporan['harga_perolehan'], $bp->id);
                $dataLaporan['bengkel'] = $this->getTotalByPeruntukkan($bp->nama_barang, '12', $dataLaporan['harga_perolehan'], $bp->id);
                $dataLaporan['siekepeg'] = $this->getTotalByPeruntukkan($bp->nama_barang, '02', $dataLaporan['harga_perolehan'], $bp->id);
                $dataLaporan['siekeuangan'] = $this->getTotalByPeruntukkan($bp->nama_barang, '03', $dataLaporan['harga_perolehan'], $bp->id);
                $dataLaporan['siepengadaan'] = $this->getTotalByPeruntukkan($bp->nama_barang, '04', $dataLaporan['harga_perolehan'], $bp->id);
                $dataLaporan['sieinventaris'] = $this->getTotalByPeruntukkan($bp->nama_barang, '05', $dataLaporan['harga_perolehan'], $bp->id);
                $dataLaporan['sarpras'] = $this->getTotalByPeruntukkan($bp->nama_barang, '06', $dataLaporan['harga_perolehan'], $bp->id);
                $dataLaporan['sieprogram'] = $this->getTotalByPeruntukkan($bp->nama_barang, '07', $dataLaporan['harga_perolehan'], $bp->id);
                $stokTotal =
                    $dataLaporan['umum']
                    + $dataLaporan['sbnp']
                    + $dataLaporan['telkompel']
                    + $dataLaporan['pengla']
                    + $dataLaporan['knk']
                    + $dataLaporan['bengkel']
                    + $dataLaporan['siekepeg']
                    + $dataLaporan['siekeuangan']
                    + $dataLaporan['siepengadaan']
                    + $dataLaporan['sieinventaris']
                    + $dataLaporan['sarpras']
                    + $dataLaporan['sieprogram'];

                $dataLaporan['stock'] = $stokTotal;
                $dataLaporan['jumlah_total'] = $stokTotal * $bp->harga_perolehan;


                $grandTotal += $dataLaporan['jumlah_total'];

                $laporans[] = $dataLaporan;
            }

            return ['laporan'=>$laporans,'grandTotal'=>$grandTotal];
        });


        $data['laporans'] = $value['laporan'];
        $data['grand_total'] = number_format($value['grandTotal']);

        return Excel::download(new BarangExport($data), 'Laporan Stock Opname.xlsx');

    }

    private function getTotalByPeruntukkan($nama_barang,$kode, $harga_perolehan){
        $bp = BarangPersediaan::select('id')->where('nama_barang',$nama_barang)
            ->where('sub_sub_kategori',$kode)
            ->where('harga_perolehan', $harga_perolehan)
            ->get();
        if($bp){
            $totalStock = 0;
            foreach ($bp as $key => $value) {
                $totalStock += $value->stokBarang();
            }
            return $totalStock;
        }else{
            return 0;
        }
    }
}
