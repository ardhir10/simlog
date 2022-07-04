<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RencanaKebutuhanDetail extends Model
{
    protected $table = 'rencana_kebutuhan_detail';
    protected $guarded = [];

    public function barang(){
        return $this->belongsTo(BarangPersediaan::class,'barang_id','id');
    }
}
