<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovalRab extends Model
{
    protected $table = 'approval_rab';
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class, 'approve_by_id', 'id');
    }
}
