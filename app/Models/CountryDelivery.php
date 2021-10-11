<?php

namespace App\Models;

use App\Base as Model;

class CountryDelivery extends Model
{
    protected $table = 'country_deliveries';

    protected $fillable = [
                'country_id',
                'delivery_id',
                'price',
                'inherit_price',
                'delivery_time',
            ];

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
}
