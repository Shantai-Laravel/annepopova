<?php

namespace App\Models;

use App\Base as Model;

class PromocodeType extends Model
{
    protected $table = 'promocode_types';

    protected $fillable = [
        'name',
        'discount',
        'times',
        'valid_to'
    ];
}
