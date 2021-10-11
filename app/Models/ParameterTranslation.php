<?php
namespace App\Models;

use App\Base as Model;

class ParameterTranslation extends Model
{
    protected $table = 'parameters_translation';

    protected $fillable = ['lang_id', 'parameter_id', 'name', 'unit'];

    public function parameter()
    {
        return $this->belongsTo(Parameter::class);
    }
}
