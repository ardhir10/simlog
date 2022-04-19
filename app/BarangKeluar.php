<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $table = 'barang_keluar';
    protected $guarded = [];

    public function permintaan(){
        return $this->belongsTo(PermintaanBarang::class,'permintaan_id','id');
    }

}
