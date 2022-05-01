<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovalRetur extends Model
{
    protected $table = 'approval_retur';
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class, 'approve_by_id', 'id');
    }
}
