<?php

namespace App\Models;

use App\Base as Model;

class PromotionTranslation extends Model
{
    protected $table = 'promotions_translation';

    protected $fillable = [
        'lang_id',
        'promotion_id',
        'name',
        'description',
        'body',
        'btn_text',
        'banner',
        'banner_mob',
        'seo_text',
        'seo_title',
        'seo_description',
        'seo_keywords'
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}
