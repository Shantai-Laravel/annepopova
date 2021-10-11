<?php
namespace App\Models;

use App\Base as Model;

class ProductMaterial extends Model
{
    protected $fillable = ['product_id', 'material_id'];

    protected $table = 'product_materials';

    public function products()
    {
        return $this->hasMany(Product::class, 'id', 'product_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'material_id');
    }

    public function materials()
    {
        return $this->hasMany(Product::class, 'id', 'material_id');
    }
}
