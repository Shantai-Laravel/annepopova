<?php

namespace App\Models;

use App\Base as Model;

class ProductImageTranslation extends Model
{
    protected $fillable = ['lang_id', 'product_image_id', 'alt', 'title'];

    protected $table = 'product_images_translation';

    public function image()
    {
        return $this->belongsTo(ProductImage::class);
    }
}
