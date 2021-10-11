<?php

namespace App\Models;

use App\Base as Model;

class ProductCollection extends Model
{
    protected $table = 'product_collection';

    protected $fillable = [
            'product_id',
            'collection_id',
    ];

    public function product()
    {
        return $this->hasOne(Product::class);
    }

    public function collection()
    {
        return $this->hasOne(Collection::class);
    }

}
