<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LaporanDistribusi extends Model
{
    protected $table = 'laporan_distribusi';
    protected $guarded = [];

    public function fileDistribusi()
    {
        return $this->hasMany(FileLaporanDistribusi::class, 'laporan_distribusi_id', 'id');
    }
}
