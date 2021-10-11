<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;

class FrontUser extends Model implements \Illuminate\Contracts\Auth\Authenticatable
{
    use Authenticatable;

    protected $fillable = ['is_authorized', 'google', 'facebook', 'instagram', 'lang', 'name', 'surname', 'email', 'code', 'phone', 'birthday', 'terms_agreement', 'promo_agreement', 'personaldata_agreement', 'company', 'companyaddress', 'fisc', 'priorityaddress', 'password', 'spam', 'remember_token'];

    protected $hidden = ['password', 'remember_token'];

    public function addresses() {
        return $this->hasMany(FrontUserAddress::class);
    }

    public function priorityAddress() {
        return $this->hasOne(FrontUserAddress::class, 'id', 'priorityaddress');
    }
}
