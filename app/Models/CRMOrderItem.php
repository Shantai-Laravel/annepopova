<?php

namespace App\Models;

use App\Base as Model;

class CRMOrderItem extends Model
{
    protected $table = 'crm_order_items';

    protected $fillable = [
                'order_id',
                'parent_id',
                'set_id',
                'product_id',
                'subproduct_id',
                'qty',
                'code',
                'discount',
                'old_price',
                'price',
                'currency',
                'img',
                'details',
                'deleted',
                'return_id',
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

    public function children()
    {
        return $this->hasMany(CRMOrderItem::class, 'parent_id', 'id');
    }
}
