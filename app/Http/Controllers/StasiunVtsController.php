<?php

namespace App\Http\Controllers;

use App\StasiunVts;
use Illuminate\Http\Request;

class StasiunVtsController extends Controller
{
    public function index()
    {
        $data['page_title'] = "Data Stasiun VTS";
        $data['stasiun_vts'] = StasiunVts::orderby('id', 'desc')->get();
        return view('master-data.stasiun-vts.index', $data);
    }

    public function create()
    {

        $data['page_title'] = "Tambah Stasiun VTS";
        return view('master-data.stasiun-vts.create', $data);
    }

    public function edit($id)
    {
        $data['page_title'] = "Edit Kapal Negara";

        $data['data'] = StasiunVts::find($id);

        return view('master-data.stasiun-vts.edit', $data);
    }

    public function store(Request $request)
    {

        // --- BAGIAN VALIDASI
        $messages = [
            'nama_stasiun_vts.required' => 'Nama Stasiun VTS wajib diisi !',
        ];
        $request->validate([
            'nama_stasiun_vts' => 'required',
        ], $messages);


        // --- HANDLE PROCESS
        try {
            StasiunVts::create(
                [
                    'nama_stasiun_vts' => $request->nama_stasiun_vts,
                    'alamat_stasiun' => $request->alamat_stasiun,
                    'keterangan' => $request->keterangan,
                ]
            );
            return redirect()->route('master-data.stasiun-vts.index')->with(['success' => 'Data berhasil dibuat !']);
        } catch (\Throwable $th) {
            return redirect()->route('master-data.stasiun-vts.index')->with(['failed' => $th->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {

        // --- BAGIAN VALIDASI
        $messages = [
            'nama_stasiun_vts.required' => 'Nama Stasiun VTS wajib diisi !',
        ];
        $request->validate([
            'nama_stasiun_vts' => 'required',
        ], $messages);




        // --- HANDLE PROCESS
        try {
            StasiunVts::where('id', $id)->update(
                [
                    'nama_stasiun_vts' => $request->nama_stasiun_vts,
                    'alamat_stasiun' => $request->alamat_stasiun,
                    'keterangan' => $request->keterangan,
                ]
            );
            return redirect()->route('master-data.stasiun-vts.index')->with(['success' => 'Data berhasil diupdate !']);
        } catch (\Throwable $th) {
            return redirect()->route('master-data.stasiun-vts.index')->with(['failed' => $th->getMessage()]);
        }
    }

    public function delete($id)
    {

        try {
            StasiunVts::destroy($id);
            return redirect()->route('master-data.stasiun-vts.index')->with(['failed' => 'Data berhasil dihapus !']);
        } catch (\Throwable $th) {
            return redirect()->route('master-data.stasiun-vts.index')->with(['failed' => $th->getMessage()]);
        }
    }
}
