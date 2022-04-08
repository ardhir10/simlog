<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class BarangPersediaan extends Model
{
    use Notifiable, SoftDeletes;

    protected $table = 'barang_persediaan';
    protected $guarded = [];

    public function kategori_barang(){
        return $this->belongsTo(KategoriBarang::class,'kategori_barang_id','id');
    }
    public function satuan(){
        return $this->belongsTo(Satuan::class,'satuan_id','id');
    }
}
