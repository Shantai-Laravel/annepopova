<?php
namespace App\Models;

use App\Base as Model;

class AutoAlt extends Model
{
    protected $table = 'autoalts';

    protected $fillable = ['cat_id', 'keywords', 'lang_id'];
}
