<?php

namespace App\Models;

use App\Base as Model;

class Country extends Model
{
    protected $table = 'countries';

    protected $fillable = [
                'name',
                'iso',
                'iso3',
                'num_code',
                'phone_code',
                'flag',
                'vat',
                'active',
                'main',
                'lang_id',
                'currency_id',
                'warehouse_id',
            ];

    public function translations()
    {
        return $this->hasMany(CountryTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(CountryTranslation::class)->where('lang_id', self::$lang);
    }

    public function lang()
    {
        return $this->hasOne(Lang::class, 'id', 'lang_id');
    }

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }

    public function deliveries()
    {
        return $this->hasMany(CountryDelivery::class);
    }

    public function mainDelivery()
    {
        return $this->hasOne(CountryDelivery::class);
    }

    public function payments()
    {
        return $this->hasMany(CountryPayment::class);
    }
}
