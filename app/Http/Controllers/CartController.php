<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Base as Model;
use App\Http\Controllers\WishListController;
use App\Http\Controllers\PagesController as PageItem;
use App\Models\Page;
use App\Models\Product;
use App\Models\SubProduct;
use App\Models\Cart;
use App\Models\WishList;
use App\Models\WishListSet;
use App\Models\Promocode;
use App\Models\FrontUserUnlogged;
use App\Models\FrontUser;
use App\Models\Country;
use App\Models\Set;
use App\Models\Currency;

class CartController extends Controller
{
    // Render Cart Page
    public function index()
    {
        $page = Page::where('alias', 'cart')->first();

        if (is_null($page)) {
            abort(404);
        }

        $user = $this->checkIfLogged();

        $cartProducts = Cart::with(['product.translation', 'product.mainPrice', 'product.personalPrice', 'product.mainImage'])
                            ->where('user_id', $user['user_id'])
                            ->orderBy('id', 'desc')
                            ->where('parent_id', null)
                            ->get();

        if (!@$_COOKIE['country_id']) {
            if ($this->country->mainDelivery) {
                setcookie('country_id', $this->country->id, time() + 10000000, '/');
                setcookie('delivery_id', $this->country->mainDelivery->delivery_id, time() + 10000000, '/');
            }else{
                $mainCountry = Country::where('main', 1)->first();
                setcookie('country_id', $mainCountry->id, time() + 10000000, '/');
                setcookie('delivery_id', @$mainCountry->mainDelivery->delivery_id, time() + 10000000, '/');
            }
        }

        $pageItem = new PageItem;
        $seoData = $pageItem->getSeo($page);

        return view('front.'. $this->device .'.dynamic.cart', compact('seoData'));
    }

    // Get Cart Items - vue js
    public function getCartItems()
    {
        $userId = $this->checkIfLogged();

        return $this->getAllCarts($userId['user_id']);
    }

    // Add product to cart
    public function addProductToCart(Request $request)
    {
        $checkStock = 'true';
        $userId = $this->checkIfLogged();

        $product = Product::find($request->get('productId'));

        if (count($product->subproducts) > 0) {
            $subproduct = SubProduct::find($request->get('subproductId'))->first();
            if (!is_null($subproduct)) {
                $cart = Cart::where('user_id', $userId['user_id'])
                            ->where('subproduct_id', $request->get('subproductId'))
                            ->first();

                if (is_null($cart)) {
                    Cart::create([
                        'product_id' => $product->id,
                        'subproduct_id' => $request->get('subproductId'),
                        'user_id' => $userId['user_id'],
                        'qty' => 1,
                    ]);
                }else{
                    if ($subproduct->warehouse->stock > $cart->qty) {
                        Cart::where('id', $cart->id)->update([
                            'qty' =>  $cart->qty + 1,
                        ]);
                    }else{
                        $checkStock = 'false';
                    }
                }
            }
        }else{
            $cart = Cart::where('user_id', $userId['user_id'])->where('subproduct_id', 0)->where('product_id', $product->id)->first();

            if (is_null($cart)) {
                Cart::create([
                    'product_id' => $product->id,
                    'subproduct_id' => 0,
                    'user_id' => $userId['user_id'],
                    'qty' => $request->get('qty') ?? 1,
                ]);
            }else{
                if ($product->warehouse) {
                    if ($product->warehouse->stock > $cart->qty) {
                        Cart::where('id', $cart->id)->update([
                            'qty' =>  $cart->qty + 1,
                        ]);
                    }else{
                        $checkStock = 'false';
                    }
                }
            }
        }

        return $this->getCartItems();
    }

