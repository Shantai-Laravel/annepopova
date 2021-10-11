<?php
namespace App\Models;

use App\Base as Model;

class Cart extends Model
{
    protected $table = 'carts';

    protected $fillable = [
                        'parent_id',
                        'product_id',
                        'subproduct_id',
                        'user_id',
                        'set_id',
                        'qty',
                        'stock_qty',
                        'is_logged',
                        'active'
                    ];

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

    // public function cartSet()
    // {
    //     return $this->hasOne(CartSet::class, 'id', 'set_id');
    // }

    public function children()
    {
        return $this->hasMany(Cart::class, 'parent_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'id', 'product_id')->where(self::$warehouseName, 1);
    }

}
