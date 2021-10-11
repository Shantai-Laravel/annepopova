<?php

namespace App\Models;

use App\Base as Model;

class ProductImage extends Model
{
    protected $table = 'product_images';

    protected $fillable = ['product_id', 'src', 'href', 'main', 'first', 'type'];

    public function translations()
    {
        return $this->hasMany(ProductImageTranslation::class);
    }

    public function translation()
    {
        return $this->hasMany(ProductImageTranslation::class)->where('lang_id', self::$lang);
    }

    public function translationByLanguage($lang = 1)
    {
        return $this->hasOne(ProductImageTranslation::class)->where('lang_id', $lang)->first();
    }

}
