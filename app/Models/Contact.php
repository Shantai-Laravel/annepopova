<?php
namespace App\Models;

use App\Base as Model;

class Contact extends Model
{
    protected $table = 'contacts';

    protected $fillable = ['title'];

    public function translations()
    {
        return $this->hasMany(ContactTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(ContactTranslation::class)->where('lang_id', self::$lang)->first();
    }

    public function translationByLanguage($lang = 1)
    {
        return $this->hasMany(ContactTranslation::class)->where('lang_id', $lang);
    }
}
