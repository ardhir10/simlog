<?php

namespace App\Http\Controllers;

use App\KapalNegara;
use Illuminate\Http\Request;

class KapalNegaraController extends Controller
{
    public function index()
    {
        $data['page_title'] = "Data Kapal Negara";
        $data['kapal_negara'] = KapalNegara::orderby('id', 'desc')->get();
        return view('master-data.kapal-negara.index', $data);
    }

    public function create()
    {

        $data['page_title'] = "Tambah Kapal Negara";
        return view('master-data.kapal-negara.create', $data);
    }

    public function edit($id)
    {
        $data['page_title'] = "Edit Kapal Negara";

        $data['data'] = KapalNegara::find($id);

        return view('master-data.kapal-negara.edit', $data);
    }

    public function store(Request $request)
    {

        // --- BAGIAN VALIDASI
        $messages = [
            'nama_kapal.required' => 'Nama Kapal wajib diisi !',
        ];
        $request->validate([
            'nama_kapal' => 'required',
        ], $messages);


        // --- HANDLE PROCESS
        try {
             KapalNegara::create(
                [
                    'nama_kapal' => $request->nama_kapal,
                    'imo_number' => $request->nama_kapal,
                    'call_sign' => $request->call_sign,
                    'mmsi' => $request->mmsi,
                    'keterangan' => $request->keterangan,
                ]
            );
            return redirect()->route('master-data.kapal-negara.index')->with(['success' => 'Data berhasil dibuat !']);
        } catch (\Throwable $th) {
            return redirect()->route('master-data.kapal-negara.index')->with(['failed' => $th->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {

        // --- BAGIAN VALIDASI
        $messages = [
            'nama_kapal.required' => 'Nama Kapal wajib diisi !',
        ];
        $request->validate([
            'nama_kapal' => 'required',
        ], $messages);



        // --- HANDLE PROCESS
        try {
                KapalNegara::where('id', $id)->update(
                    [
                        'nama_kapal' => $request->nama_kapal,
                        'imo_number' => $request->nama_kapal,
                        'call_sign' => $request->call_sign,
                        'mmsi' => $request->mmsi,
                        'keterangan' => $request->keterangan,
                    ]
            );
            return redirect()->route('master-data.kapal-negara.index')->with(['success' => 'Data berhasil diupdate !']);
        } catch (\Throwable $th) {
            return redirect()->route('master-data.kapal-negara.index')->with(['failed' => $th->getMessage()]);
        }
    }

    public function delete($id)
    {

        try {
            KapalNegara::destroy($id);
            return redirect()->route('master-data.kapal-negara.index')->with(['failed' => 'Data berhasil dihapus !']);
        } catch (\Throwable $th) {
            return redirect()->route('master-data.kapal-negara.index')->with(['failed' => $th->getMessage()]);
        }
    }
}
