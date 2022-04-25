<?php

namespace App\Http\Controllers;

use App\BarangMasuk;
use App\BarangPersediaan;
use App\KategoriBarang;
use App\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class BarangPersediaanController extends Controller
{
    public function index()
    {
        $data['page_title'] = "Data Barang Persediaan";
        $data['barang_persediaan'] = BarangPersediaan::orderby('id', 'desc')->get();
        return view('barang-persediaan.index', $data);
    }

    public function stockDetail($id)
    {
        $data['data'] = BarangPersediaan::find($id);
        $data['page_title'] = " (". $data['data']->nama_barang.')';

        return view('barang-persediaan.stock-detail', $data);
    }

    public function stockMasuk(Request $request,$id){
        $dataBarang = BarangPersediaan::find($id);
        try {
            $dataInsert['barang_id'] = $id;
            $dataInsert['timestamp'] = $request->timestamp.' '.date("H:i:s");
            $dataInsert['jumlah'] = $request->jumlah;
            $dataInsert['permintaan_id'] = 0;
            $dataInsert['harga_perolehan'] = $dataBarang->harga_perolehan;
            $dataInsert['harga_perolehan'] = $dataBarang->harga_perolehan;
            $dataInsert['tahun_perolehan'] = $dataBarang->tahun_perolehan;
            $dataInsert['sub_sub_kategori'] = $dataBarang->sub_sub_kategori;
            BarangMasuk::create($dataInsert);
            return redirect()->back()->with(['success' => 'Stok Berhasil ditambahkan !']);
        } catch (\Throwable $th) {
            return redirect()->back()->with(['failed' => $th->getMessage()]);
        }
    }

    public function create(Request $request)
    {


        if ($request->id) {
            $data['page_title'] = "Tambah Barang Persediaan";
            $data['kategori_barang'] = KategoriBarang::orderby('id', 'desc')->get();
            $data['satuan'] = Satuan::orderby('id', 'desc')->get();
            $data['data'] = BarangPersediaan::find($request->id);
            $data['kategori_barang'] = KategoriBarang::orderby('id', 'desc')->get();

            return view('barang-persediaan.create-diff', $data);
        }else{
            $data['page_title'] = "Tambah Barang Persediaan";
            $data['kategori_barang'] = KategoriBarang::orderby('id', 'desc')->get();
            $data['satuan'] = Satuan::orderby('id', 'desc')->get();

            return view('barang-persediaan.create', $data);
        }

    }

    public function edit($id)
    {
        $data['page_title'] = "Edit Barang Persediaan";

        $data['data'] = BarangPersediaan::find($id);
                $data['kategori_barang'] = KategoriBarang::orderby('id', 'desc')->get();
        $data['satuan'] = Satuan::orderby('id', 'desc')->get();


        return view('barang-persediaan.edit', $data);
    }

    public function store(Request $request)
    {

        $dataInsert['sumber_barang'] = $request->sumber_barang;
        $dataInsert['kategori_barang_id'] = $request->kategori_barang_id;
        $dataInsert['nama_barang'] = $request->nama_barang;
        $dataInsert['kode_barang'] = $request->kode_barang;
        $dataInsert['sub_sub_kategori'] = $request->sub_sub_kategori;




        $dataInsert['tahun_perolehan'] = $request->tahun_perolehan;
        $dataInsert['jumlah'] = $request->jumlah;
        $dataInsert['satuan_id'] = $request->satuan_id;
        $dataInsert['harga_perolehan'] = $request->harga_perolehan;
        $dataInsert['mata_uang'] = $request->mata_uang;
        $dataInsert['masa_simpan'] = $request->masa_simpan;
        $dataInsert['jumlah_stok_minimal'] = $request->jumlah_stok_minimal;
        $dataInsert['spesifikasi_barang'] = $request->spesifikasi_barang;
        $uploadFotoBarang = $this->uploadFile($request, 'foto_barang', $request->kode_barang, 'dokumen_barang/foto_barang/');
        $dataInsert['foto_barang'] = $uploadFotoBarang;


        // IF NEW
        $dataInsert['nomor_bast'] = $request->nomor_bast;
        $uploadDokumenBast = $this->uploadFile($request, 'dokumen_bast', $request->kode_barang, 'dokumen_barang/dokumen_bast/');
        $dataInsert['dokumen_bast'] = $uploadDokumenBast;



        $dataInsert['created_by_id'] = Auth::user()->id;
        $dataInsert['created_by_name'] = Auth::user()->name;




        // --- HANDLE PROCESS
        try {
            DB::beginTransaction();
            $barang = BarangPersediaan::create(
                $dataInsert
            );
            BarangMasuk::create([
                'timestamp' => date('Y-m-d H:i:s'),
                'barang_id' => $barang->id,
                'permintaan_id' => 0,
                'harga_perolehan' => $dataInsert['harga_perolehan'],
                'jumlah' => $dataInsert['jumlah'] = $request->jumlah,
                'tahun_perolehan' => $dataInsert['tahun_perolehan'],
                'sub_sub_kategori' => $dataInsert['sub_sub_kategori'],
            ]);
            DB::commit();

            return redirect()->route('barang-persediaan.index')->with(['success' => 'Data berhasil dibuat !']);
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->route('barang-persediaan.index')->with(['failed' => $th->getMessage()]);
        }
    }

    public function createDiff(Request $request,$id){
        $dataBP = BarangPersediaan::find($id);

        if ($dataBP->harga_perolehan == $request->harga_perolehan) {
            return redirect()->route('barang-persediaan.create',['id'=>$id])->with(['failed' => 'Harga Barang Tidak Boleh Sama !']);
        }

        $dataInsert['sumber_barang'] = $dataBP->sumber_barang;
        $dataInsert['kategori_barang_id'] = $dataBP->kategori_barang_id;
        $dataInsert['nama_barang'] = $dataBP->nama_barang;
        $dataInsert['kode_barang'] = $dataBP->kode_barang;


        $dataInsert['satuan_id'] = $dataBP->satuan_id;
        $dataInsert['masa_simpan'] = $dataBP->masa_simpan;
        $dataInsert['jumlah_stok_minimal'] = $dataBP->jumlah_stok_minimal;
        $dataInsert['spesifikasi_barang'] = $dataBP->spesifikasi_barang;
        $dataInsert['foto_barang'] = $dataBP->foto_barang;


        // IF NEW
        $dataInsert['nomor_bast'] = $dataBP->nomor_bast;
        $dataInsert['dokumen_bast'] = $dataBP->dokumen_bast;



        $dataInsert['created_by_id'] = Auth::user()->id;
        $dataInsert['created_by_name'] = Auth::user()->name;



        $dataInsert['sub_sub_kategori'] = $request->sub_sub_kategori;
        $dataInsert['tahun_perolehan'] = $request->tahun_perolehan;
        $dataInsert['jumlah'] = $request->jumlah;
        $dataInsert['harga_perolehan'] = $request->harga_perolehan;
        $dataInsert['mata_uang'] = $request->mata_uang;


        // --- HANDLE PROCESS
        try {
            DB::beginTransaction();
            $barang = BarangPersediaan::create(
                $dataInsert
            );
            BarangMasuk::create([
                'timestamp' => date('Y-m-d H:i:s'),
                'barang_id' => $barang->id,
                'permintaan_id' => 0,
                'harga_perolehan' => $dataInsert['harga_perolehan'],
                'jumlah' => $dataInsert['jumlah'] = $request->jumlah,
                'tahun_perolehan' => $dataInsert['tahun_perolehan'],
                'sub_sub_kategori' => $dataInsert['sub_sub_kategori'],
            ]);
            DB::commit();

            return redirect()->route('barang-persediaan.index')->with(['success' => 'Data berhasil dibuat !']);
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->route('barang-persediaan.index')->with(['failed' => $th->getMessage()]);
        }

    }

    public function update(Request $request, $id)
    {

        $BarangPersediaan = BarangPersediaan::find($id);

        $dataInsert['sumber_barang'] = $request->sumber_barang;
        $dataInsert['kategori_barang_id'] = $request->kategori_barang_id;
        $dataInsert['nama_barang'] = $request->nama_barang;
        $dataInsert['kode_barang'] = $request->kode_barang;
        $dataInsert['sub_sub_kategori'] = $request->sub_sub_kategori;




        $dataInsert['tahun_perolehan'] = $request->tahun_perolehan;
        $dataInsert['jumlah'] = $request->jumlah;
        $dataInsert['satuan_id'] = $request->satuan_id;
        $dataInsert['harga_perolehan'] = $request->harga_perolehan;
        $dataInsert['mata_uang'] = $request->mata_uang;
        $dataInsert['masa_simpan'] = $request->masa_simpan;
        $dataInsert['jumlah_stok_minimal'] = $request->jumlah_stok_minimal;
        $dataInsert['spesifikasi_barang'] = $request->spesifikasi_barang;
        $uploadFotoBarang = $this->uploadFile($request, 'foto_barang', $request->kode_barang, 'dokumen_barang/foto_barang/',$BarangPersediaan->foto_barang);
        if ($uploadFotoBarang != false) {
            $dataInsert['foto_barang'] = $uploadFotoBarang;
        }


        // IF NEW
        $dataInsert['nomor_bast'] = $request->nomor_bast;
        $uploadDokumenBast = $this->uploadFile($request, 'dokumen_bast', $request->kode_barang, 'dokumen_barang/dokumen_bast/',$BarangPersediaan->dokumen_bast);
        if($uploadDokumenBast !=false){
            $dataInsert['dokumen_bast'] = $uploadDokumenBast;
        }



        $dataInsert['updated_by_name'] = Auth::user()->name;
        $dataInsert['updated_by_id'] = Auth::user()->id;



        // --- HANDLE PROCESS
        try {
            BarangPersediaan::where('id',$id)->update(
                $dataInsert
            );
            return redirect()->route('barang-persediaan.index')->with(['success' => 'Data berhasil dibuat !']);
        } catch (\Throwable $th) {
            return redirect()->route('barang-persediaan.index')->with(['failed' => $th->getMessage()]);
        }
    }

    public function delete($id)
    {

        try {
            $BarangPersediaan = BarangPersediaan::find($id);
            BarangPersediaan::destroy($id);

            // if ($BarangPersediaan->foto_barang) {
            //     $picture_path = public_path('dokumen_barang/foto_barang/' . $BarangPersediaan->foto_barang);
            //     if (File::exists($picture_path)) {
            //         File::delete($picture_path);
            //     }
            // }
            // if ($BarangPersediaan->dokumen_bast) {
            //     $picture_path = public_path('dokumen_barang/dokumen_bast/' . $BarangPersediaan->dokumen_bast);
            //     if (File::exists($picture_path)) {
            //         File::delete($picture_path);
            //     }
            // }
            return redirect()->route('barang-persediaan.index')->with(['failed' => 'Data berhasil di hapus !']);
        } catch (\Throwable $th) {
            return redirect()->route('barang-persediaan.index')->with(['failed' => $th->getMessage()]);
        }
    }

    private function uploadFile($request,$rname,$prefix='img',$path = 'images/',$isDeleteFile =false){
        if ($request->hasFile($rname)) {

            if ($isDeleteFile) {
                $picture_path = public_path($path . $isDeleteFile);
                if (File::exists($picture_path)) {
                    File::delete($picture_path);
                }
            }
            $file = $request->file($rname);
            $name = $prefix . '_' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path($path);
            $file->move($destinationPath, $name);
            return $fileName = $name;
        }
        return false;
    }
}

