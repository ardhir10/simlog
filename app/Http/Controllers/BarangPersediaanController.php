<?php

namespace App\Http\Controllers;

use App\BarangMasuk;
use App\BarangPersediaan;
use App\Imports\GeneralImport;
use App\KategoriBarang;
use App\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

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
            $dataInsert['keterangan'] = $dataBarang->keterangan;
            $dataInsert['created_by'] = Auth::user()->id;
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

    public function import(Request $request){

        $timestampID = date('Y-m-d H:i:s');
        if ($request->hasFile('file')) {
            try {
                $rows = Excel::toArray(new GeneralImport(), $request->file('file'));
                $dataBarangImport = [];
                $dataBarangImport2 = [];

                foreach ($rows[0] as $key => $value) {
                    if(is_numeric($value[0])){



                        // UDAH JADI
                        $dataBarang['kode_barang'] = $value[1];

                        if( strpos($value[2], "-") == 0){
                            $namaBarang = substr($value[2], 1);
                            $namaBarang = trim($namaBarang, ' ');
                        }
                        $dataBarang['nama_barang'] = $namaBarang;
                        $dataBarang['tahun_perolehan'] = $value[3];
                        $dataBarang['satuan'] = $value[5];
                        $dataBarang['harga_perolehan'] = $value[6];
                        $dataBarang['kategori'] = $value[13];
                        // // Peruntukkan Apa saja yang ada stocknya
                        $peruntukkan = [];
                        if($value[14]>0){
                            // umum
                            $peruntukkan[] = ['kode_peruntukkan'=>'01','value'=>$value[14]];
                        }
                        if($value[15]>0){
                            // SBNP
                            $peruntukkan[] = ['kode_peruntukkan'=>'08','value'=>$value[15]];
                        }
                        if ($value[16]>0) {
                            // TELKOMPEL
                            $peruntukkan[] = ['kode_peruntukkan'=>'09','value'=>$value[16]];
                        }
                        if ($value[17]>0) {
                            // PENGLA
                            $peruntukkan[] = ['kode_peruntukkan'=>'10','value'=>$value[17]];
                        }
                        if ($value[18]>0) {
                            // KNK
                            $peruntukkan[] = ['kode_peruntukkan'=>'11','value'=>$value[18]];
                        }
                        if ($value[19]>0) {
                            // BEGNKEL
                            $peruntukkan[] = ['kode_peruntukkan'=>'12','value'=>$value[19]];
                        }
                        if ($value[20]>0) {
                            // Sie Kepeg & Umum
                            $peruntukkan[] = ['kode_peruntukkan'=>'02','value'=>$value[20]];
                        }
                        if ($value[21]>0) {
                            // Sie Keuangan
                            $peruntukkan[] = ['kode_peruntukkan'=>'03','value'=>$value[21]];
                        }
                        if ($value[22]>0) {
                            // Sie Pengadaan
                            $peruntukkan[] = ['kode_peruntukkan'=>'04','value'=>$value[22]];
                        }
                        if ($value[23]>0) {
                            // Sie Inventaris
                            $peruntukkan[] = ['kode_peruntukkan'=>'05','value'=>$value[23]];
                        }
                        if ($value[24]>0) {
                            // Sie Sarpras
                            $peruntukkan[] = ['kode_peruntukkan'=>'06','value'=>$value[24]];
                        }
                        if ($value[25]>0) {
                            // Sie Sarpras
                            $peruntukkan[] = ['kode_peruntukkan'=>'07','value'=>$value[25]];
                        }

                        $dataBarang['peruntukkan_available'] = $peruntukkan;

                        // HANYA DI PROSES JIKA MEMILIKI SUB TOTAL UNTUK MENGHINDARI SALAH HITUNG
                        if ($value[12]>0) {
                            $dataBarangImport[] = $value[0];
                            try {
                                DB::beginTransaction();
                                // GENERATE SATUAN
                                $satuanId = Satuan::updateOrCreate(['nama_satuan' => $dataBarang['satuan']], ['nama_satuan' => $dataBarang['satuan']]);
                                $satuanId = $satuanId->id;

                                // GENERATE KATEGORI
                                $kategoriId = KategoriBarang::updateOrCreate(['nama_kategori' => $dataBarang['kategori']], ['nama_kategori' => $dataBarang['kategori']]);
                                $kategoriId = $kategoriId->id;

                                // LOOP BERDASARKAN PERUNTUKKAN
                                foreach ($peruntukkan as $key => $value2) {
                                    $BarangPersediaan = BarangPersediaan::where('nama_barang', $namaBarang)
                                        ->where('sub_sub_kategori', $value2['kode_peruntukkan'])
                                        ->where('harga_perolehan', $dataBarang['harga_perolehan'])
                                        ->where('kode_barang', $dataBarang['kode_barang'])
                                        ->first();
                                    if ($BarangPersediaan) {
                                        $BarangPersediaanId = $BarangPersediaan;
                                    } else {
                                        $dataBarangImport2[] = $value[0];

                                        // Buat Barangnya
                                        $createBarang['sumber_barang'] = 'existing';
                                        $createBarang['kategori_barang_id'] = $kategoriId;
                                        $createBarang['nama_barang'] = $namaBarang;
                                        $createBarang['kode_barang'] = $dataBarang['kode_barang'];
                                        $createBarang['tahun_perolehan'] = $dataBarang['tahun_perolehan'];
                                        $createBarang['jumlah'] = 0;
                                        $createBarang['satuan_id'] = $satuanId;
                                        $createBarang['harga_perolehan'] = $dataBarang['harga_perolehan'];
                                        $createBarang['mata_uang'] = 'IDR';
                                        $createBarang['masa_simpan'] = 0;
                                        $createBarang['jumlah_stok_minimal'] = 0;
                                        $createBarang['spesifikasi_barang'] = '';
                                        $createBarang['foto_barang'] = '';
                                        $createBarang['created_by_id'] = Auth::user()->id;
                                        $createBarang['created_by_name'] = Auth::user()->name;
                                        $createBarang['sub_sub_kategori'] = $value2['kode_peruntukkan'];
                                        $createBarang['from'] = 'file';
                                        $BarangPersediaanId = BarangPersediaan::create($createBarang);
                                    }

                                    // Masukkan Stock
                                    BarangMasuk::create([
                                        'timestamp' => $timestampID,
                                        'barang_id' => $BarangPersediaanId->id,
                                        'permintaan_id' => 0,
                                        'harga_perolehan' => $dataBarang['harga_perolehan'],
                                        'tahun_perolehan' => $dataBarang['tahun_perolehan'],
                                        'jumlah' => $value2['value'],
                                        'sub_sub_kategori' =>  $value2['kode_peruntukkan'],
                                    ]);
                                }
                                DB::commit();
                            } catch (\Throwable $th) {
                                dd($value);
                            }
                        }



                    }
                }
                // $dataBarangImport = array_filter($dataBarangImport,function($q){
                //     if($q > 0 ){
                //         return $q;
                //     }
                // });

                // dd($dataBarangImport, $dataBarangImport2, array_diff($dataBarangImport,$dataBarangImport2));
                return redirect()->route('barang-persediaan.index')->with(['success' => 'Import data berhasil !']);

            } catch (\Throwable $th) {
                dd($th);
                return back()->with(['failed' => $th->getMessage()]);
            }
        }

        return back()->with(['failed' => 'Please Check your file, Something is wrong there.']);
    }
}

