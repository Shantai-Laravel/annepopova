<?php
namespace App\Models;

use App\Base as Model;

class ParameterValue extends Model
{
    protected $table = 'parameter_values';

    protected $fillable = ['parameter_id', 'image', 'suffix'];

    public function translations()
    {
        return $this->hasMany(ParameterValueTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(ParameterValueTranslation::class , 'parameter_value_id')->where('lang_id', self::$lang);
    }

    public function translationByLang($lang)
    {
        return $this->hasOne(ParameterValueTranslation::class , 'parameter_value_id')->where('lang_id', $lang)->first();
    }

    public function transData()
    {
        return $this->hasOne(ParameterValueTranslation::class , 'parameter_value_id');
    }

    public function parameter()
    {
        return $this->hasOne(Parameter::class , 'id', 'parameter_id');
    }
}
