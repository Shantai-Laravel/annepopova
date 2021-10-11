<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;

class DillerGroup extends Model
{
    protected $fillable = ['name', 'type', 'discount', 'applied_on'];

    public function dillers()
    {
        return $this->hasMany(FrontUser::class, 'diller_group', 'id');
    }

    public function groupCurencies()
    {
        return $this->hasMany(DillerGroupCurrency::class, 'diller_group_id', 'id');
    }

    public function prices()
    {
        return $this->hasMany(ProductDillerPrice::class, 'diller_group_id', 'id');
    }

    public function pricesByProduct($productId)
    {
        return $this->hasMany(ProductDillerPrice::class, 'diller_group_id', 'id')->where('product_id', $productId);
    }
}
