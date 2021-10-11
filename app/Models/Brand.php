<?php
namespace App\Models;

use App\Base as Model;

class Brand extends Model
{
    protected $table = 'brands';

    protected $fillable = ['alias', 'image', 'logo', 'position', 'active'];

    public function translations()
    {
        return $this->hasMany(BrandTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(BrandTranslation::class , 'brand_id')->where('lang_id', self::$lang);
    }
}
