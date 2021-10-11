<?php
namespace App\Models;

use App\Base as Model;

class ParameterValueTranslation extends Model
{
    protected $table = 'parameter_values_translation';

    protected $fillable = ['lang_id', 'parameter_value_id', 'name'];

    public function parameterValue()
    {
        return $this->belongsTo(ParameterValue::class);
    }
}
