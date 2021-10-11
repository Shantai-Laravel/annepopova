<?php

namespace App\Models;

use App\Base as Model;

class FrontUserUnlogged extends Model
{
    protected $table = 'front_users_unlogged';

    protected $fillable = ['user_id', 'name', 'phone', 'code', 'email'];

    public function addresses()
    {
        return $this->hasMany(FrontUserAddress::class, 'front_user_id', 'id');
    }
}
