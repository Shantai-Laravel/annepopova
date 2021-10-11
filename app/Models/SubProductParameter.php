<?php

namespace App\Models;

use App\Base as Model;

class SubProductParameter extends Model
{
    protected $table = 'subproduct_parameters';

    protected $fillable = ['category_id', 'parameter_id'];


    public function parameter()
    {
        return $this->hasOne(Parameter::class, 'id', 'parameter_id');
    }

    public function category()
    {
        return $this->hasOne(ProductCategory::class, 'id', 'category_id');
    }

    public function property()
    {
        return $this->hasOne(Parameter::class, 'id', 'parameter_id');
    }

}
