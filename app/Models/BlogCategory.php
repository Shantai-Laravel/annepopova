<?php

namespace App\Models;

use App\Base as Model;

class BlogCategory extends Model
{
    protected $table = 'blog_categories';

    protected $fillable = [
                        'parent_id',
                        'alias',
                        'level',
                        'position',
                        'succesion',
                        'on_home',
                        'active',
                        'icon',
                        'product_type'
                    ];

    public function translations()
    {
        return $this->hasMany(BlogCategoryTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(BlogCategoryTranslation::class)->where('lang_id', self::$lang);
    }

    public function translationByLang($lang)
    {
        return $this->hasOne(BlogCategoryTranslation::class)->where('lang_id', $lang);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'category_id');
    }

    public function parent()
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(BlogCategory::class, 'parent_id')->orderBy('position', 'asc') ->orderBy('created_at', 'desc');
    }

}
