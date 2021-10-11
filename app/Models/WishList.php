<?php

namespace App\Models;

use App\Base as Model;

class WishList extends Model
{
    protected $table = 'wishlist';

    protected $fillable = ['product_id', 'subproduct_id', 'user_id', 'is_logged', 'set_id'];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function subproduct()
    {
        return $this->hasOne(SubProduct::class, 'id', 'subproduct_id');
    }

    public function set()
    {
        return $this->hasOne(Set::class, 'id', 'set_id');
    }

}
