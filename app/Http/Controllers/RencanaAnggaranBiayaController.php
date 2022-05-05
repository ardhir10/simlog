<?php

namespace App\Http\Controllers;

use App\ApprovalRab;
use App\BarangPersediaan;
use App\RAB;
use App\RabDetail;
use App\RencanaKebutuhan;
use App\RencanaKebutuhanDetail;
use App\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;


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


        $data['rencana_kebutuhan'] = RencanaKebutuhan::where('status','Disetujui')->get();

        $data['rab_details'] = RabDetail::where('rab_id',$dataRab->id ??null)->get();
        $data['rab'] = RAB::orderBy('id', 'desc')->get();
        return view('rab.create', $data);
    }

    public function store(Request $request,$id=null){
        if($id == null){
            try {
                if($request->submit == 'AJUKAN'){
                    // RAB::whereId($id)->update(['is_draft'=>false]);
                    // return redirect()->route('rab.index')->with(['success' => 'Rab di Ajukan !']);
                }else{
                    DB::beginTransaction();
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
                    $dataRab['rencana_kebutuhan_id'] = $request->rencana_kebutuhan_id;


                    $id = RAB::create($dataRab);
                    $id = $id->id;
                    // Jika Dari Rencan Kebutuhan
                    if ($request->rencana_kebutuhan_id) {
                        $listBarangRencanaKebutuhan = RencanaKebutuhanDetail::where('rencana_kebutuhan_id', $request->rencana_kebutuhan_id)->get();

                        foreach ($listBarangRencanaKebutuhan as $key => $lbrk) {
                            $cariBarang = RabDetail::where('rab_id', $id)
                            ->where('nama_barang', $lbrk->nama_barang)
                            ->where('satuan', $lbrk->satuan)
                            ->where('harga_satuan', $lbrk->harga_satuan)
                            ->where('mata_uang', $lbrk->mata_uang)
                            ->first();
                            if ($cariBarang) {
                                $data['rab_id'] = $id;
                                $data['nama_barang'] = $lbrk->nama_barang;
                                $data['satuan'] = $lbrk->satuan;
                                $data['qty'] = $lbrk->qty;
                                $data['harga_satuan']
                                    = $lbrk->harga_satuan;
                                $data['mata_uang']
                                    = $lbrk->mata_uang;
                                $data['keterangan']
                                    = $lbrk->keterangan;
                                $data['add_by'] = Auth::user()->id;

                                RabDetail::where('rab_id', $id)->update($data);
                            } else {
                                $data['rab_id'] = $id;
                                $data['nama_barang'] = $lbrk->nama_barang;
                                $data['satuan'] = $lbrk->satuan;
                                $data['qty'] = $lbrk->qty;
                                $data['harga_satuan']
                                    = $lbrk->harga_satuan;
                                $data['mata_uang']
                                    = $lbrk->mata_uang;
                                $data['keterangan']
                                    = $lbrk->keterangan;
                                $data['add_by'] = Auth::user()->id;

                                RabDetail::create($data);
                            }
                        }
                    }
                    DB::commit();
                    return redirect()->route('rab.create')->with(['success' => 'Rab di buat !']);
                }

            } catch (\Throwable $th) {
                DB::rollBack();
                return redirect()->route('rab.create')->with(['failed' => $th->getMessage()]);
            }

        }else{
            try {
                if ($request->submit == 'AJUKAN') {

                    DB::beginTransaction();
                    RAB::whereId($id)->update([
                        'is_draft'=> false,
                        'status'=> 'Dalam Proses',
                    ]);
                    // Buat Approval Persetujuan RAB
                    $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                    $dataApproval['rab_id'] = $id;
                    $dataApproval['user_id'] = Auth::user()->id;
                    $dataApproval['user_name'] = Auth::user()->name;
                    $dataApproval['role_to_name'] = 'Kasie Pengadaan';
                    $dataApproval['type'] = 'Menunggu Persetujuan Kasie Pengadaan';
                    $dataApproval['status'] = '';
                    $dataApproval['keterangan'] = $request->keterangan ?? '';
                    $dataApproval['tindak_lanjut'] = '';
                    $dataApproval['approve_by_id'] = Auth::user()->id;
                    $dataApproval['kategori'] = "APPROVAL";

                    if ($request->harga_satuan) {
                        foreach ($request->harga_satuan as $key => $value) {
                            RabDetail::where('id',$key)
                                    ->update([
                                        'harga_satuan' =>$value,
                                        'mata_uang' =>$request->mata_uang[$key],
                                    ]);
                        }
                    }

                    ApprovalRab::create($dataApproval);
                    DB::commit();
                    return redirect()->route('rab.index')->with(['success' => 'Rab di Ajukan !']);
                }
            } catch (\Throwable $th) {
                DB::rollback();
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

    public function batalkan($id=0){
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


    // APPROVAL

    public function approvalReview($id){
        $data['page_title'] = 'Rencana Anggaran Biaya';
        $data['data'] = RAB::find($id);

        return view('rab-approval.review',$data);
    }

    public function approvalTindakLanjut(Request $request,$id){

        $rab = RAB::find($id);
        // update approval sebelumnya
        ApprovalRab::where('rab_id', $id)
        ->orderBy('id', 'desc')
            ->first()
            ->update([
                'status' => 'done',
                'tindak_lanjut' => '',
                'keterangan' => $request->keterangan ?? ''
            ]);
        if($request->tindak_lanjut == 'SETUJUI'){
            DB::beginTransaction();


            if(Auth::user()->role->name == 'Kasie Pengadaan'){
                foreach ($request->jumlah_disetujui as $key => $value) {
                    // Update Rab Detail
                    RabDetail::where('id', $key)->update(['jumlah_disetujui' => $value]);
                }
                // APPROVAL DISETUJUI KASIE PENGADAAN
                $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                $dataApproval['rab_id'] = $id;
                $dataApproval['user_id'] = $rab->created_by;
                $dataApproval['user_name'] = $rab->created_by_name ?? '';
                $dataApproval['role_to_name'] = 'Kasie Pengadaan' ?? '';

                $dataApproval['type'] = 'Disetujui Kasie Pengadaan';
                $dataApproval['status'] = 'done';

                $dataApproval['keterangan'] = $request->keterangan ?? '';

                $dataApproval['tindak_lanjut'] = $request->tindak_lanjut;
                $dataApproval['approve_by_id'] = Auth::user()->id;
                $dataApproval['kategori'] = 'PERSETUJUAN';
                ApprovalRab::create($dataApproval);

                // Buat Approval Persetujuan RAB
                $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
                $dataPersetujuan['rab_id'] = $id;
                $dataPersetujuan['user_id'] = Auth::user()->id;
                $dataPersetujuan['user_name'] = Auth::user()->name;
                $dataPersetujuan['role_to_name'] = 'Kabid Logistik';
                $dataPersetujuan['type'] = 'Menunggu Persetujuan Kabid Logistik';
                $dataPersetujuan['status'] = '';
                $dataPersetujuan['keterangan'] = $request->keterangan ?? '';
                $dataPersetujuan['tindak_lanjut'] = '';
                $dataPersetujuan['approve_by_id'] = Auth::user()->id;
                $dataPersetujuan['kategori'] = "APPROVAL";
                ApprovalRab::create($dataPersetujuan);

            } elseif (Auth::user()->role->name == 'Kabid Logistik'){
                foreach ($request->jumlah_disetujui as $key => $value) {
                    // Update Rab Detail
                    RabDetail::where('id', $key)->update(['jumlah_disetujui' => $value]);
                }
                // APPROVAL DISETUJUI KaBID LOGISTIK PENGADAAN
                $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                $dataApproval['rab_id'] = $id;
                $dataApproval['user_id'] = $rab->created_by;
                $dataApproval['user_name'] = $rab->created_by_name ?? '';
                $dataApproval['role_to_name'] = 'Kabid Logistik' ?? '';

                $dataApproval['type'] = 'Disetujui Kabid Logistik';
                $dataApproval['status'] = 'done';

                $dataApproval['keterangan'] = $request->keterangan ?? '';

                $dataApproval['tindak_lanjut'] = $request->tindak_lanjut;
                $dataApproval['approve_by_id'] = Auth::user()->id;
                $dataApproval['kategori'] = 'PERSETUJUAN';
                ApprovalRab::create($dataApproval);


                $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                $dataApproval['rab_id'] = $id;
                $dataApproval['user_id'] = $rab->created_by;
                $dataApproval['user_name'] = $rab->created_by_name ?? '';
                $dataApproval['role_to_name'] = '' ?? '';

                $dataApproval['type'] = 'RAB Disetujui';
                $dataApproval['status'] = 'done';

                $dataApproval['keterangan'] = $request->keterangan ?? '';

                $dataApproval['tindak_lanjut'] = $request->tindak_lanjut;
                $dataApproval['approve_by_id'] = Auth::user()->id;
                $dataApproval['kategori'] = 'PERSETUJUAN';
                ApprovalRab::create($dataApproval);
            }



            RAB::where('id', $id)
                ->update(['status' => 'Selesai']);
            DB::commit();
            return redirect()->route('rab.approval-review', $rab->id)->with(['success' => 'Data Berhasil Disetujui !']);

        }elseif($request->tindak_lanjut == "UPDATE"){

            DB::beginTransaction();
            if (Auth::user()->role->name == 'Kasie Pengadaan') {
                foreach ($request->jumlah_disetujui as $key => $value) {
                    // Update Rab Detail
                    RabDetail::where('id', $key)->update(['jumlah_disetujui' => $value]);
                }
                // APPROVAL DISETUJUI KASIE PENGADAAN
                $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                $dataApproval['rab_id'] = $id;
                $dataApproval['user_id'] = $rab->created_by;
                $dataApproval['user_name'] = $rab->created_by_name ?? '';
                $dataApproval['role_to_name'] = 'Kasie Pengadaan' ?? '';

                $dataApproval['type'] = 'Disetujui Kasie Pengadaan';
                $dataApproval['status'] = 'done';

                $dataApproval['keterangan'] = $request->keterangan ?? '';

                $dataApproval['tindak_lanjut'] = $request->tindak_lanjut;
                $dataApproval['approve_by_id'] = Auth::user()->id;
                $dataApproval['kategori'] = 'PERSETUJUAN';
                ApprovalRab::create($dataApproval);

                // Buat Approval Persetujuan RAB
                $dataPersetujuan['timestamp'] = date('Y-m-d H:i:s');
                $dataPersetujuan['rab_id'] = $id;
                $dataPersetujuan['user_id'] = Auth::user()->id;
                $dataPersetujuan['user_name'] = Auth::user()->name;
                $dataPersetujuan['role_to_name'] = 'Kabid Logistik';
                $dataPersetujuan['type'] = 'Menunggu Persetujuan Kabid Logistik';
                $dataPersetujuan['status'] = '';
                $dataPersetujuan['keterangan'] = $request->keterangan ?? '';
                $dataPersetujuan['tindak_lanjut'] = '';
                $dataPersetujuan['approve_by_id'] = Auth::user()->id;
                $dataPersetujuan['kategori'] = "APPROVAL";
                ApprovalRab::create($dataPersetujuan);
            } elseif (Auth::user()->role->name == 'Kabid Logistik') {
                foreach ($request->jumlah_disetujui as $key => $value) {
                    // Update Rab Detail
                    RabDetail::where('id', $key)->update(['jumlah_disetujui' => $value]);
                }
                // APPROVAL DISETUJUI KaBID LOGISTIK PENGADAAN
                $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                $dataApproval['rab_id'] = $id;
                $dataApproval['user_id'] = $rab->created_by;
                $dataApproval['user_name'] = $rab->created_by_name ?? '';
                $dataApproval['role_to_name'] = 'Kabid Logistik' ?? '';

                $dataApproval['type'] = 'Disetujui Kabid Logistik';
                $dataApproval['status'] = 'done';

                $dataApproval['keterangan'] = $request->keterangan ?? '';

                $dataApproval['tindak_lanjut'] = $request->tindak_lanjut;
                $dataApproval['approve_by_id'] = Auth::user()->id;
                $dataApproval['kategori'] = 'PERSETUJUAN';
                ApprovalRab::create($dataApproval);


                $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                $dataApproval['rab_id'] = $id;
                $dataApproval['user_id'] = $rab->created_by;
                $dataApproval['user_name'] = $rab->created_by_name ?? '';
                $dataApproval['role_to_name'] = '' ?? '';

                $dataApproval['type'] = 'RAB Disetujui';
                $dataApproval['status'] = 'done';

                $dataApproval['keterangan'] = $request->keterangan ?? '';

                $dataApproval['tindak_lanjut'] = $request->tindak_lanjut;
                $dataApproval['approve_by_id'] = Auth::user()->id;
                $dataApproval['kategori'] = 'PERSETUJUAN';
                ApprovalRab::create($dataApproval);
            }


            RAB::where('id', $id)
                ->update(['status' => 'Selesai']);

            DB::commit();
            return redirect()->route('rab.approval-review', $rab->id)->with(['success' => 'Data Berhasil Disetujui !']);

        }elseif($request->tindak_lanjut == 'TOLAK'){
            DB::beginTransaction();
            if (Auth::user()->role->name == 'Kasie Pengadaan') {
                // APPROVAL PROCESS
                $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                $dataApproval['rab_id'] = $id;
                $dataApproval['user_id'] = $rab->created_by;
                $dataApproval['user_name'] = $rab->created_by_name ?? '';
                $dataApproval['role_to_name'] = '' ?? '';


                $dataApproval['type'] = 'Permintaan Ditolak Kasie Pengadaan';
                $dataApproval['status'] = '';

                $dataApproval['keterangan'] = $request->keterangan ?? 'Ditolak';

                $dataApproval['tindak_lanjut'] = $request->tindak_lanjut;
                $dataApproval['approve_by_id'] = Auth::user()->id;
                $dataApproval['kategori'] = 'APPROVAL';

                ApprovalRab::create($dataApproval);

            } elseif (Auth::user()->role->name == 'Kabid Logistik') {
                // APPROVAL PROCESS
                $dataApproval['timestamp'] = date('Y-m-d H:i:s');
                $dataApproval['rab_id'] = $id;
                $dataApproval['user_id'] = $rab->created_by;
                $dataApproval['user_name'] = $rab->created_by_name ?? '';
                $dataApproval['role_to_name'] = '' ?? '';


                $dataApproval['type'] = 'Permintaan Ditolak Kabid Logistik';
                $dataApproval['status'] = '';

                $dataApproval['keterangan'] = $request->keterangan ?? 'Ditolak';

                $dataApproval['tindak_lanjut'] = $request->tindak_lanjut;
                $dataApproval['approve_by_id'] = Auth::user()->id;
                $dataApproval['kategori'] = 'APPROVAL';

                ApprovalRab::create($dataApproval);
            }


            RAB::where('id', $id)
                ->update(['status' => 'Ditolak']);
            DB::commit();
            return redirect()->route('rab.approval-review', $rab->id)->with(['success' => 'Data Berhasil ditolak !']);
        }else{
            dd('NOT AVAILABLE');
        }
    }

    public function cetakRab(Request $request, $id)
    {
        $data['title'] = 'UPP4';

        $rab = RAB::where('id', $id)
            ->first();
        $data['data'] =$rab ;
        if ($request->v == 'html') {
            return view('pdf.rab', $data);
        }


        $pdf = PDF::loadView('pdf.rab', $data)->setOptions(['isRemoteEnabled' => true, "isPhpEnabled" => true])->setPaper('a4', 'potrait');
        // $font = Font_Metrics::get_font("helvetica", "bold");

        return $pdf->stream($rab->nomor_rab . ' (RAB).pdf');
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
