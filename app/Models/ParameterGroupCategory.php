<?php

namespace App\Models;

use App\Base as Model;

class ParameterGroupCategory extends Model
{
    protected $table = 'parameter_groups_categories';

    protected $fillable = [
                    'category_id',
                    'group_id',
                ];

    public function group()
    {
        return $this->hasOne(ParameterGroup::class, 'id', 'group_id');
    }

    public function category()
    {
        return $this->hasOne(ProductCategory::class, 'id', 'category_id');
    }
}
