<?php

namespace App\Models;

use App\Base as Model;

class CurrencyTranslation extends Model
{
    protected $table = 'currencies_translation';

    protected $fillable = [
                'lang_id',
                'currency_id',
                'name',
            ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
