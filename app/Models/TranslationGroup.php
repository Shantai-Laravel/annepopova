<?php
namespace App\Models;

use App\Base as Model;

class TranslationGroup extends Model
{
    protected $table = 'translation_groups';

    protected $fillable = ['key', 'comment'];

    public function translations()
    {
        return $this->hasMany(Translation::class, 'group_id')->orderBy('id', 'desc');
    }
}
