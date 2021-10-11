<?php
namespace App\Models;

use App\Base as Model;

class BrandTranslation extends Model
{
    protected $table = 'brands_translation';

    protected $fillable = ['lang_id', 'brand_id', 'name', 'description', 'body', 'banner', 'seo_text', 'seo_title', 'seo_description', 'seo_keywords'];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