    public function addProductToCartFromWish(Request $request)
    {
        $checkStock = 'true';
        $userId = $this->checkIfLogged();

        $product = Product::find($request->get('productId'));

        if (count($product->subproducts) > 0) {
            $subproduct = SubProduct::find($request->get('subproductId'));
            if (!is_null($subproduct)) {
                $cart = Cart::where('user_id', $userId['user_id'])
                            ->where('subproduct_id', $request->get('subproductId'))
                            ->where('parent_id', 0)
                            ->first();

                if (is_null($cart)) {
                    Cart::create([
                        'product_id' => $subproduct->product_id,
                        'subproduct_id' => $request->get('subproductId'),
                        'user_id' => $userId['user_id'],
                        'qty' => 1,
                    ]);
                }else{
                    if ($subproduct->stoc > $cart->qty) {
                        Cart::where('id', $cart->id)->update([
                            'qty' =>  $cart->qty + 1,
                        ]);
                    }else{
                        $checkStock = 'false';
                    }
                }

                WishList::where('user_id', $userId['user_id'])
                        ->where('product_id', $request->get('productId'))
                        ->delete();

            }
        }else{
            $cart = Cart::where('user_id', $userId['user_id'])
                        ->where('product_id', $request->get('productId'))
                        ->where('subproduct_id', 0)
                        ->where('parent_id', 0)
                        ->first();

            if (is_null($cart)) {
                Cart::create([
                    'product_id' => $product->id,
                    'subproduct_id' => 0,
                    'user_id' => $userId['user_id'],
                    'qty' => 1,
                ]);
            }else{
                if ($subproduct->stoc > $cart->qty) {
                    Cart::where('id', $cart->id)->update([
                        'qty' =>  $cart->qty + 1,
                    ]);
                }else{
                    $checkStock = 'false';
                }
            }

            // return $request->get('productId');

            WishList::where('user_id', $userId['user_id'])
                    ->where('product_id', $request->get('productId'))
                    ->delete();
        }

        $wishList = new WishListController;
        $data['carts'] = $this->getCartItems();
        $data['wish'] = $wishList->getwishItems();

        return $data;
    }

    public function addSetToCart(Request $request)
    {
        $checkStock = 'true';
        $subproducts = array_filter($request->get('subproducts'), function($var){return !is_null($var);} );
        $set = Set::find($request->get('setId'));
        $userId = $this->checkIfLogged();

        $setCart = Cart::where('user_id', $userId['user_id'])
                        ->where('set_id', $set->id)
                        ->first();

        if (is_null($setCart)) {
            if (!is_null($set)) {
                if (count($subproducts) > 0) {
                    $setCart = Cart::create([
                            'set_id' => $set->id,
                            'user_id' => $userId['user_id'],
                            'qty' => 1,
                        ]);

                    foreach ($subproducts as $key => $subprod) {
                        $subproduct = SubProduct::find($subprod);
                        if (!is_null($subproduct)) {
                            $cart = Cart::where('user_id', $userId['user_id'])
                                        ->where('parent_id', $set->id)
                                        ->where('set_id', $set->id)
                                        ->where('subproduct_id', $subproduct->id)
                                        ->first();

                            if (is_null($cart)) {
                                Cart::create([
                                    'parent_id' => $setCart->id,
                                    'set_id' => $set->id,
                                    'subproduct_id' => $subproduct->id,
                                    'user_id' => $userId['user_id'],
                                    'qty' => 1,
                                ]);
                            }
                        }
                    }

                    WishListSet::where('user_id', $userId['user_id'])
                            ->where('set_id', $set->id)
                            ->delete();
                }
            }
        }

        // return $this->getCartItems();
        $wishList = new WishListController;
        $data['carts'] = $this->getCartItems();
        $data['wish'] = $wishList->getwishItems();

        return $data;
    }

    // Change Qty of Product Cart
    public function changeProductQty(Request $request)
    {
        Cart::where('id', $request->get('cartId'))->update([
            'qty' => $request->get('qty'),
        ]);

        return $this->getCartItems();
    }

    // Change Qty of Product Set
    public function changeSetQty(Request $request)
    {
        CartSet::where('id', $request->get('cartSetId'))->update([
            'qty' => $request->get('qty'),
        ]);

        return $this->getCartItems();
    }

    // Delete Product From Cart
    public function deleteProductFromCart(Request $request)
    {
        $cart = Cart::where('id', $request->get('cartId'))->first();

        if (!is_null($cart)) {
            Cart::where('id', $request->get('cartId'))->delete();
            Cart::where('parent_id', $request->get('cartId'))->delete();
        }

        return $this->getCartItems();
    }

