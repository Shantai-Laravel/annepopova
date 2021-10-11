<?php

namespace App\Models;

use App\Base as Model;

class Lang extends Model
{
    protected $table = 'langs';

    protected $fillable = [ 'lang', 'descr', 'default', 'position', 'active'];

    public function countries()
    {
        return $this->hasMany(Country::class, 'lang_id', 'id');
    }
}
