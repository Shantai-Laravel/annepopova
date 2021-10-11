<?php

namespace App\Models;

use App\Base as Model;

class Product extends Model
{
    protected $fillable = [
                'category_id',
                'promotion_id',
                'alias',
                'position',
                'succesion',
                'price',
                'dependable_price',
                'actual_price',
                'discount',
                'hit',
                'recomended',
                'stock',
                'code',
                'ean_code',
                'video',
                'discount_update',
                'homewear',
                'bijoux',
                'active',
                'frisbo',
                'swagger'
            ];

    protected $appends = ['color', 'type'];

    public function getColorAttribute()
    {
        return null;
    }

    public function getTypeAttribute()
    {
        if ($this->bijoux == 1) {
            return "bijoux";
        }else{
            return "homewear";
        }
    }

    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(ProductTranslation::class)->where('lang_id', self::$lang);
    }

    public function translationByLang($lang)
    {
        return $this->hasOne(ProductTranslation::class)->where('lang_id', $lang)->first();
    }

    public function price()
    {
        return $this->hasOne(ProductPrice::class)->where('currency_id', self::$currency);
    }

    public function warehouse()
    {
        return $this->hasOne(WarehousesStock::class, 'product_id', 'id')->where('subproduct_id', null)->where('warehouse_id', self::$warehouse);
    }

    public function warehouseFrisbo()
    {
        return $this->hasOne(WarehousesStock::class, 'product_id', 'id')->where('subproduct_id', null)->where('warehouse_id', 1);
    }

    public function warehouses()
    {
        return $this->hasMany(WarehousesStock::class, 'product_id', 'id')->where('subproduct_id', null);
    }

    public function getUserGroupId()
    {
        $dillerGroupId = null;

        if (!is_null(auth('persons')->id())) {
            if (auth('persons')->user()->active_diller == 1) {
                if (auth('persons')->user()->diller_group_id > 0) {
                    $dillerGroupId = auth('persons')->user()->diller_group_id;
                }else{
                    $dillerGroupId = 'b2b_prices';
                }
            }
        }

        return $dillerGroupId;
    }

    public function mainPrice()
    {
        $dillerGroupId = $this->getUserGroupId();
        $prices = new ProductPrice();

        if (!is_null($dillerGroupId)) {
            if ($dillerGroupId == 'b2b_prices') {
                return $this->hasOne(ProductPrice::class)->where('currency_id', self::$mainCurrency);
            }else{
                return $this->hasOne(ProductDillerPrice::class)->where('diller_group_id', $dillerGroupId)->where('currency_id', self::$mainCurrency);
            }
        }else{
            return $this->hasOne(ProductPrice::class)->where('currency_id', self::$mainCurrency);
        }
    }

    public function personalPrice()
    {
        $dillerGroupId = $this->getUserGroupId();
        $prices = new ProductPrice();

        if (!is_null($dillerGroupId)) {
            if ($dillerGroupId == 'b2b_prices') {
                return $this->hasOne(ProductPrice::class)->where('currency_id', self::$currency);
            }else{
                return $this->hasOne(ProductDillerPrice::class)->where('diller_group_id', $dillerGroupId)->where('currency_id', self::$currency);
            }
        }else{
            return $this->hasOne(ProductPrice::class)->where('currency_id', self::$currency);
        }

        // return $this->hasOne(ProductPrice::class)->where('currency_id', self::$currency);
    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class)->orderBy('dependable', 'desc');
    }

    public function category()
    {
        return $this->hasOne(ProductCategory::class, 'id', 'category_id');
    }

    public function brands()
    {
        return $this->hasMany(ProductBrand::class, 'product_id', 'id');
    }

    public function collections()
    {
        return $this->hasMany(ProductCollection::class, 'product_id', 'id');
    }

    public function mainImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id')->where('type', null)->orderBy('main', 'desc');
    }

    public function setImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id')->where('type', null)->where('first', 5);
    }

    public function FBImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id')->where('type', 'fb');
    }

    public function hoverImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id')->where('main', '!==', '1')->orderBy('id', 'asc');
    }

    // public function setImage($setId)
    // {
    //      return $this->hasOne(SetProductImage::class, 'product_id')->where('set_id', $setId);
    // }

    public function setImages()
    {
        return  $this->hasMany(ProductImage::class, 'product_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id')->where('type', null)->orderBy('main', 'asc')->orderBy('first', 'asc')->orderBy('id', 'asc');
    }

    public function imagesFB()
    {
         return $this->hasMany(ProductImage::class, 'product_id')->where('type', 'fb')->orderBy('id', 'asc');
    }

    public function imagesBegin()
    {
         return $this->hasMany(ProductImage::class, 'product_id')->where('main', 1)->orWhere('first', 1)->orderBy('first', 'asc');
    }

    public function imagesLast()
    {
         return $this->hasMany(ProductImage::class, 'product_id')->where('main',  0)->where('first', 0);
    }

    public function inCart()
    {
        return $this->hasOne(Cart::class, 'product_id')->where('user_id', @$_COOKIE['user_id']);
    }

    public function inWishList()
    {
        $user_id = auth('persons')->id() ? auth('persons')->id() : @$_COOKIE['user_id'];
        return $this->hasOne(WishList::class, 'product_id')->where('user_id', $user_id);
    }

    public function similar()
    {
        return $this->hasMany(ProductSimilar::class);
    }

    public function subproducts()
    {
        return $this->hasMany(SubProduct::class);
    }

    public function firstSubproduct()
    {
        return $this->hasOne(SubProduct::class)->where('stoc', '!=', 0);
    }

    public function subproductById($id)
    {
        return $this->hasOne(SubProduct::class)->where('id', $id);
    }

    public function property()
    {
        return $this->hasMany(SubProductProperty::class, 'product_category_id', 'category_id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'product_id', 'id');
    }

    public function set()
    {
        return $this->hasOne(Set::class, 'id', 'set_id');
    }

    public function setProds()
    {
        return $this->hasMany(SetProducts::class, 'product_id', 'id');
    }

    public function sets()
    {
        return $this->belongsToMany(Set::class, 'set_product');
    }

    public function propertyValues()
    {
        return $this->hasMany(ParameterValueProduct::class, 'product_id', 'id')->orderBy('id', 'desc');
    }

    public function materials()
    {
        return $this->hasMany(ProductMaterial::class, 'product_id', 'id');
    }

    public function productCategories()
    {
        return $this->hasMany(ProductsCategories::class, 'product_id', 'id');
    }
}
