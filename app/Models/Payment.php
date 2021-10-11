<?php

namespace App\Models;

use App\Base as Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = ['alias'];

    public function translations()
    {
        return $this->hasMany(PaymentTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(PaymentTranslation::class)->where('lang_id', self::$lang);
    }

    public function countries()
    {
        return $this->hasMany(CountryPayment::class, 'payment_id');
    }
}
