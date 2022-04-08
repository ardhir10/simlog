<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermintaanBarangDetail extends Model
{
    protected $table = 'permintaan_barang_detail';
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo(BarangPersediaan::class, 'barang_persediaan_id', 'id');
    }
}
