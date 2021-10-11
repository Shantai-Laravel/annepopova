<?php
namespace App\Models;

use App\Base as Model;

class ParameterCategory extends Model
{
    protected $table = 'parameter_categories';

    protected $fillable = ['parameter_id', 'category_id', 'position'];

    public function parameters()
    {
        return $this->hasMany(Parameter::class, 'id', 'parameter_id');
    }

    public function property()
    {
        return $this->hasOne(Parameter::class, 'id', 'parameter_id');
    }

    public function categories()
    {
        return $this->hasMany(ProductCategories::class, 'category_id');
    }
}
