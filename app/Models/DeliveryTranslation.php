<?php

namespace App\Models;

use App\Base as Model;

class DeliveryTranslation extends Model
{
    protected $table = 'deliveries_translation';

    protected $fillable = [
                'lang_id',
                'delivery_id',
                'name',
            ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }
}
