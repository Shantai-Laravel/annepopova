<?php

namespace App\Models;

use App\Base as Model;

class CRMOrders extends Model
{
    protected $table = 'crm_orders';

    protected $fillable = [
                'order_hash',
                'order_reference',
                'frisbo_reference',
                'user_id',
                'guest_user_id',
                'address_id',
                'promocode_id',
                'currency_id',
                'payment_id',
                'delivery_id',
                'country_id',
                'amount',
                'shipping_price',
                'discount',
                'amount',
                'step',
                'label',
                'main_status',
                'secondary_status',
                'tracking_link',
                'invoice_file_en',
                'invoice_file',
                'change_status_at',
            ];

    public function details()
    {
        return $this->hasOne(CRMOrderDetail::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(FrontUser::class, 'id', 'user_id');
    }

    public function guest()
    {
        return $this->hasOne(FrontUserUnlogged::class, 'id', 'guest_user_id');
    }

    public function deletedItems()
    {
        return $this->hasMany(CRMOrderItem::class, 'order_id', 'id')->where('deleted', 1)->where('parent_id', 0);
    }

    public function orderProducts()
    {
        return $this->hasMany(CRMOrderItem::class, 'order_id', 'id')->where('product_id', '!=', 0)->where('subproduct_id',  0);
    }

    public function orderSubproducts()
    {
        return $this->hasMany(CRMOrderItem::class, 'order_id', 'id')->where('subproduct_id', '!=', 0)->where('parent_id', 0);
    }

    public function items()
    {
        return $this->hasMany(CRMOrderItem::class, 'order_id', 'id')->where('subproduct_id', '!=', 0);
    }

    public function orderSets()
    {
        return $this->hasMany(CRMOrderItem::class, 'order_id', 'id')->where('set_id', '!=', 0)->where('parent_id', 0);
    }

    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class, 'id', 'delivery_id');
    }

}
