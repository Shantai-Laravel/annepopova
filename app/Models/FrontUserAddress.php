<?php

namespace App\Models;

use App\Base as Model;

class FrontUserAddress extends Model
{
    protected $fillable = [
                            'front_user_id',
                            'addressname',
                            'country',
                            'region',
                            'location',
                            'address',
                            'code',
                            'homenumber',
                            'entrance',
                            'floor',
                            'apartment',
                            'phone_code',
                            'phone',
                            'comment'
                        ];

    public function user() {
        return $this->hasOne(FrontUser::class);
    }

    public function userUnlogged() {
        return $this->hasOne(FrontUserUnlogged::class);
    }

    public function getCountryById() {
        return $this->hasOne(Country::class, 'id', 'country');
    }

    public function getRegionById() {
        return $this->hasOne(Region::class, 'id', 'region');
    }

    public function getCityById() {
        return $this->hasOne(City::class, 'id', 'location');
    }
}
