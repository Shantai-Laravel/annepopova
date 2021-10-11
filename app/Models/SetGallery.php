<?php

namespace App\Models;

use App\Base as Model;

class SetGallery extends Model
{
    protected $table = 'sets_gallery';

    protected $fillable = [
        'set_id', 'type', 'src', 'main'
    ];

    public function translations()
    {
        return $this->hasMany(SetTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(SetTranslation::class, 'set_id')->where('lang_id', self::$lang);
    }
}
