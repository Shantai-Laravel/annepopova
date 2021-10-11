<?php

namespace App\Models;

use App\Base as Model;

class ProductDillerPrice extends Model
{
    protected $table = 'product_diller_prices';

    protected $fillable = ['product_id', 'diller_group_id', 'currency_id', 'old_price', 'price', 'dependable'];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }

    public function dillerGroup()
    {
        return $this->hasOne(DillerGroup::class, 'id', 'diller_group_id');
    }

}
