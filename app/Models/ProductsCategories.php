<?php
namespace App\Models;

use App\Base as Model;

class ProductsCategories extends Model
{
    protected $table = 'categories_products';

    protected $fillable = ['product_id', 'category_id'];

    public function products()
    {
        return $this->hasMany(Product::class, 'id', 'product_id')->where(self::$warehouseName, 1);
    }

    public function categories()
    {
        return $this->hasMany(ProductCategory::class, 'id', 'category_id');
    }
}
