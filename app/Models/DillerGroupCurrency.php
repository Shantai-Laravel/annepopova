<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;

class DillerGroupCurrency extends Model
{
    protected $table = "diller_group_currencies";

    protected $fillable = ['diller_group_id', 'currency_id'];

    public function currencies()
    {
        return $this->hasMany(Currency::class, 'id', 'currency_id');
    }
}
