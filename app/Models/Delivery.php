<?php

namespace App\Models;

use App\Base as Model;

class Delivery extends Model
{
    protected $table = 'deliveries';

    protected $fillable = ['alias', 'price', 'delivery_time'];

    public function translations()
    {
        return $this->hasMany(DeliveryTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(DeliveryTranslation::class)->where('lang_id', self::$lang);
    }

    public function countries()
    {
        return $this->hasMany(CountryDelivery::class, 'delivery_id');
    }
}
