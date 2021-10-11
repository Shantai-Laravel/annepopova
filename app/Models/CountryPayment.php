<?php

namespace App\Models;

use App\Base as Model;

class CountryPayment extends Model
{
    protected $table = 'country_payments';

    protected $fillable = [
                'country_id',
                'payment_id',
            ];

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id')->first();
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
