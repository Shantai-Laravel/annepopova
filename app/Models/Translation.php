<?php
namespace App\Models;

use App\Base as Model;

class Translation extends Model
{
    protected $table = 'translations';

    protected $fillable = ['group_id', 'key', 'comment'];

    public function lines()
    {
        return $this->hasMany(TranslationLine::class, 'translation_id');
    }

    public function linesByLang($langId)
    {
        return $this->hasMany(TranslationLine::class, 'translation_id')->where('lang_id', $langId);
    }

    public function translation()
    {
        return $this->hasOne(TranslationLine::class, 'translation_id')->where('lang_id', self::$lang);
    }

    public function translations()
    {
        return $this->hasMany(TranslationLine::class, 'translation_id');
    }
}
