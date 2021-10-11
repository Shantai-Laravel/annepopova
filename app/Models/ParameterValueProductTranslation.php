<?php
namespace App\Models;

use App\Base as Model;

class ParameterValueProductTranslation extends Model
{
    protected $table = 'parameters_values_products_translation';

    protected $fillable = ['lang_id', 'param_val_id', 'value'];

    public function paramValProd()
    {
        return $this->belongsTo(ParameterValueProduct::class);
    }
}
