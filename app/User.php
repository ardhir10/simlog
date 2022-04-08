<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{

    protected $table = 'users';
    use Notifiable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     // 'name', 'email', 'password','username', 'fs_avatar'
    // ];
    protected $guarded = [
        // 'name', 'email', 'password','username', 'fs_avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


     public function typeUser(){
         if($this->type_user == 'typeInternalUser'){
            return 'Internal';

         }elseif($this->type_user == 'typeBadanUsahaPemilik'){
             return 'Eksternal';
         }else{
            return 'N/A';
         }
     }


    public function role(){
        return $this->belongsTo(Role::class);
    }
    public function liniUsaha(){
        return $this->belongsTo(LiniUsaha::class);
    }

    public function kapalNegara()
    {
        return $this->belongsTo(KapalNegara::class,'kapal_negara_id','id');
    }

    public function srop()
    {
        return $this->belongsTo(StasiunRadioPantai::class, 'srop_id', 'id');
    }


}
