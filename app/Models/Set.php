<?php

namespace App\Models;

use App\Base as Model;

class Set extends Model
{
    protected $table = 'sets';

    protected $fillable = [
            'collection_id',
            'alias',
            'code',
            'video',
            'price',
            'dependable_price',
            'discount',
            'position',
            'on_home',
            'active',
            'stock',
            'material_id',
            'color_id',
            'room_id',
            'employment_id',
            'com',
            'md',
            'banner_desktop',
            'banner_mobile',
            'homewear',
            'bijoux'
    ];

    public function translations()
    {
        return $this->hasMany(SetTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(SetTranslation::class, 'set_id')->where('lang_id', self::$lang);
    }

    public function price()
    {
        return $this->hasOne(SetPrice::class)->where('currency_id', self::$currency);
    }

    public function mainPrice()
    {
        return $this->hasOne(SetPrice::class)->where('currency_id', self::$mainCurrency);
    }

    public function personalPrice()
    {
        return $this->hasOne(SetPrice::class)->where('currency_id', self::$currency);
    }

    public function prices()
    {
        return $this->hasMany(SetPrice::class)->orderBy('dependable', 'desc');
    }

    public function setProduct($productId)
    {
        return $this->hasOne(SetProducts::class, 'set_id', 'id')->where('product_id', $productId);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'set_product')->where(self::$warehouseName, 1)->orderBy('set_product.position', 'asc');
    }

    public function galleryItems()
    {
        return $this->hasMany(SetGallery::class, 'set_id', 'id')->orderBy('main', 'desc');
    }

    public function photos()
    {
        return $this->hasMany(SetGallery::class, 'set_id', 'id')->where('type', 'photo')->orderBy('main', 'desc');
    }

    public function videos()
    {
        return $this->hasMany(SetGallery::class, 'set_id', 'id')->where('type', 'video');
    }

    public function mainPhoto()
    {
        return $this->hasOne(SetGallery::class, 'set_id', 'id')->where('type', 'photo')->orderBy('main', 'desc');
    }

    public function collection()
    {
        return $this->hasOne(Collection::class, 'id', 'collection_id');
    }

    // added relations (daxin)
    public function material()
    {
        return $this->hasOne(ParameterValue::class, 'id', 'material_id');
    }

    public function color()
    {
        return $this->hasOne(ParameterValue::class, 'id', 'color_id');
    }

    public function room()
    {
        return $this->hasOne(ParameterValue::class, 'id', 'room_id');
    }

    public function employment()
    {
        return $this->hasOne(ParameterValue::class, 'id', 'employment_id');
    }
}
