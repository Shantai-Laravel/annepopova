<?php

namespace App\Models;

use App\Base as Model;

class Page extends Model
{
    protected $table = 'pages';

    protected $fillable = [
                    'alias',
                    'image',
                    'gallery_id',
                    'position',
                    'active',
                    'on_header',
                    'on_drop_down',
                    'on_footer'
                ];

    public function gallery()
    {
        return $this->hasOne(Gallery::class, 'id', 'gallery_id');
    }

    public function translations()
    {
        return $this->hasMany(PageTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(PageTranslation::class, 'page_id')->where('lang_id', self::$lang);
    }

}
