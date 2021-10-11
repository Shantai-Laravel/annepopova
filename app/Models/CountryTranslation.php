<?php

namespace App\Models;

use App\Base as Model;

class CountryTranslation extends Model
{
    protected $table = 'countries_translation';

    protected $fillable = [
                        'lang_id',
                        'country_id',
                        'name',
                    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
