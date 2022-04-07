<?php

namespace App\Http\Controllers;

use App\LaporanPengawasan;
use App\MenaraSuar;
use App\PelampungSuar;
use App\RambuSuar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ApiSbnpController extends Controller
{
    public function getDashboard($id,$type){
        // --
        if($type == 'menara_suar'){
            $type = 'Menara Suar';
            $data['data_master'] = MenaraSuar::select(DB::raw('adm_nama_menara_suar as nama,*'))->find($id);
        } elseif ($type == 'rambu_suar') {
            $type = 'Rambu Suar';
            $data['data_master'] = RambuSuar::select(DB::raw('adm_nama_rambu_suar as nama,*'))->find($id);
        } elseif ($type == 'pelampung_suar') {
            $type = 'Pelampung Suar';
            $data['data_master'] = PelampungSuar::select(DB::raw('adm_nama_pelampung_suar as nama,*'))->find($id);
        }else{

        }


        // Search From Laporan Pengawasan/Pemeliharaan
        $pengawasanPemeliharaan = LaporanPengawasan::where('sbnp_id', $id)
            ->where('type_sbnp', $type)
            ->orderBy('id', 'desc')
            ->first();

        if ($pengawasanPemeliharaan) {
            $from = 'pengawasan';
            $data['data'] = $pengawasanPemeliharaan;
            $data['keandalan'] = number_format($pengawasanPemeliharaan->keandalan(),2);
            $data['bobot_keandalan'] = $pengawasanPemeliharaan->bobotKeandalan();
            $data['kondisi_teknis'] = number_format($pengawasanPemeliharaan->kondisiTeknis(),2);
            $data['bobot_kondisi_teknis'] = $pengawasanPemeliharaan->bobotTeknis();

        } else {
            $from = 'master';
            if ($type == 'Menara Suar') {
                $data['data'] = MenaraSuar::select(DB::raw('adm_nama_menara_suar as nama,*'))->find($id);
            } elseif ($type == 'Rambu Suar') {
                $data['data'] = RambuSuar::select(DB::raw('adm_nama_rambu_suar as nama,*'))->find($id);
            } elseif ($type == 'Pelampung Suar') {
                $data['data'] = PelampungSuar::select(DB::raw('adm_nama_pelampung_suar as nama,*'))->find($id);
            }else{

            }
            $data['keandalan'] = 0;
            $data['kondisi_teknis'] = 0;
            $data['bobot_keandalan'] = '';
            $data['bobot_kondisi_teknis'] = '';
        }
        $data['from'] = $from;

        $data['tab_data'] = [
            'informasi_sbnp' => [
                'nomor_dsi'=> $data['data']->nts_nomor_dsi,
                'nomor_ba'=> $data['data']->nts_nomor_ba,
            ]
        ];
        return  response()->json($data);
    }

    public function getSbnp(Request $request){
         try {
            $jwtToken = $request->token;
            // {
            // "alg": "HS256",
            // "typ": "JWT"
            // }
            $decoded = JWT::decode($jwtToken, new Key('atonSecret', 'HS256'));
            $listUid = [1000001];

            if(in_array($decoded->uid,$listUid)){
                return 'Success';
            }else{
                return 'uid not registered';
            }

            print_r($decoded->uid);
        } catch (\Throwable $th) {
            print_r($th->getMessage());
        }

    }
}
