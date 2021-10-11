<?php

namespace App\Models;

use App\Base as Model;

class SetTranslation extends Model
{
    protected $table = 'sets_translation';

    protected $fillable = [ 'lang_id', 'set_id', 'name', 'subtitle', 'description', 'image', 'seo_text', 'seo_title', 'seo_description', 'seo_keywords'];

    public function set()
    {
        return $this->belongsTo(Set::class);
    }
}