    // // Delete Set From Cart
    // public function deleteSetFromCart(Request $request)
    // {
    //     $user = $this->checkIfLogged();
    //
    //     Cart::where('user_id', $user['user_id'])->where('set_id', $request->get('setId'))->delete();
    //
    //     CartSet::where('id', $request->get('cartSetId'))->delete();
    //
    //     return $this->getCartItems();
    // }

    // Remove All Cart
    public function removeAllCart()
    {
        $user = $this->checkIfLogged();

        Cart::where('user_id', $user['user_id'])->delete();

        return $this->getCartItems();
    }

    // Move Product to Favorites
    public function moveProductToWish(Request $request)
    {
        $user = $this->checkIfLogged();
        $cartProduct = Cart::where('id', $request->get('cartId'))->first();

        if (!is_null($cartProduct)) {
            $checkWish = WishList::where('user_id', $user['user_id'])->where('product_id', $cartProduct->product_id)->first();
            if (is_null($checkWish)) {
                WishList::create([
                    'product_id' => $cartProduct->product_id,
                    'subproduct_id' => $cartProduct->subproduct ? $cartProduct->subproduct_id : null,
                    'user_id' => $user['user_id'],
                    'is_logged' => $user['is_logged']
                ]);
            }
            $cartProduct->delete();
        }

        $wishList = new WishListController;
        $data['cartProducts'] = $this->getCartItems();
        $data['wishProducts'] = $wishList->getwishItems();

        return $data;
    }

    // move Set to Favorites
    public function moveSetToWish(Request $request)
    {
        $user = $this->checkIfLogged();

        $cartSet = Cart::where('id', $request->get('id'))->first();

        if (!is_null($cartSet)) {
            $checkWish = WishListSet::where('user_id', $user['user_id'])->where('set_id', $cartSet->set_id)->first();
            if (is_null($checkWish)) {
                $wishListSet = WishListSet::create([
                    'set_id' => $cartSet->set_id,
                    'user_id' => $user['user_id'],
                    'is_logged' => $user['is_logged']
                ]);
            }
            $cartSet->delete();
            Cart::where('parent_id', $cartSet->id)->delete();

            $wishList = new WishListController;
            $data['cartProducts'] = $this->getCartItems();
            $data['wishProducts'] = $wishList->getwishItems();

            return $data;
        }
    }

    // Move Product to Favorites
    public function moveAllProductToWish()
    {
        $user = $this->checkIfLogged();

        $carts = $this->getCartItems();

        foreach ($carts['subproducts'] as $key => $cartSubprod) {
            if ($cartSubprod->stock_qty == 0) {
                $checkWish = WishList::where('user_id', $user['user_id'])->where('product_id', $cartSubprod->product_id)->first();
                if (is_null($checkWish)) {
                    WishList::create([
                        'product_id' => $cartSubprod->product_id,
                        'subproduct_id' => $cartSubprod->subproduct ? $cartSubprod->subproduct_id : null,
                        'user_id' => $user['user_id'],
                        'is_logged' => $user['is_logged']
                    ]);
                }
                $cartSubprod->delete();
            }
        }

        foreach ($carts['sets'] as $key => $cartSet) {
            if ($cartSet->stock_qty == 0) {
                $checkWish = WishListSet::where('user_id', $user['user_id'])->where('set_id', $cartSet->set_id)->first();
                if (is_null($checkWish)) {
                    $wishListSet = WishListSet::create([
                        'set_id' => $cartSet->set_id,
                        'user_id' => $user['user_id'],
                        'is_logged' => $user['is_logged']
                    ]);
                }
                $cartSet->delete();
                Cart::where('parent_id', $cartSet->id)->delete();
            }
        }

        $wishList = new WishListController;
        $data['cartProducts'] = $this->getCartItems();
        $data['wishProducts'] = $wishList->getwishItems();

        return $data;
    }

