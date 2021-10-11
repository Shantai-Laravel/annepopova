<?php

namespace App\Models;

use App\Base as Model;

class Promotion extends Model
{
    protected $table = 'promotions';

    protected $fillable = [
        'alias',
        'img',
        'img_mobile',
        'discount',
        'homewear',
        'bijoux'
    ];

    public function translations()
    {
        return $this->hasMany(PromotionTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(PromotionTranslation::class, 'promotion_id')->where('lang_id', self::$lang);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'promotion_id', 'id');
    }

}
