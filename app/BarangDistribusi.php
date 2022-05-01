<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BarangDistribusi extends Model
{
    protected $table = 'barang_distribusi';
    protected $guarded = [];


    public function gambar(){
        return $this->hasOne(FileLaporanDistribusi::class,'barang_distribusi_id','id');
    }
}
