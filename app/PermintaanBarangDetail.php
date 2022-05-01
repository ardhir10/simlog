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
    public function barang_retur()
    {
        return $this->hasOne(ReturDetail::class, 'permintaan_barang_detail_id', 'id');
    }

    public function permintaanBarang()
    {
        return $this->belongsTo(PermintaanBarang::class, 'permintaan_barang_id', 'id');
    }

    public function barangDistribusi()
    {
        return $this->hasMany(BarangDistribusi::class, 'permintaan_barang_detail_id', 'id');
    }

    public function totalDistribusi(){
        return $totalDistribusi = BarangDistribusi::where('permintaan_barang_detail_id',$this->id)->get()->sum('jumlah');
    }
    

    public function distribusiSelesai(){
        $totalDistribusi = BarangDistribusi::where('permintaan_barang_detail_id', $this->id)->get()->sum('jumlah');
        if($totalDistribusi == $this->jumlah){
            return true;
        }
        return false;
    }

    public function barangBelumDistribusi(){
        $totalDistribusi = $this->totalDistribusi();
        $jumlahDisetujui = $this->jumlah_disetujui;
        return $jumlahDisetujui -$totalDistribusi;
    }
}
