<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RencanaKebutuhan extends Model
{
    protected $table = 'rencana_kebutuhan';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function lastProcess($role = '')
    {
        if ($role) {
            return $process = ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $this->id)
                ->where('role_to_name', $role)
                ->orderBy('id', 'desc')
                ->first();
        } else {
            return $process = ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $this->id)
                ->orderBy('id', 'desc')
                ->first();
        }
    }

    public function timeline()
    {
        return $this->hasMany(ApprovalRencanaKebutuhanProcess::class, 'rencana_kebutuhan_id', 'id');
    }

    public function barang_diminta()
    {
        return $this->hasMany(RencanaKebutuhanDetail::class, 'rencana_kebutuhan_id', 'id');
    }

    public function approvals()
    {
        return $this->hasMany(ApprovalRencanaKebutuhanProcess::class, 'rencana_kebutuhan_id', 'id');
    }
    public function pengguna()
    {

        // PENGGUNANYA
        switch ($this->pengguna) {
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
        return $this->pengguna;
    }

    public function fromKadisnav()
    {
        return $process = ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $this->id)
            ->where('from_kadisnav', '!=', null)
            ->orderBy('id', 'desc')
            ->first()->from_kadisnav ?? null;
    }
    public function lastApproval()
    {
        return $process = ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $this->id)
            ->where('kategori', 'PERSETUJUAN')
            ->orderBy('id', 'desc')
            ->first();
    }
    public function isNeedApprove()
    {
        return $process = ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $this->id)
            ->where('kategori', 'APPROVAL')
            ->where('status', '!=', 'done')
            ->orderBy('id', 'desc')
            ->first();
    }
    public function isNeedApproveDisposisi()
    {
        return $process = ApprovalRencanaKebutuhanProcess::where('rencana_kebutuhan_id', $this->id)
            ->where('kategori', 'DISPOSISI')
            ->where('status', '!=', 'done')
            ->orderBy('id', 'desc')
            ->first();
    }




}
