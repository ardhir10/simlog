<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovalProcess extends Model
{
    protected $table = 'approval_process';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'approve_by_id', 'id');
    }
}
