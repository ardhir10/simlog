<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturDetail extends Model
{
    protected $table = 'retur_detail';
    protected $guarded = [];

    public function barang(){
        return $this->belongsTo(BarangPersediaan::class,'barang_id','id');
    }
    public function permintaanBarang(){
        return $this->belongsTo(PermintaanBarang::class,'permintaan_barang_id','id');
    }
}
