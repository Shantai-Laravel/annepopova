<?php

namespace App\Models;

use App\Base as Model;

class ProductTranslation extends Model
{
    protected $table = 'products_translation';

    protected $fillable = [
                    'lang_id',
                    'product_id',
                    'name',
                    'description',
                    'body',
                    'atributes',
                    'aditionall',
                    'seo_title',
                    'seo_keywords',
                    'seo_description',
                ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function lang()
    {
        return $this->lang(Lang::class, 'id', 'lang_id');
    }
}
