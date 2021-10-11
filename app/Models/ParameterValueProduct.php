<?php
namespace App\Models;

use App\Base as Model;

class ParameterValueProduct extends Model
{
    protected $table = 'parameters_values_products';

    protected $fillable = ['parameter_id', 'product_id', 'parameter_value_id'];

    public function translations()
    {
        return $this->hasMany(ParameterValueProductTranslation::class, 'param_val_id', 'id');
    }

    public function translation()
    {
        return $this->hasOne(ParameterValueProductTranslation::class , 'param_val_id')->where('lang_id', self::$lang);
    }

    public function transByLang($lang)
    {
        return $this->hasOne(ParameterValueProductTranslation::class , 'param_val_id')->where('lang_id', $lang);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parameters()
    {
        return $this->hasMany(Parameter::class);
    }

    public function parameter()
    {
        return $this->hasOne(Parameter::class, 'id', 'parameter_id');
    }

    public function value()
    {
        return $this->hasOne(ParameterValue::class, 'id', 'parameter_value_id');
    }

    public function parametersMain()
    {
        return $this->hasOne(Parameter::class, 'id', 'parameter_id')->where('main', '1');
    }

    public function parametersAdditional()
    {
        return $this->hasMany(Parameter::class, 'id', 'parameter_id')->where('main', '!=', '1');
    }
}
