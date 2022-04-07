<?php

namespace App\Http\Controllers;

use App\LaporanPengawasan;
use App\MenaraSuar;
use App\PelampungSuar;
use App\RambuSuar;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

CONST MENARA_SUAR = 'menara_suar';
CONST RAMBU_SUAR = 'rambu_suar';
CONST PELAMPUNG_SUAR = 'pelampung_suar';
class ApiAtonrepController extends Controller
{
    protected $secret = 'atonRepsSecret2022';
    public function getSecret(){
        return $this->secret;
    }
    public function getSbnp(Request $request)
    {
        try {
            $jwtToken = $request->token;

            // --- DECODE JWTTOKEN
            $decoded = JWT::decode($jwtToken, new Key($this->secret, 'HS256'));

            // --- LIST UID
            $listUid = [1000001];

            // --- CHEKCING SIGNATURE
            if (in_array($decoded->uid, $listUid)) {
                $ms = MenaraSuar::select('*')->get();
                $rs = RambuSuar::select('*')->get();
                $ps = PelampungSuar::select('*')->get();
                $allSbnp = $ms->merge($rs)->merge($ps);
                $allSbnpMap = [];
                foreach ($allSbnp as $key => $value) {
                    if ($value->getTable() == 'menara_suar') {
                        $nama = $value->adm_nama_menara_suar;
                        $penandaan = 'N/A';
                        $laporan = LaporanPengawasan::where('sbnp_id', $value->id)
                        ->where('type_sbnp', 'Menara Suar')
                        ->orderBy('id', 'desc')
                            ->first();
                    } elseif ($value->getTable() == 'rambu_suar') {
                        $nama = $value->adm_nama_rambu_suar;
                        $penandaan = $value->penandaan->nama;
                        $laporan = LaporanPengawasan::where('sbnp_id', $value->id)
                        ->where('type_sbnp', 'Rambu Suar')
                        ->orderBy('id', 'desc')
                        ->first();
                    } elseif ($value->getTable() == 'pelampung_suar') {
                        $nama = $value->adm_nama_pelampung_suar;
                        $penandaan = $value->jenisPenandaan->nama;
                        $laporan = LaporanPengawasan::where('sbnp_id', $value->id)
                        ->where('type_sbnp', 'Pelampung Suar')
                        ->orderBy('id', 'desc')
                        ->first();
                    } else {
                        $nama = 'N/A';
                    }
                    $allSbnpMap[] = (object)[
                        'id' => $value->id,
                        'name' => $nama,
                        'penyelenggara' => $value->penyelenggara() ?? null,

                        'perairan' => $value->perairan->nama ?? 'Tidak Dalam Perairan',
                        'long_deg'=> $value->nts_long_deg,
                        'long_min'=> $value->nts_long_min,
                        'long_sec'=> $value->nts_long_sec,
                        'long_dir'=> $value->nts_long_dir,
                        'long_dec' => $value->longDec() ?? null,

                        'lat_min' => $value->nts_lat_min,
                        'lat_sec' => $value->nts_lat_sec,
                        'lat_dir' => $value->nts_lat_dir,
                        'lat_dec' => $value->latDec() ?? null,

                        'penandaan' => $penandaan,
                        'kondisi_teknis' => optional($laporan)->kondisiTeknis() ?? null,
                        'keandalan' => optional($laporan)->keandalan() ?? null,
                        'type' => $value->getTable(),
                    ];
                }

                $reponse = [
                    'success' => true,
                    'message' => 'Get data success !',
                    'data'=> [
                        'sbnp_total' =>[
                            'menara_suar' => $ms->count(),
                            'rambu_suar' => $rs->count(),
                            'pelampung_suar' => $ps->count(),
                        ],
                        'sbnp'=> $allSbnpMap
                    ]
                ];
                return  response()->json($reponse);
            } else {
                // --- IF SIGNATURE NOT MATCH
                return  response()->json([
                    'success' => false,
                    'message' => 'uid not registered'
                ]);
            }
        } catch (\Throwable $th) {
            return  response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function getSbnpDetail(Request $request){
        try {
            $jwtToken = $request->token;

            // --- DECODE JWTTOKEN
            $decoded = JWT::decode($jwtToken, new Key($this->secret, 'HS256'));

            // --- LIST UID
            $listUid = [1000001];

            // --- CHEKCING SIGNATURE
            if (in_array($decoded->uid, $listUid)) {

                // -- CHECK TYPE SBNP
                switch ($decoded->type) {
                    case MENARA_SUAR:
                        $data = MenaraSuar::find($decoded->sbnp_id);
                        if($data){
                            $data['adm_penyelenggara'] = $data->penyelenggara();
                            $data->lahan;
                            $data->perairan;
                            $data->badanUsaha;
                            $data->ksopupp;

                            $data->tandaSiangMenaraSuar;
                            $data->fungsiMenaraSuar;
                            $data->jenisKonstruksi;
                            $data->sistemListrik;
                            $data->bangunan;
                            $data->sumberAir;
                            $reponse = [
                                'success' => true,
                                'message' => 'Succes get data Menara Suar',
                                'data' => $data
                            ];
                        }else{
                            $reponse = [
                                'success' => false,
                                'message' => 'Menara Suar Not Found !',
                            ];
                        }

                        break;
                    case RAMBU_SUAR:
                        $data = RambuSuar::find($decoded->sbnp_id);
                        if ($data) {
                            $data['adm_penyelenggara'] = $data->penyelenggara();
                            $data->badanUsaha;
                            $data->lahan;
                            $data->ksopupp;

                            $data->tandaPuncak;
                            $data->penandaan;
                            $data->sistemListrik;
                            $data->jenisKonstruksi;
                            $data->jenisPondasi;
                            $data->jenisPlatform;



                            $reponse = [
                                'success' => true,
                                'message' => 'Succes get data Rambu Suar',
                                'data' => $data
                            ];
                        } else {
                            $reponse = [
                                'success' => false,
                                'message' => 'Rambu Suar Not Found !',
                            ];
                        }
                        break;
                    case PELAMPUNG_SUAR:
                        $data = PelampungSuar::find($decoded->sbnp_id);
                        if ($data) {
                            $data['adm_penyelenggara'] = $data->penyelenggara();
                            $data->badanUsaha;
                            $data->lahan;
                            $data->ksopupp;

                            $data->perairan;

                            $data->tandaSiang;
                            $data->jenisPenandaan;
                            $data->tipeBadan;

                            $data->tandaPuncak;
                            $data->jenisKonstruksi;
                            $data->bahanBadan;
                            $data->tipeBadan;


                            $reponse = [
                                'success' => true,
                                'message' => 'Succes get data Pelampung Suar',
                                'data' => $data
                            ];
                        } else {
                            $reponse = [
                                'success' => false,
                                'message' => 'Pelampung Suar Not Found !',
                            ];
                        }
                        break;
                    default:
                        $reponse = [
                            'success' => false,
                            'message' => 'Wrong SBNP Type',
                        ];
                        break;
                }
                return  response()->json($reponse);
            } else {
                // --- IF SIGNATURE NOT MATCH
                return  response()->json([
                    'success' => false,
                    'message' => 'uid not registered'
                ]);
            }
        } catch (\Throwable $th) {
            return  response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
