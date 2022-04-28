<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturBarang extends Model
{
    protected $table = 'retur_barang';
    protected $guarded = [];

    public function retur_detail()
    {
        return $this->hasMany(ReturDetail::class, 'retur_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function lastProcess($role = '')
    {
        if ($role) {
            return $process = ApprovalRetur::where('retur_id', $this->id)
                ->where('role_to_name', $role)
                ->orderBy('id', 'desc')
                ->first();
        } else {
            return $process = ApprovalRetur::where('retur_id', $this->id)
                ->orderBy('id', 'desc')
                ->first();
        }
    }

    public function timeline()
    {
        return $this->hasMany(ApprovalRetur::class, 'retur_id', 'id');
    }

    public function approvals()
    {
        return $this->hasMany(ApprovalRetur::class, 'retur_id', 'id');
    }
}
