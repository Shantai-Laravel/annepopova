<?php
namespace App\Models;

use App\Base as Model;

class ParameterGroup extends Model
{
    protected $table = 'parameter_groups';

    protected $fillable = ['translation_group_id', 'key'];

    public function key()
    {
        return $this->hasOne(TranslationGroup::class , 'id', 'translation_group_id');
    }

    public function parmeters()
    {
        return $this->hasMany(Parameter::class, 'group_id', 'id');
    }
}
