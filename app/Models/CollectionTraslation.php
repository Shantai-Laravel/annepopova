<?php
namespace App\Models;

use App\Base as Model;

class CollectionTranslation extends Model
{
    protected $table = 'collections_translation';

    protected $fillable = [
                        'lang_id',
                        'collection_id',
                        'name',
                        'subtitle',
                        'description',
                        'body',
                        'image',
                        'seo_text',
                        'seo_title',
                        'seo_description',
                        'seo_keywords'
                    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }
}
