<?php

namespace App\Http\Controllers;

use App\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        $data['page_title'] = "Data Satuan";
        $data['satuan'] = Satuan::orderby('id', 'desc')->get();
        return view('master-data.satuan.index', $data);
    }

    public function create()
    {

        $data['page_title'] = "Tambah Satuan";
        return view('master-data.satuan.create', $data);
    }

    public function edit($id)
    {
        $data['page_title'] = "Edit Satuan";

        $data['data'] = Satuan::find($id);

        return view('master-data.satuan.edit', $data);
    }

    public function store(Request $request)
    {

        // --- BAGIAN VALIDASI
        $messages = [
            'nama_satuan.required' => 'Nama Satuan wajib diisi !',
        ];
        $request->validate([
            'nama_satuan' => 'required',
        ], $messages);


        // --- HANDLE PROCESS
        try {
            Satuan::create(
                [
                    'nama_satuan' => $request->nama_satuan,
                    'keterangan' => $request->keterangan,
                ]
            );
            return redirect()->route('master-data.satuan.index')->with(['success' => 'Data berhasil dibuat !']);
        } catch (\Throwable $th) {
            return redirect()->route('master-data.satuan.index')->with(['failed' => $th->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {

        // --- BAGIAN VALIDASI
        $messages = [
            'nama_satuan.required' => 'Nama Satuan wajib diisi !',
        ];
        $request->validate([
            'nama_satuan' => 'required',
        ], $messages);



        // --- HANDLE PROCESS
        try {
            Satuan::where('id', $id)->update(
                [
                    'nama_satuan' => $request->nama_satuan,
                    'keterangan' => $request->keterangan,
                ]
            );
            return redirect()->route('master-data.satuan.index')->with(['success' => 'Data berhasil diupdate !']);
        } catch (\Throwable $th) {
            return redirect()->route('master-data.satuan.index')->with(['failed' => $th->getMessage()]);
        }
    }

    public function delete($id)
    {

        try {
            Satuan::destroy($id);
            return redirect()->route('master-data.satuan.index')->with(['failed' => 'Data berhasil dihapus !']);
        } catch (\Throwable $th) {
            return redirect()->route('master-data.satuan.index')->with(['failed' => $th->getMessage()]);
        }
    }
}
