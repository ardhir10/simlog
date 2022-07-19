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
    public function laporanDistribusi(){
        return $this->hasOne(LaporanDistribusi::class, 'permintaan_barang_id','id');
    }

    public function lastProcess($role = ''){
        if($role){
            return $process = ApprovalProcess::where('permintaan_barang_id', $this->id)
                ->where('role_to_name',$role)
                ->orderBy('id', 'desc')
                ->first();
        }else{
            return $process = ApprovalProcess::where('permintaan_barang_id',$this->id)
            ->orderBy('id','desc')
            ->first();
        }
    }
    public function fromKadisnav(){
        return $process = ApprovalProcess::where('permintaan_barang_id', $this->id)
            ->where('from_kadisnav', '!=', null)
            ->orderBy('id', 'desc')
            ->first()->from_kadisnav ?? null;
    }
    public function lastApproval(){
            return $process = ApprovalProcess::where('permintaan_barang_id',$this->id)
            ->where('kategori','PERSETUJUAN')
            ->orderBy('id','desc')
            ->first();
    }
    public function isNeedApprove(){
            return $process = ApprovalProcess::where('permintaan_barang_id',$this->id)
            ->where('kategori','APPROVAL')
            ->where('status', '!=', 'done')
            ->orderBy('id','desc')
            ->first();
    }
    public function isNeedApproveDisposisi(){
            return $process = ApprovalProcess::where('permintaan_barang_id',$this->id)
            ->where('kategori','DISPOSISI')
            ->where('status','!=','done')
            ->orderBy('id','desc')
            ->first();
    }
    public function isTindakLanjut($step = 1){
        return $process = ApprovalProcess::where('step','>',$step)
            ->orderBy('id','desc')
            ->first();
    }
    public function approvals(){
        return $this->hasMany(ApprovalProcess::class, 'permintaan_barang_id', 'id');
    }
    public function timeline(){
        return $this->hasMany(ApprovalProcess::class, 'permintaan_barang_id', 'id');
    }

    public function barang_diminta(){
        return $this->hasMany(PermintaanBarangDetail::class,'permintaan_barang_id','id');
    }
    public function dimintaOleh(){
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

    public function bagianBidangNotaDinas()
    {
        $roleName = $this->user->role->name ?? null;
        if ($roleName == 'Nakhoda') {
            $kode = 'Nakhoda';
        } elseif($roleName == 'Kepala VTS') {
            $kode = 'Kepala VTS';
        } elseif($roleName == 'Kepala SROP') {
            $kode = 'Kepala SROP';
        } elseif($roleName == 'Kepala Kelompok SBNP') {
            $kode = 'Kepala Kelompok SBNP';
        } elseif($roleName == 'Kepala Kelompok Pengamatan Laut') {
            $kode = 'Kepala Kelompok Pengamatan Laut';
        } elseif($roleName == 'Kepala Kelompok Bengkel') {
            $kode = 'Kepala Kelompok Bengkel';
        } elseif($roleName == 'Kasie Program' || $roleName == 'Kasie Sarpras') {
            $kode = 'Kabid Operasi';
        } elseif($roleName == 'Kepala Distrik Navigasi' 
            || $roleName == 'Kasie Kepeg & Umum'
            || $roleName == 'Kasie Keuangan'
            ) {
            $kode = 'Kabag Tata Usaha';
        } elseif($roleName == 'Kasie Inventaris' || $roleName == 'Kasie Pengadaan') {
            $kode = 'Kabid Logistik';
        } else {
            $kode = 'NONUSER';
        }

        return $kode;
    }
    public function kepalaBagiannya()
    {
        // $roleName = $this->user->role->name ?? null;
        // if (
        //     $roleName == 'Kepala Distrik Navigasi' ||
        //     $roleName == 'Kabag Tata Usaha' ||
        //     $roleName == 'Subbag Kepegawaian dan Umum' ||
        //     $roleName == 'Subbag Keuangan'
        // ) {
        //     $kode = User::where('role_id', 33)->first()->name;
        // } else if (
        //     $roleName == 'Kabid Operasi' ||
        //     $roleName == 'Seksi Program' ||
        //     $roleName == 'Seksi Sarana Prasarana' ||
        //     $roleName == 'Nakhoda' ||
        //     $roleName == 'Kepala VTS' ||
        //     $roleName == 'Kepala SROP' ||
        //     $roleName == 'Kepala Kelompok Pengamatan Laut' ||
        //     $roleName == 'Kepala Kelompok Bengkel' ||
        //     $roleName == 'Kepala Kelompok SBNP'
        // ) {
        //     $kode = User::where('role_id', 34)->first()->name;
        // } else if (
        //     $roleName == 'Kabid Logistik' ||
        //     $roleName == 'Seksi Pengadaan' ||
        //     $roleName == 'Seksi Inventaris'
        // ) {
        //     $kode = User::where('role_id', 35)->first()->name;
        // } else {
        //     $kode = 'NONUSER';
        // }


        $roleName = $this->user->role->name ?? null;
        if ($roleName == 'Nakhoda') {
            $kode = 'Nakhoda';
        } else {
            $kode = 'NONUSER';
        }


        return $kode;


    }

    public function kepalaBagiannyaId()
    {
        // $roleName = $this->user->role->id ?? null;
        // if (
        //     $roleName == 'Kepala Distrik Navigasi' ||
        //     $roleName == 'Kabag Tata Usaha' ||
        //     $roleName == 'Subbag Kepegawaian dan Umum' ||
        //     $roleName == 'Subbag Keuangan'
        // ) {
        //     $kode = User::where('role_id', 33)->first()->id;
        // } else if (
        //     $roleName == 'Kabid Operasi' ||
        //     $roleName == 'Seksi Program' ||
        //     $roleName == 'Seksi Sarana Prasarana' ||
        //     $roleName == 'Nakhoda' ||
        //     $roleName == 'Kepala VTS' ||
        //     $roleName == 'Kepala SROP' ||
        //     $roleName == 'Kepala Kelompok Pengamatan Laut' ||
        //     $roleName == 'Kepala Kelompok Bengkel' ||
        //     $roleName == 'Kepala Kelompok SBNP'
        // ) {
        //     $kode = User::where('role_id', 34)->first()->id;
        // } else if (
        //     $roleName == 'Kabid Logistik' ||
        //     $roleName == 'Seksi Pengadaan' ||
        //     $roleName == 'Seksi Inventaris'
        // ) {
        //     $kode = User::where('role_id', 35)->first()->id;
        // } else {
        //     $kode = User::where('role_id', 34)->first()->id;
        // }

        $roleName = $this->user->role->name ?? null;
        if ($roleName == 'Nakhoda') {
            $kode = User::whereHas('role',function($q) use($roleName){
                $q->whereName($roleName);
            })->first()->id;
        } elseif ($roleName == 'Kepala VTS') {
            $kode = User::whereHas('role', function ($q) use ($roleName) {
                $q->whereName($roleName);
            })->first()->id;
        } elseif ($roleName == 'Kepala SROP') {
            $kode = User::whereHas('role', function ($q) use ($roleName) {
                $q->whereName($roleName);
            })->first()->id;
        } elseif ($roleName == 'Kepala Kelompok SBNP') {
            $kode = User::whereHas('role', function ($q) use ($roleName) {
                $q->whereName($roleName);
            })->first()->id;
        } elseif ($roleName == 'Kepala Kelompok Pengamatan Laut') {
            $kode = User::whereHas('role', function ($q) use ($roleName) {
                $q->whereName($roleName);
            })->first()->id;
        } elseif ($roleName == 'Kepala Kelompok Bengkel') {
            $kode = User::whereHas('role', function ($q) use ($roleName) {
                $q->whereName($roleName);
            })->first()->id;
        } elseif ($roleName == 'Kasie Program' || $roleName == 'Kasie Sarpras') {

            $kode = User::whereHas('role', function ($q) use ($roleName) {
                $q->whereName('Kabid Operasi');
            })->first()->id;
        } elseif ($roleName == 'Kepala Distrik Navigasi' || $roleName == 'Kasie Kepeg & Umum' || $roleName == 'Kasie Keuangan') {
            $kode = User::whereHas('role', function ($q) use ($roleName) {
                $q->whereName('Kabag Tata Usaha');
            })->first()->id;
        } elseif ($roleName == 'Kasie Inventaris' || $roleName == 'Kasie Pengadaan') {
            $kode = User::whereHas('role', function ($q) use ($roleName) {
                $q->whereName('Kabid Logistik');
            })->first()->id;
        } else {
            $kode = 'NONUSER';
        }
        return $kode;
    }

    public function bagianBidangUpp3()
    {
        $roleName = $this->user->role->name ?? null;
        if (
            $roleName == 'Kepala Distrik Navigasi' ||
            $roleName == 'Kabag Tata Usaha' ||
            $roleName == 'Subbag Kepegawaian dan Umum' ||
            $roleName == 'Subbag Keuangan'
        ) {
            $kode = 'TU';
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
            $kode = 'OPS';
        } else if (
            $roleName == 'Kabid Logistik' ||
            $roleName == 'Seksi Pengadaan' ||
            $roleName == 'Seksi Inventaris'
        ) {
            $kode = 'LOG';
        } else {
            $kode = 'NONUSER';
        }

        return $kode;
    }
}
