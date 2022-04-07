<?php

namespace App\Http\Controllers;

use App\StasiunRadioPantai;
use Illuminate\Http\Request;

class StasiunRadioPantaiController extends Controller
{
    public function index()
    {
        $data['page_title'] = "Data Stasiun Radio Pantai";
        $data['stasiun_srop'] = StasiunRadioPantai::orderby('id', 'desc')->get();
        return view('master-data.stasiun-radio-pantai.index', $data);
    }

    public function create()
    {

        $data['page_title'] = "Tambah Stasiun Radio Pantai";
        return view('master-data.stasiun-radio-pantai.create', $data);
    }

    public function edit($id)
    {
        $data['page_title'] = "Edit Stasiun Radio Pantain";

        $data['data'] = StasiunRadioPantai::find($id);

        return view('master-data.stasiun-radio-pantai.edit', $data);
    }

    public function store(Request $request)
    {

        // --- BAGIAN VALIDASI
        $messages = [
            'nama_srop.required' => 'Nama Stasiun Radio Pantai wajib diisi !',
        ];
        $request->validate([
            'nama_srop' => 'required',
        ], $messages);


        // --- HANDLE PROCESS
        try {
            StasiunRadioPantai::create(
                [
                    'nama_srop' => $request->nama_srop,
                    'alamat_stasiun' => $request->alamat_stasiun,
                    'keterangan' => $request->keterangan,
                ]
            );
            return redirect()->route('master-data.stasiun-radio-pantai.index')->with(['success' => 'Data berhasil dibuat !']);
        } catch (\Throwable $th) {
            return redirect()->route('master-data.stasiun-radio-pantai.index')->with(['failed' => $th->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {

        // --- BAGIAN VALIDASI
        $messages = [
            'nama_srop.required' => 'Nama Stasiun Radio Pantai wajib diisi !',
        ];
        $request->validate([
            'nama_srop' => 'required',
        ], $messages);




        // --- HANDLE PROCESS
        try {
            StasiunRadioPantai::where('id', $id)->update(
                [
                    'nama_srop' => $request->nama_srop,
                    'alamat_stasiun' => $request->alamat_stasiun,
                    'keterangan' => $request->keterangan,
                ]
            );
            return redirect()->route('master-data.stasiun-radio-pantai.index')->with(['success' => 'Data berhasil diupdate !']);
        } catch (\Throwable $th) {
            return redirect()->route('master-data.stasiun-radio-pantai.index')->with(['failed' => $th->getMessage()]);
        }
    }

    public function delete($id)
    {

        try {
            StasiunRadioPantai::destroy($id);
            return redirect()->route('master-data.stasiun-radio-pantai.index')->with(['failed' => 'Data berhasil dihapus !']);
        } catch (\Throwable $th) {
            return redirect()->route('master-data.stasiun-radio-pantai.index')->with(['failed' => $th->getMessage()]);
        }
    }
}
