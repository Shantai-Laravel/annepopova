<?php

namespace App\Models;

use App\Base as Model;

class Currency extends Model
{
    protected $table = 'currencies';

    protected $fillable = [
                'abbr',
                'type',
                'rate',
                'active',
                'exchange_dependable',
                'correction_factor'
            ];

    public function translations()
    {
        return $this->hasMany(CurrencyTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(CurrencyTranslation::class)->where('lang_id', self::$lang);
    }

    public function countries()
    {
        return $this->hasMany(Country::class, 'currency_id', 'id');
    }
}
