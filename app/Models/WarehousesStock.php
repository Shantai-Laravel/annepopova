<?php

namespace App\Models;

use App\Base as Model;

class WarehousesStock extends Model
{
    protected $table = 'warehouses_stocks';

    protected $fillable = ['warehouse_id', 'product_id', 'subproduct_id', 'stock'];

    public function warehouse()
    {
        return $this->hasOne(WareHouse::class, 'id', 'warehouse_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function subproduct()
    {
        return $this->hasOne(SubProduct::class, 'id', 'subproduct_id');
    }
}