    // Check Promocode
    public function checkPromocode(Request $request)
    {
        if (@$_COOKIE['promocode']) {
            $promocode = Promocode::where('name', @$_COOKIE['promocode'])
                                    // ->whereRaw('"'.date('Y-m-d').'" between `valid_from` and `valid_to`')
                                    ->where(function($query){
                                        $query->where('status', 'valid');
                                        $query->orWhere('status', 'partially');
                                    })
                                    ->first();

            return $this->validatePromoCode($promocode, $request->get('amount'));
        }
        return 'false';
    }

    // Apply Promocode
    public function applyPromocode(Request $request)
    {
        $promocode = Promocode::where('name', $request->get('promocode'))
                                // ->whereRaw('"'.date('Y-m-d').'" between `valid_from` and `valid_to`')
                                ->where(function($query){
                                    $query->where('status', 'valid');
                                    $query->orWhere('status', 'partially');
                                })
                                ->first();

        if (!is_null($promocode)) {
            $promocodeName = $promocode->name;
            setcookie('promocode', $promocodeName, time() + 10000000, '/');
        }

        return $this->validatePromoCode($promocode, $request->get('amount'));
    }

    // Validate Promoceode
    public function validatePromoCode($promocode, $amount)
    {
        $user = $this->checkIfLogged();
        $message = [];

        if (!is_null($promocode)) {

            if ($user['is_logged']) {
                if ($user['user_id'] !== $promocode->user_id && $promocode->user_id !== 0) {
                    $message['name'] = $promocode->name;
                    $message['body'] = trans('vars.Notifications.promocodeWrongUser');
                    $message['status'] = 'false';
                    $message['discount'] = 0;
                    setcookie('promocode', '', time() + 10000000, '/');
                }elseif ($promocode->treshold > $amount) {
                    $message['name'] = $promocode->name;
                    $message['body'] =  trans('vars.Promocode.promoCommand') .' '. $promocode->treshold .' Eur';
                    $message['status'] = 'false';
                    $message['discount'] = 0;
                    setcookie('promocode', '', time() + 10000000, '/');
                }else{
                    $message['name'] = $promocode->name;
                    $message['body'] = 'Success';
                    $message['status'] = 'true';
                    $message['discount'] = $promocode->discount;
                }
            }else{
                // if ($promocode->user_id !== 0) {
                //     $message['name'] = $promocode->name;
                //     $message['body'] = trans('vars.Notifications.promocodeWrongUser');
                //     $message['status'] = 'false';
                //     $message['discount'] = 0;
                //     setcookie('promocode', '', time() + 10000000, '/');
                // }
                if ($promocode->treshold > $amount) {
                    $message['name'] = $promocode->name;
                    $message['body'] =  trans('vars.Promocode.promoCommand') .' '. $promocode->treshold .' Eur';
                    $message['status'] = 'false';
                    $message['discount'] = 0;
                    setcookie('promocode', '', time() + 10000000, '/');
                }else{
                    $message['name'] = $promocode->name;
                    $message['body'] = 'Success';
                    $message['status'] = 'true';
                    $message['discount'] = $promocode->discount;
                }
            }
        }else{
            $message['name'] = '';
            $message['body'] = trans('vars.Promocode.promoCodeNotValid');
            $message['status'] = 'false';
            $message['discount'] = 0;
            setcookie('promocode', '', time() + 10000000, '/');
        }

        return $message;
    }

    // Get List of Active Countries
    public function getCountries(Request $request)
    {
        $userCountryId = @$_COOKIE['country_id'];

        $data['countries'] = Country::with(['translation', 'deliveries.delivery.translation', 'mainDelivery', 'payments', 'payments.payment.translation'])->where('active', 1)->get();

        // if (@$_COOKIE['country_id']) {
        //     $currentCountry = Country::with(['translation', 'deliveries.delivery.translation', 'mainDelivery', 'payments', 'payments.payment.translation'])->where('id', @$_COOKIE['country_id'])->first();
        //     $mainDelivery = @$_COOKIE['delivery_id'];
        // }else{
            $currentCountry = Country::with(['translation', 'deliveries.delivery.translation', 'mainDelivery', 'payments', 'payments.payment.translation'])->where('id', $userCountryId)->first();
            $mainDelivery = @$_COOKIE['delivery_id'];
        // }

        if (is_null($currentCountry) || is_null($currentCountry->mainDelivery)){
            $data['currentCountry'] = Country::with(['translation', 'deliveries.delivery.translation', 'mainDelivery', 'payments', 'payments.payment.translation'])->where('main', 1)->first();
            $data['mainDelivery'] = $mainDelivery;
        }else{
            $data['currentCountry'] = $currentCountry;
            $data['mainDelivery'] = $mainDelivery;
        }

        return $data;
    }

