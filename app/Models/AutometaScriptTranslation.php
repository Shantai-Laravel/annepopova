<?php

namespace App\Models;

use App\Base as Model;

class AutometaScriptTranslation extends Model
{
    protected $table = 'autometa_scripts_translation';

    protected $fillable = [
                    'lang_id',
                    'script_id',
                    'var1',
                    'var2',
                    'var3',
                    'var4',
                    'var5',
                    'var6',
                    'var7',
                    'var8',
                    'var9',
                    'var10',
                    'var11',
                    'var12',
                    'var13',
                    'var14',
                    'var15',
                    'description',
                    'meta_title',
                    'meta_description',
                    'meta_keywords',
                ];

    public function product()
    {
        return $this->belongsTo(AutometaScript::class, 'id', 'script_id');
    }
}
