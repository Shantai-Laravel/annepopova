<?php

namespace App\Models;

use App\Base as Model;

class CRMOrderDetail extends Model
{
    protected $table = 'crm_order_details';

    protected $fillable = [
                'order_id',
                'contact_name',
                'email',
                'phone',
                'code',
                'promocode',
                'currency',
                'payment',
                'delivery',
                'country',
                'region',
                'city',
                'address',
                'apartment',
                'zip',
                'delivery_price',
                'tax_price',
                'comment',
            ];

}
