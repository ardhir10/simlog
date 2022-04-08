<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermintaanBarang extends Model
{
    protected $table = 'permintaan_barang';
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function barang_diminta(){
        return $this->hasMany(PermintaanBarangDetail::class,'permintaan_barang_id','id');
    }
    public function dimintaOleh(){

        $roleName = $this->user->role->name ?? null;
        if ($roleName == 'Nakhoda') {
            $kode = $this->user->kapalNegara->nama_kapal ?? null;
        } else if ($roleName == 'Manager VTS') {
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
    public function bagianBidang(){

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
            $roleName == 'Manager VTS' ||
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
}
