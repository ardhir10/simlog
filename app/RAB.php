<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RAB extends Model
{
    protected $table = 'rab';
    protected $guarded = [];

    public function detailRab(){
        return $this->hasMany(DetailRab::class,'rab_id','id');
    }
}
