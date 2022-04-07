<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BarangPersediaan extends Model
{
    protected $table = 'barang_persediaan';
    protected $guarded = [];

    public function kategori_barang(){
        return $this->belongsTo(KategoriBarang::class,'kategori_barang_id','id');
    }
    public function satuan(){
        return $this->belongsTo(Satuan::class,'satuan_id','id');
    }
}
