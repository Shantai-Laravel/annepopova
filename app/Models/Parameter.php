<?php
namespace App\Models;

use App\Base as Model;

class Parameter extends Model
{
    protected $table = 'parameters';

    protected $fillable = ['type', 'key', 'in_filter', 'multilingual', 'multilingual_title', 'multilingual_unit', 'main', 'group_id', 'position'];

    public function translations()
    {
        return $this->hasMany(ParameterTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(ParameterTranslation::class, 'parameter_id')->where('lang_id', self::$lang);
    }

    public function translationByLang($lang)
    {
        return $this->hasOne(ParameterTranslation::class, 'parameter_id')->where('lang_id', $lang)->first();
    }

    public function transData()
    {
        return $this->hasOne(ParameterTranslation::class, 'parameter_id');
    }

    public function parameterValues()
    {
        return $this->hasMany(ParameterValue::class, 'parameter_id');
    }

    public function categories()
    {
        return $this->hasMany(ParameterCategory::class, 'parameter_id');
    }

    public function pramProduct($parameterId)
    {
        return $this->hasOne(ParameterValueProduct::class, 'parameter_id')->orderBy('id', 'desc')->where('product_id', $parameterId);
    }
}
