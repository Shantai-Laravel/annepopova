<?php

namespace App\Models;

use App\Base as Model;

class SetProducts extends Model
{
    protected $table = 'set_product';

    protected $fillable = [ 'set_id', 'product_id', 'src', 'position'];

    public function set()
    {
        return $this->hasOne(Set::class, 'id', 'set_id');
    }
}
