<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'admin_user';

    protected $fillable = [
        'name', 'email', 'password', 'login', 'remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function group()
    {
        return $this->hasOne('App\Models\AdminUserGroup', 'id', 'admin_user_group_id');
    }

}
