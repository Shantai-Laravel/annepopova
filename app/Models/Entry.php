<?php

namespace App\Models;

use App\Base as Model;

class Entry extends Model
{
    protected $fillable = [
                'ip',
                'country',
                'date',
                'url',
            ];
}
