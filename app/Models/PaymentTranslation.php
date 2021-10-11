<?php

namespace App\Models;

use App\Base as Model;

class PaymentTranslation extends Model
{
    protected $table = 'payments_translation';

    protected $fillable = [
                'lang_id',
                'payment_id',
                'name',
            ];

    public function delivery()
    {
        return $this->belongsTo(Payment::class);
    }
}
