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
    public function stokBarang(){
        $BarangMasuk = BarangMasuk::where('barang_id',$this->id)->get()->sum('jumlah');
        $Barangkeluar = BarangKeluar::where('barang_keluar_id',$this->id)->get()->sum('jumlah');
        $BarangRetur = ReturDetail::where('barang_id', $this->id)->where('status', 'done')->get()->sum('jumlah_retur') ?? 0;
        return $stock = $BarangMasuk-$Barangkeluar+$BarangRetur;
    }

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class, 'barang_id', 'id');
    }

    public function barangRetur()
    {
        return $totalBarangRetur = ReturDetail::where('barang_id',$this->id)->where('status','done')->get()->sum('jumlah_retur') ?? 0;
    }

    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'barang_keluar_id', 'id');
    }

    public function peruntukkan(){
        // PENGGUNANYA
        switch ($this->sub_sub_kategori) {
            case '01':
                return 'Umum';
                break;
            case '02':
                return 'Sie Kepeg & Umum';
                break;
            case '03':
                return 'Sie Keuangan';
                break;
            case '04':
                return 'Sie Pengadaan';
                break;
            case '05':
                return 'Sie Inventaris';
                break;
            case '06':
                return 'SieSarPras';
                break;
            case '07':
                return 'Sie Program & Evaluasi';
                break;
            case '08':
                return 'SBNP';
                break;
            case '09':
                return 'Telkompel';
                break;
            case '10':
                return 'Pengla';
                break;
            case '11':
                return 'KNK';
                break;
            case '12':
                return 'Bengkel';
                break;
            default:
                return '';
                break;
        }

    }

    public function satuan(){
        return $this->belongsTo(Satuan::class,'satuan_id','id');
    }
}
