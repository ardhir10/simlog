<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{

    protected $table = 'users';
    use Notifiable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     // 'name', 'email', 'password','username', 'fs_avatar'
    // ];
    protected $guarded = [
        // 'name', 'email', 'password','username', 'fs_avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


     public function typeUser(){
         if($this->type_user == 'typeInternalUser'){
            return 'Internal';

         }elseif($this->type_user == 'typeBadanUsahaPemilik'){
             return 'Eksternal';
         }else{
            return 'N/A';
         }
     }


    public function role(){
        return $this->belongsTo(Role::class);
    }
    public function liniUsaha(){
        return $this->belongsTo(LiniUsaha::class);
    }

    public function kapalNegara()
    {
        return $this->belongsTo(KapalNegara::class,'kapal_negara_id','id');
    }

    public function srop()
    {
        return $this->belongsTo(StasiunRadioPantai::class, 'srop_id', 'id');
    }

    public function vts()
    {
        return $this->belongsTo(StasiunVts::class, 'stasiun_vts_id', 'id');
    }


    public function getSubKategoriKode($roleName=null)
    {
        if($roleName == null)
            $roleName = $this->role->name ?? null;
            /*
            PL.001/04/SIM/KNK-2022
            PL. adalah bentuk baku, 001 adalah nomor urut auto generate (Nomor Urut direset jadi 001
            jika berbeda bulan), 04 Adalah bulan April (05-Mei, 06-Juni, dan seterusnya), SIM adalah
            bentuk baku yang mengartikan SIMLOG, KNK adalah Kode Role User, -2022 adalah tahun.
            Kode Role User
            • Jika Role = (Nakhoda), Kode = KNK
            • Jika Role = (Kepala VTS), Kode = VTS
            • Jika Role = (Kepala SROP), Kode = SROP
            • Jika Role = (Kepala Distrik Navigasi), Kode = TU
            • Jika Role = (Kabag Tata Usaha, Subbag Kepegawaian & Umum, Subbag Keuangan), Kode = TU
            • Jika Role = (Kabid Operasi, Seksi Program, Seksi Sarana Prasarana), Kode = OPS
            • Jika Role = (Kabid Logistik, Seksi Pengadaan, Seksi Inventaris), Kode = LOG
            • Jika Role = (Kepala Kelompok Pengamatan Laut), Kode = PENGLA
            • Jika Role = (Kepala Kelompok Bengkel), Kode = BKL
            • Jika Role = (Kepala Kelompok SBNP), Kode = SBNP
            */

        if ($roleName == 'Kepala Kelompok Bengkel') {
            $kode = '12';
        }  else if (
            $roleName == 'Kasie Kepeg & Umum' ||
            $roleName == 'Subbag Kepegawaian dan Umum'
        ) {
            $kode = '02';
        }
         else if (
            $roleName == 'Kasie Keuangan' ||
            $roleName == 'Subbag Keuangan'
        ) {
            $kode = '03';
        } else if (
            $roleName == 'Kasie Pengadaan' ||
            $roleName == 'Staff Seksi Pengadaan' ||
            $roleName == 'Seksi Seksi Pengadaan'
        ) {
            $kode = '04';
        } else if (
            $roleName == 'Kasie Inventaris' ||
            $roleName == 'Seksi Inventaris'
        ) {
            $kode = '05';
        } else if (
            $roleName == 'Kasie Sarpras' ||
            $roleName == 'Seksi Sarana Prasarana'
        ) {
            $kode = '06';
        } else if (
            $roleName == 'Kasie Program' ||
            $roleName == 'Seksi Program'
        ) {
            $kode = '07';
        } else if (
            $roleName == 'Kepala Kelompok SBNP'
        ) {
            $kode = '08';
        } else if ($roleName == 'Kepala VTS' || $roleName == 'Kepala SROP') {
            $kode = '09';
        } else if ($roleName == 'Kepala Kelompok Pengamatan Laut' ) {
            $kode = '10';
        } else if ($roleName == 'Nakhoda') {
            $kode = '11';
        }


        else {
            // UMUM
            $kode = '01';
        }

        return $kode;
    }

}
