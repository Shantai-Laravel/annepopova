<?php

namespace App\Models;

use App\Base as Model;

class Returns extends Model
{
    protected $table = 'returns';

    protected $fillable = [
                'user_id',
                'guest_id',
                'order_id',
                'amount',
                'status',
                'delivery',
                'payment',
                'reason',
                'additional',
                'image',
                'datetime',
                'iban',
                'bank',
                'paypal_email'
            ];

    public function order()
    {
        return $this->hasOne(CRMOrders::class, 'id', 'order_id');
    }

    public function user()
    {
        return $this->hasOne(FrontUser::class, 'id', 'user_id');
    }

    public function guest()
    {
        return $this->hasOne(FrontUserUnlogged::class, 'id', 'guest_id');
    }

    public function items()
    {
        return $this->hasMany(CRMOrderItem::class, 'return_id', 'id');
    }
}
