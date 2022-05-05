<?php

namespace App\Http\Controllers;

use App\BarangPersediaan;
use App\RencanaKebutuhanTahunan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RencanaKebutuhanTahunanController extends Controller
{
    public function index(Request $request){
        $data['page_title'] = "Rencana Kebutuhan Tahunan";

        if($request->tahun){
            $data['rencana_kebutuhan_tahunan'] = RencanaKebutuhanTahunan::where('tahun',$request->tahun)->orderby('id', 'desc')->get();


        }else{

            $data['rencana_kebutuhan_tahunan'] = RencanaKebutuhanTahunan::where('tahun',$request->tahun)->orderby('id', 'desc')->get();
        }

        return view('rencana-kebutuhan-tahunan.index',$data);
    }
    public function create(){
        $data['page_title'] = "Buat Rencana Kebutuhan Tahunan";
        $data['barang_persediaan'] = BarangPersediaan::distinct('nama_barang')->get();
        $data['data'] = null;
        return view('rencana-kebutuhan-tahunan.create',$data);
    }
    public function edit($id){
        $data['page_title'] = "Edit Rencana Kebutuhan Tahunan";
        $data['barang_persediaan'] = BarangPersediaan::distinct('nama_barang')->get();
        $data['data'] = RencanaKebutuhanTahunan::find($id);
        return view('rencana-kebutuhan-tahunan.edit',$data);
    }

    public function store(Request $request,$id){

        try {
            if($id == 0){
                $cekData = RencanaKebutuhanTahunan::where('nama_barang',$request->nama_barang)
                    ->where('tahun',$request->tahun)
                    ->first();
                if($cekData){
                    return redirect()->route('rencana-kebutuhan-tahunan.create')->with(['failed' => 'Data Tahun Ini sudah Ada !']);
                }
                // CREATE BARU
                $dataInsert['nama_barang'] = $request->nama_barang;

                $barangPersediaan= BarangPersediaan::where('nama_barang',$request->nama_barang)->first();
                $dataInsert['nama_barang'] = $request->nama_barang;
                $dataInsert['kategori'] = $barangPersediaan->kategori_barang->nama_kategori ?? null;

                $dataInsert['tahun'] = $request->tahun;
                $dataInsert['p01'] = $request->p01;
                $dataInsert['p02'] = $request->p02;
                $dataInsert['p03'] = $request->p03;
                $dataInsert['p04'] = $request->p04;
                $dataInsert['p05'] = $request->p05;
                $dataInsert['p06'] = $request->p06;
                $dataInsert['p07'] = $request->p07;
                $dataInsert['p08'] = $request->p08;
                $dataInsert['p09'] = $request->p09;
                $dataInsert['p10'] = $request->p10;
                $dataInsert['p11'] = $request->p11;
                $dataInsert['p12'] = $request->p12;
                $dataInsert['created_by'] = Auth::user()->id;
                RencanaKebutuhanTahunan::create($dataInsert);
                return redirect()->route('rencana-kebutuhan-tahunan.index')->with(['success' => 'Rencana kebutuhan tahunan berhasil dibuat !']);
            }else{
                // CREATE BARU
                $dataUpdate['p01'] = $request->p01;
                $dataUpdate['p02'] = $request->p02;
                $dataUpdate['p03'] = $request->p03;
                $dataUpdate['p04'] = $request->p04;
                $dataUpdate['p05'] = $request->p05;
                $dataUpdate['p06'] = $request->p06;
                $dataUpdate['p07'] = $request->p07;
                $dataUpdate['p08'] = $request->p08;
                $dataUpdate['p09'] = $request->p09;
                $dataUpdate["p10"] = $request->p10;
                $dataUpdate['p11'] = $request->p11;
                $dataUpdate['p12'] = $request->p12;
                $dataUpdate['created_by'] = Auth::user()->id;
                RencanaKebutuhanTahunan::where('id',$id)->update($dataUpdate);
                return redirect()->route('rencana-kebutuhan-tahunan.index')->with(['success' => 'Rencana kebutuhan tahunan berhasil diupdate !']);
            }

        } catch (\Throwable $th) {
            return redirect()->route('rencana-kebutuhan-tahunan.index')->with(['failed' => $th->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            RencanaKebutuhanTahunan::destroy($id);
            return redirect()->route('rencana-kebutuhan-tahunan.index')->with(['failed' => 'Data berhasil di hapus !']);
        } catch (\Throwable $th) {
            return redirect()->route('rencana-kebutuhan-tahunan.index')->with(['failed' => $th->getMessage()]);
        }
    }
}
