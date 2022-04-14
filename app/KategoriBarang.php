<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    protected $table = 'kategori_barang';
    protected $guarded = [];

    public function parentName(){
        return KategoriBarang::where('id',$this->parent_id)
        ->first()->nama_kategori ?? null;

    }
}
