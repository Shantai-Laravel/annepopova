<?php

namespace App\Models;

use App\Base as Model;

class ProductBrand extends Model
{
    protected $table = 'product_brands';

    protected $fillable = [
            'product_id',
            'brand_id',
    ];

    public function product()
    {
        return $this->hasOne(Product::class);
    }

    public function brand()
    {
        return $this->hasOne(Brand::class);
    }

}
