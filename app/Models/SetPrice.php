<?php

namespace App\Models;

use App\Base as Model;

class SetPrice extends Model
{
    protected $table = 'set_prices';

    protected $fillable = ['set_id', 'currency_id', 'old_price', 'price', 'dependable'];

    public function set()
    {
        return $this->hasOne(Set::class);
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }

}
