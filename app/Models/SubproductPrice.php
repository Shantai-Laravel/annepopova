<?php

namespace App\Models;

use App\Base as Model;

class SubproductPrice extends Model
{
    protected $table = 'subproduct_prices';

    protected $fillable = ['subproduct_id', 'currency_id', 'old_price', 'price', 'dependable'];

    public function subproduct()
    {
        return $this->hasOne(Subproduct::class, 'id', 'subproduct_id');
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }

}
