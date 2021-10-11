<?php

namespace App\Models;

use App\Base as Model;
use App\Models\Lang;

class Module extends Model
{
    protected $fillable = [
        'src', 'controller', 'position', 'table_name', 'icon', 'parent_id', 'on_dashboard'
    ];

    public function parent()
    {
        return $this->belongsTo(Module::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Module::class, 'parent_id');
    }
}
