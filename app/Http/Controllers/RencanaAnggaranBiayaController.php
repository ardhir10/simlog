<?php

namespace App\Http\Controllers;

use App\BarangPersediaan;
use App\RAB;
use App\RabDetail;
use App\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RencanaAnggaranBiayaController extends Controller
{
    public function index(){
        $data['page_title'] = 'Rencana Anggaran Biaya';

        $data['rab'] = RAB::orderBy('id','desc')->get();
        return view('rab.index',$data);
    }

    public function create()
    {
        $data['page_title'] = 'Rencana Anggaran Biaya';
        $data['satuan'] = Satuan::orderBy('id','desc')->get();
        $data['barang_persediaan'] = BarangPersediaan::orderBy('id','desc')->get();
        $dataRab = RAB::where('created_by',Auth::user()->id)
        ->where('is_draft',true)
        ->first();
        if($dataRab){
            $data['data'] = $dataRab;
        }else{
            $data['data'] = null;
        }


        $data['rab_details'] = RabDetail::where('rab_id',$dataRab->id ??null)->get();
        $data['rab'] = RAB::orderBy('id', 'desc')->get();
        return view('rab.create', $data);
    }

    public function store(Request $request,$id=null){
        if($id == null){
            try {
                if($request->submit == 'AJUKAN'){
                    RAB::whereId($id)->update(['is_draft'=>false]);

                    return redirect()->route('rab.index')->with(['success' => 'Rab di Ajukan !']);
                }else{
                    // Saat Klik Pilih Item
                    $dataRab['kegiatan'] = $request->kegiatan;
                    $dataRab['mak'] = $request->mak;
                    $dataRab['pengguna'] = $request->pengguna;
                    $dataRab['tahun_anggaran'] = $request->tahun_anggaran;
                    $dataRab['is_draft'] = true;
                    $dataRab['created_by'] = Auth::user()->id;
                    $dataRab['created_by_name'] = Auth::user()->name;
                    $dataRab['timestamp'] = date('Y-m-d H:i:s');
                    $dataRab['status'] = 'draft';
                    $dataRab['nomor_rab'] = $this->generateNomorRab();
                    RAB::create($dataRab);
                    return redirect()->route('rab.create')->with(['success' => 'Rab di buat !']);
                }

            } catch (\Throwable $th) {
                return redirect()->route('rab.create')->with(['failed' => $th->getMessage()]);
            }


        }else{
            try {
                if ($request->submit == 'AJUKAN') {
                    RAB::whereId($id)->update(['is_draft'=> false]);


                    // Buat Approval Persetujuan RAB
                    $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                    $dataApproval['rab_id'] = $id;
                    $dataApproval['user_id'] = Auth::user()->id;
                    $dataApproval['user_name'] = Auth::user()->name;
                    $dataApproval['rolte_to_name'] = 'Kasie Pengadaan';
                    $dataApproval['type'] = 'Menunggu Persetujuan Kasie Pengadaan';
                    $dataApproval['status'] = '';
                    $dataApproval['keterangan'] = $request->keterangan;
                    $dataApproval['tindak_lanjut'] = '';
                    $dataApproval['approve_by_id'] = Auth::user()->id;
                    $dataApproval['kategori'] = "APPROVAL";
                    
                    return redirect()->route('rab.index')->with(['success' => 'Rab di Ajukan !']);
                }
            } catch (\Throwable $th) {
                return redirect()->route('rab.create')->with(['failed' => $th->getMessage()]);
            }

        }
    }


    public function getBarangPersediaan($id){
        $data = BarangPersediaan::whereId($id)->with('satuan')->first();
        return response()->json($data);
    }

    public function inputItem(Request $request,$id){
        $cariBarang = RabDetail::where('rab_id',$id)
            ->where('nama_barang',$request->nama_barang)
            ->where('satuan',$request->satuan)
            ->where('harga_satuan',$request->harga_satuan)
            ->where('mata_uang',$request->mata_uang)
            ->first();
        if($cariBarang){
            $data['rab_id'] = $id;
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

            RabDetail::where('rab_id',$id)->update($data);
            return redirect()->route('rab.create')->with(['success' => 'Item Diupdate !']);

        }else{
            $data['rab_id'] = $id;
            // $data['barang_id'] = $request->barang_id;
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

            RabDetail::create($data);
            return redirect()->route('rab.create')->with(['success' => 'Item Dimasukkan !']);
        }

    }

    public function batalkan($id){
        try {
            DB::beginTransaction();
            RabDetail::where('rab_id',$id)->delete();
            RAB::whereId($id)->delete();
            DB::commit();
            return redirect()->route('rab.create')->with(['success' => 'RAB Dibatalkan !']);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->route('rab.create')->with(['failed' => 'Gagal dibatalkan !']);
        }
    }


    public function deleteItem($id){
        RabDetail::where('id',$id)->delete();
        return redirect()->route('rab.create')->with(['success' => 'Item Dihapus !']);
    }
    private function generateNomorRab()
    {

        $roleName = Auth::user()->role->name;

        $year_now = date('Y');

        $obj = DB::table('rab')
        ->select('nomor_rab')
        ->latest('id')
            ->where('timestamp', 'ilike', $year_now . '%')
            ->first();

        $day = date('Y-m-d');
        $objDay = DB::table('rab')
        ->select('nomor_rab')
        ->latest('id')
        ->where('timestamp', 'ilike', $day . '%')
        ->first();


        $objLast = DB::table('rab')
            ->select('nomor_rab')
            ->latest('id')
            ->first();
        $blnrmwThn = $this->getRomawi(date('m')).'-'.date('Y');
        if ($obj != null|| $objDay != null ) {

            if ($obj) {
                $incrementYear = explode('/', $obj->nomor_rab);
                $incrementYear = substr($incrementYear[0], 1);
                $incrementYear = $incrementYear;

            } else {

                $incrementYear = explode('/', $objLast->nomor_rab);
                $incrementYear = substr($incrementYear[0], 1);
                $incrementYear = $incrementYear+1;
            }


            if($objDay){
                $incrementDay = explode('/', $objDay->nomor_rab);
                $incrementDay = substr($incrementDay[2], 1);
                $incrementDay = $incrementDay + 1;

            }else{
                $incrementDay = 0;
            }


            $generateCode = str_pad($incrementYear, 3, "0", STR_PAD_LEFT) .'/RAB/' . str_pad($incrementDay, 2, "0", STR_PAD_LEFT). '/DNG.TPK/' . $blnrmwThn;
        } else {
            if($objLast){
                $incrementYear = explode('/', $objLast->nomor_rab);
                $incrementYear = substr($incrementYear[0], 1);
                $incrementYear = $incrementYear + 1;
                $generateCode = str_pad($incrementYear, 3, "0", STR_PAD_LEFT) . '/RAB/' . '00' . '/DNG.TPK/' . $blnrmwThn;
            }else{
                $generateCode = str_pad(1, 3, "0", STR_PAD_LEFT) . '/RAB/' .'00' . '/DNG.TPK/' . $blnrmwThn;
            }

        }
        return $generateCode;
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
}
