<?php

namespace App\Http\Controllers;

use App\ApprovalRencanaKebutuhanProcess;
use App\BarangPersediaan;
use App\RencanaKebutuhan;
use App\RencanaKebutuhanDetail;
use App\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RencanaKebutuhanController extends Controller
{
    public function index(){
        $data['page_title'] = 'Rencana Kebutuhan';
        $data['data'] = RencanaKebutuhan::get();
        return view('rencana-kebutuhan.index',$data);
    }

    public function create()
    {
        $data['page_title'] = 'Rencana Kebutuhan';
        $data['satuan'] = Satuan::orderBy('id', 'desc')->get();
        $dataRk = RencanaKebutuhan::where('created_by', Auth::user()->id)
        ->where('is_draft', true)
        ->first();
        // if ($dataRk) {
        //     $data['data'] = $dataRk;
        //     $data['barang_persediaan'] = BarangPersediaan::where('sub_sub_kategori',$dataRk->pengguna)->orderBy('id', 'desc')->get();
        // } else {
        //     $data['data'] = null;
        //     $data['barang_persediaan'] = BarangPersediaan::orderBy('id', 'desc')->get();
        // }

            $data['data'] = $dataRk;
        $data['barang_persediaan'] = BarangPersediaan::orderBy('id', 'desc')->get();

        $data['rk_details'] = RencanaKebutuhanDetail::where('rencana_kebutuhan_id', $dataRk->id ?? null)->get();
        $data['rk'] = RencanaKebutuhan::orderBy('id', 'desc')->get();
        return view('rencana-kebutuhan.create', $data);
    }
    public function getBarangPersediaan($id)
    {
        $data = BarangPersediaan::whereId($id)->with('satuan')->first();
        return response()->json($data);
    }
    public function store(Request $request, $id = null)
    {
        if ($id == null) {
            try {
                if ($request->submit == 'AJUKAN') {
                } else {
                    // Saat Klik Pilih Item
                    $dataRk['kegiatan'] = $request->kegiatan;
                    $dataRk['pengguna'] = $request->pengguna;
                    $dataRk['is_draft'] = true;
                    $dataRk['created_by'] = Auth::user()->id;
                    $dataRk['created_by_name'] = Auth::user()->name;
                    $dataRk['timestamp'] = date('Y-m-d H:i:s');
                    $dataRk['status'] = 'draft';
                    $dataRk['nomor_rk'] = $this->generateNomorRk($request->pengguna);
                    RencanaKebutuhan::create($dataRk);
                    return redirect()->route('rencana-kebutuhan.create')->with(['success' => 'Rencana Kebutuhan di buat !']);
                }
            } catch (\Throwable $th) {
                return redirect()->route('rencana-kebutuhan.create')->with(['failed' => $th->getMessage()]);
            }
        } else {
            try {
                if ($request->submit == 'AJUKAN') {
                    DB::beginTransaction();
                    RencanaKebutuhan::whereId($id)->update([
                        'is_draft' => false,
                        'status' => 'Diproses',
                    ]);


                    // Buat Approval Persetujuan RK
                    $dataRk = RencanaKebutuhan::where('id', $id)->first();
                    // --- APPROVAL PROCESS 1
                    $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                    $dataApproval['rencana_kebutuhan_id'] = $id;
                    $dataApproval['user_peminta_id'] = $dataRk->created_by;
                    $dataApproval['user_peminta_name'] = $dataRk->user->name ?? '';

                    $dataApproval['role_to_name'] = 'Kepala Distrik Navigasi';

                    $dataApproval['type'] = 'Dalam Proses Approval';
                    $dataApproval['status'] = '';

                    $dataApproval['step'] = 1;
                    $dataApproval['keterangan'] = '';
                    $dataApproval['kategori'] = 'APPROVAL';

                    ApprovalRencanaKebutuhanProcess::create($dataApproval);


                    DB::commit();
                    return redirect()->route('rencana-kebutuhan.index')->with(['success' => 'Rencana Kebutuhan di Ajukan !']);
                }
            } catch (\Throwable $th) {
                DB::rollback();
                return redirect()->route('rencana-kebutuhan.create')->with(['failed' => $th->getMessage()]);
            }
        }
    }

    public function detail($id)
    {
        $data['page_title'] = 'Rencana Kebutuhan';
        $data['data'] = RencanaKebutuhan::find($id);

        return view('rencana-kebutuhan.detail', $data);
    }

    public function inputItem(Request $request, $id)
    {
        $cariBarang = RencanaKebutuhanDetail::where('rencana_kebutuhan_id', $id)
            ->where('nama_barang', $request->nama_barang)
            ->where('satuan', $request->satuan)
            ->where('pengguna', $request->pengguna)
            ->where('harga_satuan', $request->harga_satuan)
            ->where('mata_uang', $request->mata_uang)
            ->first();
        if ($cariBarang) {
            $data['rencana_kebutuhan_id'] = $id;
            $data['nama_barang'] = $request->nama_barang;
            $data['satuan'] = $request->satuan;
            $data['qty'] = $request->qty;
            $data['harga_satuan']
            = $request->harga_satuan;
            $data['mata_uang']
            = $request->mata_uang;
            $data['keterangan']
            = $request->keterangan;
            $data['add_by'] = Auth::user()->id;

            RencanaKebutuhanDetail::where('rencana_kebutuhan_id', $id)->update($data);
            return redirect()->route('rencana-kebutuhan.create')->with(['success' => 'Item Diupdate !']);
        } else {
            $data['rencana_kebutuhan_id'] = $id;
            // $data['barang_id'] = $request->barang_id;
            $data['nama_barang'] = $request->nama_barang;
            $data['satuan'] = $request->satuan;
            $data['qty'] = $request->qty;
            $data['pengguna'] = $request->pengguna;
            $data['harga_satuan']
            = $request->harga_satuan;
            $data['mata_uang']
            = $request->mata_uang;
            $data['keterangan']
            = $request->keterangan;
            $data['add_by'] = Auth::user()->id;

            RencanaKebutuhanDetail::create($data);
            return redirect()->route('rencana-kebutuhan.create')->with(['success' => 'Item Dimasukkan !']);
        }
    }

    public function deleteItem($id)
    {
        RencanaKebutuhanDetail::where('id', $id)->delete();
        return redirect()->route('rencana-kebutuhan.create')->with(['success' => 'Item Dihapus !']);
    }

    public function batalkan($id)
    {
        try {
            DB::beginTransaction();
            RencanaKebutuhanDetail::where('rencana_kebutuhan_id', $id)->delete();
            RencanaKebutuhan::whereId($id)->delete();
            DB::commit();
            return redirect()->route('rencana-kebutuhan.create')->with(['success' => 'Rencana Kebutuhan Dibatalkan !']);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('rencana-kebutuhan.create')->with(['failed' => 'Gagal dibatalkan !']);
        }
    }


    private function generateNomorRk($pengguna)
    {
        $roleName = Auth::user()->role->name;

        $year_now = date('Y');
        $month_now = date('m');
        $obj = DB::table('rencana_kebutuhan')
        ->select('nomor_rk')
        ->latest('id')
            ->where('nomor_rk', 'ilike', '%' . $year_now . '%')
            ->first();

        if ($obj) {

            $increment = explode('/', $obj->nomor_rk);
            $removed1char = substr($increment[0], 1);


            $generateOrder_nr =
                str_pad($removed1char + 1, 3, "0", STR_PAD_LEFT) . '/RK/' . $this->pengguna($pengguna) . '/' . $this->getRomawi($month_now) . '-' . $year_now;
        } else {
            $generateOrder_nr = '001/RK/'.$this->pengguna($pengguna). '/' . $this->getRomawi($month_now) . '-' . $year_now;
        }
        return $generateOrder_nr;
    }
    private function getRomawi($bln)
    {
        switch ($bln) {
            case 1:
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }

    public function pengguna($pengguna)
    {

        // PENGGUNANYA
        switch ($pengguna) {
            case '01':
                return 'Umum';
                break;
            case '02':
                return 'Sie Kepeg & Umum';
                break;
            case '03':
                return 'Sie Keuangan';
                break;
            case '04':
                return 'Sie Pengadaan';
                break;
            case '05':
                return 'Sie Inventaris';
                break;
            case '06':
                return 'SieSarPras';
                break;
            case '07':
                return 'Sie Program & Evaluasi';
                break;
            case '08':
                return 'SBNP';
                break;
            case '09':
                return 'Telkompel';
                break;
            case '10':
                return 'Pengla';
                break;
            case '11':
                return 'KNK';
                break;
            case '12':
                return 'Bengkel';
                break;
            default:
                return '';
                break;
        }
        return $this->pengguna;
    }
}
