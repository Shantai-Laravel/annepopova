<?php

namespace App\Models;

use App\Base as Model;

class WishListSet extends Model
{
    protected $table = 'wishlist_sets';

    protected $fillable = ['set_id', 'user_id', 'is_logged'];

    public function set()
    {
        return $this->hasOne(Set::class, 'id', 'set_id');
    }

    public function wishlist()
    {
        return $this->hasMany(WishList::class, 'set_id', 'id');
    }

}
