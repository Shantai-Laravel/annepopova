<?php

namespace App\Models;

use App\Base as Model;

class Banner extends Model
{
    protected $fillable = [
                'key',
                'desktop_src',
                'mobile_src',
                'desktop_width_size',
                'desktop_height_size',
                'mobile_width_size',
                'mobile_height_size',
            ];
}
