<?php

namespace App\Models;

use App\Base as Model;

class WareHouse extends Model
{
    protected $table = 'warehouses';

    protected $fillable = ['name', 'default', 'active'];

}