    public function setCountryDelivery(Request $request)
    {
        setcookie('country_id', $request->get('country'), time() + 10000000, '/');
        setcookie('delivery_id', $request->get('delivery'), time() + 10000000, '/');
    }

    // Check Logged User
    private function checkIfLogged()
    {
        if(auth('persons')->guest()) {
            return array('is_logged' => 0, 'user_id' => @$_COOKIE['user_id']);
        }else{
            return array('is_logged' => 1, 'user_id' => auth('persons')->id());
        }
    }

    // get updated carts of user
    private function getAllCarts($userId)
    {
        $this->validateStocks($userId);
        $data['sets'] = Cart::with(['children.subproduct.product.translation', 'children.subproduct.product.category', 'children.subproduct.product.mainPrice', 'children.subproduct.product.mainImage', 'children.subproduct.parameterValue.translation', 'set.price', 'set.mainPrice', 'set.personalPrice', 'set.translation', 'set.mainPhoto', 'set.collection', 'set.products.translation', 'set.products.mainImage', 'set.products.firstSubproduct', 'set.products.subproducts.parameterValue.translation'])
                                      ->where('user_id', $userId)
                                      ->where('parent_id', null)
                                      ->where('set_id', '!=', 0)
                                      ->orderBy('id', 'desc')
                                      ->get();

        $data['products'] = Cart::with(['product.mainPrice', 'product.personalPrice', 'product.translation', 'product.mainImage', 'product.category'])
                                      ->where('user_id', $userId)
                                      ->where('parent_id', null)
                                      ->where('product_id', '!=', null)
                                      ->where('subproduct_id',  0)
                                      ->orderBy('id', 'desc')
                                      ->get();

        $data['subproducts'] = Cart::with(['subproduct.price', 'subproduct.product.mainPrice', 'subproduct.product.personalPrice', 'subproduct.product.translation', 'subproduct.product.mainImage', 'subproduct.product.category', 'subproduct.parameterValue.translation'])
                                    ->where('user_id', $userId)
                                    ->where('parent_id', null)
                                    ->where('subproduct_id', '!=', 0)
                                    ->orderBy('id', 'desc')
                                    ->get();

        return $data;
    }

    // post: validate stocks
    public function validateStocks($userId)
    {
        $data['sets'] = Cart::where('user_id', $userId)
                          ->where('parent_id', null)
                          ->where('set_id', '!=', 0)
                          ->orderBy('id', 'desc')
                          ->get();

        $data['products'] = Cart::where('user_id', $userId)
                              ->where('parent_id', null)
                              ->where('product_id', '!=', null)
                              ->where('subproduct_id',  0)
                              ->orderBy('id', 'desc')
                              ->get();

        $data['subproducts'] = Cart::where('user_id', $userId)
                                ->where('parent_id', null)
                                ->where('subproduct_id', '!=', 0)
                                ->orderBy('id', 'desc')
                                ->get();

        if (count($data['products']) > 0) {
            foreach ($data['products'] as $key => $product) {
                $this->validateProductStock($product);
            }
        }
        if (count($data['subproducts']) > 0) {
            foreach ($data['subproducts'] as $key => $subproduct) {
                $this->validateSubproductStock($subproduct);
            }
        }
        // if (count($data['sets']) > 0) {
        //     foreach ($data['sets'] as $key => $set) {
        //         $this->validateSetStock($set);
        //     }
        // }
    }

