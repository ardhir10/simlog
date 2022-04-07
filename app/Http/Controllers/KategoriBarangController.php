<?php

namespace App\Http\Controllers;

use App\KategoriBarang;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    public function index()
    {
        $data['page_title'] = "Data Kategori Barang";
        $data['kategori_barang'] = KategoriBarang::orderby('id', 'desc')->get();
        return view('master-data.kategori-barang.index', $data);
    }

    public function create()
    {

        $data['page_title'] = "Tambah Kategori Barang";
        return view('master-data.kategori-barang.create', $data);
    }

    public function edit($id)
    {
        $data['page_title'] = "Edit Kategori Barang";

        $data['data'] = KategoriBarang::find($id);

        return view('master-data.kategori-barang.edit', $data);
    }

    public function store(Request $request)
    {

        // --- BAGIAN VALIDASI
        $messages = [
            'nama_kategori.required' => 'Nama Kategori wajib diisi !',
            'kode_kategori.required' => 'Kode Kategori wajib diisi !',
        ];
        $request->validate([
            'nama_kategori' => 'required',
            'kode_kategori' => 'required',
        ], $messages);


        // --- HANDLE PROCESS
        try {
            KategoriBarang::create(
                [
                    'nama_kategori' => $request->nama_kategori,
                    'kode_kategori' => $request->kode_kategori,
                    'keterangan' => $request->keterangan,
                ]
            );
            return redirect()->route('master-data.kategori-barang.index')->with(['success' => 'Data berhasil dibuat !']);
        } catch (\Throwable $th) {
            return redirect()->route('master-data.kategori-barang.index')->with(['failed' => $th->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {

        // --- BAGIAN VALIDASI
        $messages = [
            'nama_kategori.required' => 'Nama Kategori wajib diisi !',
            'kode_kategori.required' => 'Kode Kategori wajib diisi !',
        ];
        $request->validate([
            'nama_kategori' => 'required',
            'kode_kategori' => 'required',
        ], $messages);



        // --- HANDLE PROCESS
        try {
            KategoriBarang::where('id', $id)->update(
                [
                    'nama_kategori' => $request->nama_kategori,
                    'kode_kategori' => $request->kode_kategori,
                    'keterangan' => $request->keterangan,
                ]
            );
            return redirect()->route('master-data.kategori-barang.index')->with(['success' => 'Data berhasil diupdate !']);
        } catch (\Throwable $th) {
            return redirect()->route('master-data.kategori-barang.index')->with(['failed' => $th->getMessage()]);
        }
    }

    public function delete($id)
    {

        try {
            KategoriBarang::destroy($id);
            return redirect()->route('master-data.kategori-barang.index')->with(['failed' => 'Data berhasil dihapus !']);
        } catch (\Throwable $th) {
            return redirect()->route('master-data.kategori-barang.index')->with(['failed' => $th->getMessage()]);
        }
    }
}
