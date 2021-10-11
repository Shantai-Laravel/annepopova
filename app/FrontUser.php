<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class FrontUser extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'front_users';

    protected $fillable = [
                    'lang_id',
                    'country_id',
                    'currency_id',
                    'payment_id',
                    'active_diller',
                    'customer_type',
                    'diller_group_id',
                    'is_authorized',
                    'google',
                    'facebook',
                    'name',
                    'email',
                    'phone',
                    'company',
                    'password',
                    'remember_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function promocodes()
    {
        return $this->hasMany(Models\Promocode::class, 'user_id', 'id')->where('status', 'valid');
    }

    public function country()
    {
        return $this->hasOne(Models\Country::class, 'id', 'country_id');
    }

    public function lang()
    {
        return $this->hasOne(Models\Lang::class, 'id', 'lang_id');
    }

    public function currency()
    {
        return $this->hasOne(Models\Currency::class, 'id', 'currency_id');
    }

    public function address()
    {
        return $this->hasOne(Models\FrontUserAddress::class, 'front_user_id', 'id');
    }
}
