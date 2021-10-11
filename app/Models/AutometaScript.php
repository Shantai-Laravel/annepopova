<?php

namespace App\Models;

use App\Base as Model;

class AutometaScript extends Model
{
    protected $table = 'autometa_scripts';

    protected $fillable = [ 'name', 'type' ];

    public function translations()
    {
        return $this->hasMany(AutometaScriptTranslation::class, 'script_id', 'id');
    }

    public function translation()
    {
        return $this->hasOne(AutometaScriptTranslation::class, 'script_id', 'id')->where('lang_id', self::$lang);
    }

    public function translationByLang($lang)
    {
        return $this->hasOne(AutometaScriptTranslation::class, 'script_id', 'id')->where('lang_id', $lang);
    }
}
