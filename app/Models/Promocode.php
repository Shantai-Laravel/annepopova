<?php

namespace App\Models;

use App\Base as Model;

class Promocode extends Model
{
    protected $table = 'promocodes';

    protected $fillable = [
        'name',
        'type_id',
        'discount',
        'valid_from',
        'valid_to',
        'period',
        'treshold',
        'to_use',
        'times',
        'status',
        'user_id'
    ];

    public function type()
    {
        return $this->hasOne(PromocodeType::class, 'id', 'type_id');
    }

    public function user()
    {
        return $this->hasOne(FrontUser::class, 'id', 'user_id');
    }
}