     // post: validate sets stocks
     public function validateSetStock($setCart)
     {
         $setStock = $setCart->qty;

         foreach ($setCart->children as $key => $child) {
             if ($child->subproduct_id !== null) {
                 $subCartsSum = Cart::where('user_id', $setCart->user_id)
                                 ->where('id', '!=', $child->id)
                                 ->where('subproduct_id', $child->subproduct_id)
                                 ->get()->sum('qty');

                 $subStock = SubProduct::find($child->subproduct_id)->stoc;
                 $stock_qty = ($subStock - $subCartsSum) > 0 ? $subStock - $subCartsSum : 0;
                 $qty = ($child->qty > $stock_qty) || ($child->qty === 0) ? $stock_qty : $child->qty;

                 $child->update(['stock_qty' => $stock_qty, 'qty' => $qty]);
             }else{
                 $prodCartsSum = Cart::where('user_id', $setCart->user_id)
                                 ->where('id', '!=', $child->id)
                                 ->where('product_id', $child->product_id)
                                 ->get()->sum('qty');


                 $prodStock = Product::find($child->product_id)->stock;
                 $stock_qty = ($prodStock - $prodCartsSum) > 0 ? $prodStock - $prodCartsSum : 0;
                 $qty =($child->qty > $stock_qty) || ($child->qty === 0) ? $stock_qty : $child->qty;

                 $child->update(['stock_qty' => $stock_qty, 'qty' => $qty]);
             }
         }

         $stock_qty = $setCart->children->min('stock_qty');
         $qty = ($setCart->qty > $stock_qty) || ($setCart->qty === 0)  ? $stock_qty : $setCart->qty;

         $setCart->update(['stock_qty' => $stock_qty, 'qty' => $qty]);
     }

     // post: validate products stocks
     public function validateProductStock($productCart)
     {
         $productStock = $productCart->qty;

         $prodCartsSum = Cart::where('user_id', $productCart->user_id)
                             ->where('id', '!=', $productCart->id)
                             ->where('product_id', $productCart->product_id)
                             ->get()->sum('qty');

        // dd(Product::find($productCart->product_id));

         $prodStock = Product::find($productCart->product_id)->warehouse->stock;

         $stock_qty = ($prodStock - $prodCartsSum) > 0 ? $prodStock - $prodCartsSum : 0;
         $qty = $productCart->qty >= $stock_qty ? $stock_qty : $productCart->qty;

         if ($qty == 0 && $stock_qty > 0) {
             $qty = 1;
         }

         $productCart->update(['stock_qty' => $stock_qty, 'qty' => $qty]);
     }

     // post: validate subproducts stocks
     public function validateSubproductStock($subproductCart)
     {
         $productStock = $subproductCart->qty;

         $prodCartsSum = Cart::where('user_id', $subproductCart->user_id)
                             ->where('id', '!=', $subproductCart->id)
                             ->where('subproduct_id', $subproductCart->subproduct_id)
                             ->get()->sum('qty');

         $subprodStock = SubProduct::find($subproductCart->subproduct_id)->warehouse->stock;
         $stock_qty = ($subprodStock - $prodCartsSum) > 0 ? $subprodStock - $prodCartsSum : 0;
         $qty = $subproductCart->qty >= $stock_qty ? $stock_qty : $subproductCart->qty;

         if ($subprodStock == 0) {
             $subproductCart->update(['stock_qty' => 0, 'qty' => 0]);
         }else{
             if ($qty == 0 && $stock_qty > 0) {
                 $qty = 1;
             }
             $subproductCart->update(['stock_qty' => $stock_qty, 'qty' => $qty]);
         }
     }

     public function changeCountry(Request $request)
     {
         $country = Country::find($request->get('countryId'));

         if (!is_null($country)) {
             setcookie('warehouse_id', $country->warehouse_id, time() + 10000000, '/');
             setcookie('country_id', $country->id, time() + 10000000, '/');
         }

         Model::$warehouse = $country->warehouse_id;

         return $this->getCartItems();
     }

     public function exchangeShippingPrice(Request $request)
     {
         $price =  $request->get('price');
         $currencyAbbr =  $request->get('currency');
         $exchangedPrice = $price;

         $currency = Currency::where('abbr', $currencyAbbr)->first();

         if (!is_null($currency)) {
             $exchangedPrice = $price * $currency->rate;
         }

        return $exchangedPrice;
     }

}
