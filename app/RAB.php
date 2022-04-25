<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RAB extends Model
{
    protected $table = 'rab';
    protected $guarded = [];

    public function detailRab(){
        return $this->hasMany(DetailRab::class,'rab_id','id');
    }
    public function lastProcess($role = '')
    {
        if ($role) {
            return $process = ApprovalRab::where('rab_id', $this->id)
                ->where('role_to_name', $role)
                ->orderBy('id', 'desc')
                ->first();
        } else {
            return $process = ApprovalRab::where('rab_id', $this->id)
                ->orderBy('id', 'desc')
                ->first();
        }
    }

    public function pengguna(){

        // PENGGUNANYA
        switch ($this->pengguna) {
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
    public function dimintaOleh()
    {

        $roleName = $this->user->role->name ?? null;
        if ($roleName == 'Nakhoda') {
            $kode = $this->user->kapalNegara->nama_kapal ?? null;
        } else if ($roleName == 'Kepala VTS') {
            $kode = $this->user->vts->nama_stasiun_vts ?? null;
        } else if ($roleName == 'Kepala SROP') {
            $kode = $this->user->srop->nama_srop ?? null;
        } else if (
            $roleName == 'Kepala Distrik Navigasi'
        ) {
            $kode = 'Kepala Distrik Navigasi';
        } else if (
            $roleName == 'Kabag Tata Usaha' ||
            $roleName == 'Subbag Kepegawaian dan Umum' ||
            $roleName == 'Subbag Keuangan'
        ) {
            $kode = 'Bagian Tata Usaha';
        } else if (
            $roleName == 'Kabid Operasi' ||
            $roleName == 'Seksi Program' ||
            $roleName == 'Seksi Sarana Prasarana'
        ) {
            $kode = 'Bidang Operasi';
        } else if (
            $roleName == 'Kabid Logistik' ||
            $roleName == 'Seksi Pengadaan' ||
            $roleName == 'Seksi Inventaris'
        ) {
            $kode = 'Bidang Logistik';
        } else if (
            $roleName == 'Kepala Kelompok Pengamatan Laut'
        ) {
            $kode = 'Pengamatan Laut';
        } else if (
            $roleName == 'Kepala Kelompok Bengkel'
        ) {
            $kode = 'Bengkel';
        } else if (
            $roleName == 'Kepala Kelompok SBNP'
        ) {
            $kode = 'SBNP';
        } else {
            $kode = 'NONUSER';
        }

        return $kode;
    }
    public function bagianBidang()
    {
        $roleName = $this->user->role->name ?? null;
        if (
            $roleName == 'Kepala Distrik Navigasi' ||
            $roleName == 'Kabag Tata Usaha' ||
            $roleName == 'Subbag Kepegawaian dan Umum' ||
            $roleName == 'Subbag Keuangan'
        ) {
            $kode = 'Bagian Tata Usaha';
        } else if (
            $roleName == 'Kabid Operasi' ||
            $roleName == 'Seksi Program' ||
            $roleName == 'Seksi Sarana Prasarana' ||
            $roleName == 'Nakhoda' ||
            $roleName == 'Kepala VTS' ||
            $roleName == 'Kepala SROP' ||
            $roleName == 'Kepala Kelompok Pengamatan Laut' ||
            $roleName == 'Kepala Kelompok Bengkel' ||
            $roleName == 'Kepala Kelompok SBNP'
        ) {
            $kode = 'Bidang Operasi';
        } else if (
            $roleName == 'Kabid Logistik' ||
            $roleName == 'Seksi Pengadaan' ||
            $roleName == 'Seksi Inventaris'
        ) {
            $kode = 'Bidang Logistik';
        } else {
            $kode = 'NONUSER';
        }

        return $kode;
    }

    public function timeline()
    {
        return $this->hasMany(ApprovalRab::class, 'rab_id', 'id');
    }

    public function barang_diminta()
    {
        return $this->hasMany(RabDetail::class, 'rab_id', 'id');
    }
    public function approvals()
    {
        return $this->hasMany(ApprovalRab::class, 'rab_id', 'id');
    }
}
