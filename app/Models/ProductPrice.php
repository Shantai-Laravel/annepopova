<?php

namespace App\Models;

use App\Base as Model;

class ProductPrice extends Model
{
    protected $table = 'product_prices';

    protected $fillable = ['product_id', 'currency_id', 'old_price', 'price', 'b2b_old_price', 'b2b_price', 'dependable'];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }
}
