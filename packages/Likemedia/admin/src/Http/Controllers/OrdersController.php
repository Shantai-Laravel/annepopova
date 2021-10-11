<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\FrontUser;
use App\Models\SubProduct;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Set;
use App\Models\SetTranslation;
use App\Models\Cart;
use App\Models\Promocode;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\CountryPayment;
use App\Models\CRMOrders;
use App\Models\FrontUserAddress;
use App\Models\CRMOrderItem;


class OrdersController extends Controller
{
    // get: create order page
    public function index()
    {
        return view('admin::admin.orders.index');
    }

    // get: order lists
    public function getOrdersList(Request $request)
    {
        if ($request->get('status')) {
            if ($request->get('status') == 'preorders') {
                $orders = CRMOrders::where('label', '!=', 'Payed')->orWhere('label', null)->where('user_id', '!=', '0')->orderBy('id', 'desc')->get();
            }else{
                $orders = CRMOrders::where('label', 'Payed')->where('main_status', $request->get('status'))->where('user_id', '!=', '0')->orderBy('id', 'desc')->get();
            }
        }else{
            $orders = CRMOrders::where('label', 'Payed')->where('main_status', 'pendding')->where('user_id', '!=', '0')->orderBy('id', 'desc')->get();
        }

        $orders = CRMOrders::where('user_id', '!=', '0')->orderBy('id', 'desc')->get();

        return view('admin::admin.orders.list', compact('orders'));
    }

    // get: order lists
    public function getOrdersListGuests(Request $request)
    {
        // if ($request->get('status')) {
        //     $orders = CRMOrders::where('main_status', $request->get('status'))->where('guest_user_id', '!=', '0')->orderBy('id', 'desc')->get();
        // }else{
        //     $orders = CRMOrders::where('main_status', 'pendding')->where('guest_user_id', '!=', '0')->orderBy('id', 'desc')->get();
        // }

        if ($request->get('status')) {
            if ($request->get('status') == 'preorders') {
                $orders = CRMOrders::where('label', '!=', 'Payed')->orWhere('label', null)->where('guest_user_id', '!=', '0')->orderBy('id', 'desc')->get();
            }else{
                $orders = CRMOrders::where('label', 'Payed')->where('main_status', $request->get('status'))->where('guest_user_id', '!=', '0')->orderBy('id', 'desc')->get();
            }
        }else{
            $orders = CRMOrders::where('label', 'Payed')->where('main_status', 'pendding')->where('guest_user_id', '!=', '0')->orderBy('id', 'desc')->get();
        }

        $orders = CRMOrders::where('guest_user_id', '!=', '0')->orderBy('id', 'desc')->get();

        return view('admin::admin.orders.list', compact('orders'));
    }

    // get: order detail
    public function orderDetail($id)
    {
        $order = CRMOrders::where('id', $id)->first();

        $orderProducts = CRMOrderItem::where('order_id', $order->id)
                                    ->where('parent_id', 0)
                                    ->where('subproduct_id', 0)
                                    ->where('product_id', '!=', 0)
                                    ->get();

        $orderSubproducts = CRMOrderItem::where('order_id', $order->id)
                                    ->where('parent_id', 0)
                                    ->where('subproduct_id', '!=', 0)
                                    ->get();

        $orderSets = CRMOrderItem::where('order_id', $order->id)
                                    ->where('parent_id', 0)
                                    ->where('set_id', '!=', 0)
                                    ->get();

        return view('admin::admin.orders.details', compact('order', 'orderProducts', 'orderSubproducts', 'orderSets'));
    }

    // post: search users
    public function searchUsers(Request $request)
    {
        $users = FrontUser::with(['promocodes', 'country.deliveries.delivery.translation', 'country.mainDelivery.delivery', 'address'])
                        ->where('name', 'like',  '%'.$request->get('user_search').'%')
                        ->orWhere('surname', 'like',  '%'.$request->get('user_search').'%')
                        ->orWhere('email', 'like',  '%'.$request->get('user_search').'%')
                        ->orWhere('phone', 'like',  '%'.$request->get('user_search').'%')
                        ->get();

        return $users;
    }

    // post: search, get all users
    public function searchShowAllUsers()
    {
        $users = FrontUser::with(['promocodes', 'country.deliveries.delivery.translation', 'country.mainDelivery.delivery', 'address'])->get();

        return $users;
    }

    // post: search products, subproducts, sets
    public function searchProducts(Request $request)
    {
        $findProducts = ProductTranslation::where('name', 'like',  '%'.$request->get('product_search').'%')
                                    ->pluck('product_id')->toArray();

        $data['products'] = Product::with('translation', 'subproducts.price', 'subproducts.parameterValue.translation', 'price')
                                ->whereIn('id', $findProducts)
                                ->orWhere('code', 'like',  '%'.$request->get('product_search').'%')
                                ->get();

        $findSets = SetTranslation::where('name', 'like',  '%'.$request->get('product_search').'%')
                                    ->pluck('set_id')->toArray();

        $data['sets'] = Set::with('translation', 'price', 'products.translation', 'products.mainImage', 'products.firstSubproduct', 'products.subproducts.parameterValue.translation')
                            ->whereIn('id', $findSets)
                            ->orWhere('code', 'like',  '%'.$request->get('product_search').'%')
                            ->get();

        return $data;
    }

    // post: get user cart
    public function getUserCart(Request $request)
    {
        return $this->getAllCarts($request->get('user_id'));
    }

    // post: add set to cart
    public function addSetToCart(Request $request)
    {
        $stock = false;

        $subproducts = array_filter($request->get('subproducts'), function($var){return !is_null($var);} );

        $cart = Cart::where('user_id', $request->get('user_id'))
                        ->where('set_id', $request->get('set_id'))
                        ->first();

        if (!is_null($cart)) {
            $cart->delete();
            Cart::where('parent_id', $cart->id)->delete();
        }

            $setCart = Cart::create([
                'set_id' => $request->get('set_id'),
                'user_id' => $request->get('user_id'),
                'qty' => 1,
            ]);

            $set = Set::where('id', $request->get('set_id'))->first();

            if ($set->products()->count() > 0) {
                foreach ($set->products()->get() as $key => $product) {
                    if ($product->subproducts()->count() > 0) {
                        foreach ($product->subproducts()->get() as $key => $subprod) {
                            if (in_array($subprod->id, $subproducts)) {
                                Cart::create([
                                    'parent_id' => $setCart->id,
                                    'product_id' => $product->id,
                                    'subproduct_id' => $subprod->id,
                                    'user_id' => $request->get('user_id'),
                                    'qty' => 1,
                                ]);
                                if (!$stock || $stock < $subprod->stoc) {
                                    $stock = $subprod->stoc;
                                }
                            }
                        }
                    }else{
                        Cart::create([
                            'parent_id' => $setCart->id,
                            'product_id' => $product->id,
                            'user_id' => $request->get('user_id'),
                            'qty' => 1,
                        ]);
                        if (!$stock || $stock < $product->stock) {
                            $stock = $product->stock;
                        }
                    }
                }

        }

        $data['carts'] = $this->getAllCarts($request->get('user_id'));
        $data['stock'] = $stock;

        return $data;
    }

    // post: add product to cart
    public function addProductToCart(Request $request)
    {
        $cart = Cart::where('user_id', $request->get('user_id'))
                    ->where('parent_id', null)
                    ->where('product_id', $request->get('product_id'))
                    ->first();

        if (is_null($cart)) {
            $cart = Cart::create([
                'product_id' => $request->get('product_id'),
                'user_id' => $request->get('user_id'),
                'qty' => 1,
            ]);
        }

        return $this->getAllCarts($request->get('user_id'));
    }

    // post: add subproduct to cart
    public function addSubproductToCart(Request $request)
    {
        $cart = Cart::where('user_id', $request->get('user_id'))
                        ->where('parent_id', null)
                        ->where('subproduct_id', $request->get('subproduct_id'))
                        ->first();

        if (is_null($cart)) {
            $cart = Cart::create([
                'subproduct_id' => $request->get('subproduct_id'),
                'user_id' => $request->get('user_id'),
                'qty' => 1,
            ]);
        }

        return $this->getAllCarts($request->get('user_id'));
    }

    // post: remove cart
    public function removeCart(Request $request)
    {
        $cart = Cart::where('id', $request->get('id'))->first();
        Cart::where('parent_id', $cart->id)->delete();
        Cart::where('id', $request->get('id'))->delete();

        return $this->getAllCarts($request->get('user_id'));
    }

    // post: move cart to favorite
    public function moveToFavoriteCart(Request $request)
    {
        $cart = Cart::where('id', $request->get('id'))->first();
        Cart::where('parent_id', $cart->id)->delete();
        Cart::where('id', $request->get('id'))->delete();

        return $this->getAllCarts($request->get('user_id'));
    }

    // post: deformate set
    public function deformateSetCart(Request $request)
    {
        $cart = Cart::where('id', $request->get('id'))->first();
        $children = Cart::where('parent_id', $cart->id)->get();

        foreach ($children as $key => $child) {
            if ($child->subproduct_id !== null) {
                $subproduct = Cart::where('user_id', $cart->user_id)
                                ->where('parent_id', null)
                                ->where('subproduct_id', $child->subproduct_id)
                                ->first();
                if (!is_null($subproduct)) {
                    $child->delete();
                }else{
                    $child->update(['parent_id' => null, 'product_id' => null]);
                }
            }else{
                $product = Cart::where('user_id', $cart->user_id)
                                ->where('parent_id', null)
                                ->where('product_id', $child->product_id)
                                ->first();
                if (!is_null($product)) {
                    $child->delete();
                }else{
                    $child->update(['parent_id' => null]);
                }
            }
        }

        Cart::where('id', $request->get('id'))->delete();

        return $this->getAllCarts($request->get('user_id'));
    }

    // post: change qty of cart item
    public function changeQty(Request $request)
    {
        $cart = Cart::where('id', $request->get('id'))->first();

        if ($cart->set_id) {
            $set = Set::where('id', $cart->set_id)->first();

            if ($set->stock > $request->get('qty'))  $stock = $request->get('qty');
            else  $stock = $set->stock;

            Cart::where('id', $request->get('id'))->update(['qty' => $stock]);
            Cart::where('parent_id', $request->get('id'))->update(['qty' => $stock]);

        }elseif ($cart->product_id) {
            $product = Product::where('id', $cart->product_id)->first();

            if ($product->stock > $request->get('qty'))  $stock = $request->get('qty');
            else  $stock = $product->stock;

            Cart::where('id', $request->get('id'))->update(['qty' => $stock]);

        }elseif ($cart->subproduct_id) {
            $subproduct = SubProduct::where('id', $cart->subproduct_id)->first();

            if ($subproduct->stoc > $request->get('qty'))  $stock = $request->get('qty');
            else  $stock = $subproduct->stoc;

            Cart::where('id', $request->get('id'))->update(['qty' => $stock]);
        }

        return $this->getAllCarts($request->get('user_id'));
    }

    // post: apply promocode
    public function applyPromocode(Request $request)
    {
        $data['message'] = 'Promocode is not valid';
        $data['status'] = 'false';
        $data['id'] = 0;
        $data['discount'] = 0;

        $promocode = Promocode::where('user_id', $request->get('user_id'))
                            ->where('name', $request->get('promocode'))
                            ->whereRaw('"'.date('Y-m-d').'" between `valid_from` and `valid_to`')
                            ->where(function($query){
                                $query->where('status', 'valid');
                                $query->orWhere('status', 'partially');
                            })
                            ->first();


        if (is_null($promocode)) {
            $promocode = Promocode::where('user_id', 0)
                                ->where('name', $request->get('promocode'))
                                ->whereRaw('"'.date('Y-m-d').'" between `valid_from` and `valid_to`')
                                ->where(function($query){
                                    $query->where('status', 'valid');
                                    $query->orWhere('status', 'partially');
                                })
                                ->first();
        }

        if (!is_null($promocode)) {
            if ($promocode->treshold <= $request->get('ammount')) {
                $data['message'] = 'Success, -'. $promocode->discount .' % with promocode!';
                $data['status'] = 'true';
                $data['id'] = $promocode->id;
                $data['discount'] = $promocode->discount;
            }else{
                $data['message'] = 'Error, promocode trashold >'. $promocode->treshold;
            }
        }

        return $data;
    }

    // post: change subproduct of set
    public function changeSetSubproduct(Request $request)
    {
        $stock = $request->get('setStock');

        Cart::where('user_id', $request->get('user_id'))
            ->where('parent_id', $request->get('cart_set'))
            ->where('product_id', $request->get('product_id'))
            ->update([
                'subproduct_id' => $request->get('subproduct_id')
            ]);

        $subproduct = Subproduct::find($request->get('subproduct_id'));

        if ($subproduct->stoc <= $stock) {
            $stock = $subproduct->stoc;
        }

        return $this->getAllCarts($request->get('user_id'));
    }

    // post: get the list of countries
    public function getCountriesList(Request $request)
    {
        $countries = Country::with(['translation', 'deliveries.delivery.translation', 'mainDelivery.delivery'])
                            ->where('active', 1)
                            ->get();
        return $countries;
    }

    // post: get the current country
    public function getCurrentCountry(Request $request)
    {
        return Country::with(['translation', 'deliveries.delivery.translation', 'mainDelivery.delivery'])
                            ->where('active', 1)
                            ->where('id', $request->get('countryId'))
                            ->first();
    }

    // post: get currenct delivery
    public function getCurrentDelivery(Request $request)
    {
        return Delivery::with(['translation'])
                    ->where('id', $request->get('deliveryId'))
                    ->first();
    }

    // post: payments by country
    public function getPaymentsList(Request $request)
    {
        $countryPayments = CountryPayment::where('country_id', $request->get('countryId'))->pluck('payment_id')->toArray();

        return Payment::with(['translation'])->whereIn('id', $countryPayments)->get();
    }

    // post: user order
    public function userOrder(Request $request)
    {
        $cartData = $request->get('cartData');
        $shipingData = $request->get('shippingData');
        $payment = $request->get('payment');
        $userAddress = false;

        $country = Country::find($shipingData['countryId']);
        $promocode = Promocode::find($cartData['promocodeId']);
        $currency = Currency::find($cartData['currencyId']);
        $delivery = Delivery::find($shipingData['deliveryId']);
        $payment = Payment::find($payment);

        if ($shipingData['setDefault']) {
            FrontUser::where('id', $cartData['userId'])->update([
                'payment_id' => $request->get('payment')
            ]);

            FrontUserAddress::where('front_user_id', $cartData['userId'])->delete();
            $userAddress = FrontUserAddress::create([
                'front_user_id' => $cartData['userId'],
                'country' => $shipingData['countryId'],
                'region' => $shipingData['region'],
                'location' => $shipingData['city'],
                'address' => $shipingData['streetAddress'],
                'code' => $shipingData['zip'],
                'homenumber' => $shipingData['apartament'],
            ]);
        }

        $order = CRMOrders::create([
            'order_hash' => md5(uniqid().date('Y-M-s')),
            'user_id' => $shipingData['name'],
            'address_id' => $userAddress ? $userAddress->id : 0,
            'promocode_id'=> $cartData['promocodeId'],
            'currency_id'=> $cartData['currencyId'],
            'payment_id'=> $payment,
            'delivery_id'=> $shipingData['deliveryId'],
            'country_id'=> $shipingData['countryId'],
            'amount' => $cartData['amount'],
            'main_status' => 'pendding',
            'change_status_at'=> date('Y-m-d'),
        ]);

        $order->details()->create([
            'contact_name' => $cartData['userId'],
            'email' =>  @$shipingData['email'],
            'promocode' => !is_null($promocode) ? $promocode->name : null,
            'code' => @$shipingData['code'],
            'phone' => @$shipingData['phone'],
            'currency' => @$currency->abbr,
            'payment' => @$payment->translation->name,
            'delivery' => @$delivery->translation->name,
            'country' => @$country->translation->name,
            'region'=> @$shipingData['region'],
            'city'=> @$shipingData['city'],
            'address'=> @$shipingData['streetAddress'],
            'apartment'=> @$shipingData['apartament'],
            'zip' => @$shipingData['zip'],
            'delivery_price' => @$delivery->price,
            'tax_price' => $cartData['taxPrice'],
            'comment' => @$shipingData['comment'],
        ]);

        $carts = $this->getAllCarts($cartData['userId']);

        if ($carts['products']) {
            foreach ($carts['products'] as $key => $product) {
                if ($product->stock_qty > 0) {
                    CRMOrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->product_id,
                        'qty' => $product->qty,
                        'discount' => $product->product->discount,
                        'code' => $product->product->code,
                        'old_price' => $product->product->price()->first()->old_price,
                        'price' => $product->product->price()->first()->price,
                        'currency' => $currency->abbr,
                    ]);
                    Product::where('id', $product->product_id)->update(['stock' => $product->product->stock - $product->qty]);
                }
            }
        }

        if ($carts['subproducts']) {
            foreach ($carts['subproducts'] as $key => $product) {
                if ($product->stock_qty > 0) {
                    CRMOrderItem::create([
                        'order_id' => $order->id,
                        'subproduct_id' => $product->subproduct_id,
                        'qty' => $product->qty,
                        'discount' => $product->subproduct->discount,
                        'code' => $product->subproduct->code,
                        'old_price' => $product->subproduct->price()->first()->old_price,
                        'price' => $product->subproduct->price()->first()->price,
                        'currency' => $currency->abbr,
                    ]);
                    SubProduct::where('id', $product->subproduct_id)->update(['stoc' => $product->subproduct->stoc - $product->qty]);
                }
            }
        }

        if ($carts['sets']) {
            foreach ($carts['sets'] as $key => $set) {
                if ($set->stock_qty > 0) {
                    $list = CRMOrderItem::create([
                                'order_id' => $order->id,
                                'set_id' => $set->set_id,
                                'qty' => $set->qty,
                                'code' => $set->set->code,
                                'old_price' => $set->set->price()->first()->old_price,
                                'price' => $set->set->price()->first()->price,
                                'currency' => $currency->abbr,
                            ]);

                    Set::where('id', $set->set_id)->update(['stock' => $set->set->stock - $set->qty]);

                    if ($set->children()->get()) {
                        foreach ($set->children()->get() as $key => $chid) {
                            CRMOrderItem::create([
                                'order_id' => $order->id,
                                'parent_id' => $list->id,
                                'set_id' => $chid->set_id,
                                'product_id' => $chid->product_id,
                                'subproduct_id' => $chid->subproduct_id,
                                'qty' => $chid->qty,
                                'currency' => $currency->abbr,
                            ]);
                            if (!is_null($chid->subproduct)) {
                                SubProduct::where('id', $chid->subproduct_id)->update(['stoc' => $chid->subproduct->stoc - $chid->qty]);
                            }else{
                                Product::where('id', $chid->product_id)->update(['stock' => $chid->product->stock - $chid->qty]);
                            }
                        }
                    }
                }
            }
        }

        Cart::where('user_id', $cartData['userId'])->delete();

        return $order->id;
    }

    // post: user preodrder
    public function userPreOrder(Request $request)
    {
        $cartData = $request->get('cartData');

        $this->validateStocks($cartData['userId']);

        $carts = Cart::where('user_id', $cartData['userId'])->where('stock_qty', 0)->get();

        if ($carts->count() > 0) {
            $data['carts'] = $this->getAllCarts($cartData['userId']);
            $data['status'] = 'false';
            return $data;
        }

        $data['carts'] = $this->getAllCarts($cartData['userId']);
        $data['status'] = 'true';
        return $data;
    }

    //post: change status of order
    public function changeOrderStatus(Request $request)
    {
        $order = CRMOrders::where('id', $request->get('order_id'))->first();

        if ($order->main_status !== 'cancelled') {
            CRMOrders::where('id', $request->get('order_id'))
                    ->update([
                        'main_status' => $request->get('main_status'),
                        'secondary_status' => $request->get('secondary_status'),
                    ]);

            $order->details()->update([
                'region' => $request->get('region'),
                'city' => $request->get('city'),
                'address' => $request->get('address'),
                'apartment' => $request->get('apartment'),
                'zip' => $request->get('zip'),
                'comment' => $request->get('comment'),
            ]);

            if ($request->get('main_status') == 'cancelled') {
                if ($order->orderProducts()->count() > 0) {
                    foreach ($order->orderProducts as $key => $orderProducts) {
                        $product = Product::where('id', $orderProducts->product_id)->first();
                         Product::where('id', $orderProducts->product_id)::update(['stock' => ($orderProducts->qty + $product->stock)]);
                    }
                }
                if ($order->orderSubproducts()->count() > 0) {
                    foreach ($order->orderSubproducts as $key => $orderSubproducts) {
                        $subproduct = SubProduct::where('id', $orderSubproducts->subproduct_id)->first();
                        SubProduct::where('id', $orderSubproducts->subproduct_id)->update(['stoc' => ($orderSubproducts->qty + $subproduct->stoc)]);
                    }
                }

                if ($order->orderSets()->count() > 0) {
                    foreach ($order->orderSets as $key => $orderSets) {
                        if ($orderSets->children()->count() > 0) {
                            foreach ($orderSets->children as $key => $child) {
                                if ($child->subproduct) {
                                    $subproduct = SubProduct::where('id', $child->subproduct_id)->first();
                                    SubProduct::where('id', $child->subproduct_id)->update(['stoc' => ($orderSets->qty + $subproduct->stoc)]);
                                }else{
                                    $product = Product::where('id', $child->product_id)->first();
                                    Product::where('id', $child->product_id)->update(['stock' => ($orderSets->qty + $product->stock)]);
                                }
                            }
                        }
                    }
                }
            }
        }

        return redirect()->back();
    }

    // get: delete order
    public function orderDelete($id)
    {
        $order = CRMOrders::where('id', $id)->first();

        $order->delete();
        $order->details()->delete();
        CRMOrderItem::where('order_id', $id)->delete();

        return redirect()->back();
    }


    /**
     * VALIDATE METHODS
    **/

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
                               ->orderBy('id', 'desc')
                               ->get();

         $data['subproducts'] = Cart::where('user_id', $userId)
                                 ->where('parent_id', null)
                                 ->where('subproduct_id', '!=', null)
                                 ->orderBy('id', 'desc')
                                 ->get();


         foreach ($data['products'] as $key => $product) {
             $this->validateProductStock($product);
         }
         foreach ($data['subproducts'] as $key => $subproduct) {
             $this->validateSubproductStock($subproduct);
         }
         foreach ($data['sets'] as $key => $set) {
             $this->validateSetStock($set);
         }
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

         $prodStock = Product::find($productCart->product_id)->stock;
         $stock_qty = ($prodStock - $prodCartsSum) > 0 ? $prodStock - $prodCartsSum : 0;
         $qty = $productCart->qty >= $stock_qty ? $stock_qty : $productCart->qty;

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

         $subprodStock = SubProduct::find($subproductCart->subproduct_id)->stoc;
         $stock_qty = ($subprodStock - $prodCartsSum) > 0 ? $subprodStock - $prodCartsSum : 0;
         $qty = $subproductCart->qty >= $stock_qty ? $stock_qty : $subproductCart->qty;

         $subproductCart->update(['stock_qty' => $stock_qty, 'qty' => $qty]);
     }

    // get updated carts of user
    private function getAllCarts($userId)
    {
        $this->validateStocks($userId);

        $data['sets'] = Cart::with(['children', 'set.price', 'set.translation', 'set.mainPhoto', 'set.products.translation', 'set.products.mainImage', 'set.products.firstSubproduct', 'set.products.subproducts.parameterValue.translation'])
                                      ->where('user_id', $userId)
                                      ->where('parent_id', null)
                                      ->where('set_id', '!=', 0)
                                      ->orderBy('id', 'desc')
                                      ->get();

        $data['products'] = Cart::with(['product.mainPrice', 'product.translation', 'product.mainImage'])
                                      ->where('user_id', $userId)
                                      ->where('parent_id', null)
                                      ->where('product_id', '!=', null)
                                      ->orderBy('id', 'desc')
                                      ->get();

        $data['subproducts'] = Cart::with(['subproduct.price', 'subproduct.product.translation', 'subproduct.product.mainImage', 'subproduct.parameterValue.translation'])
                                    ->where('user_id', $userId)
                                    ->where('parent_id', null)
                                    ->where('subproduct_id', '!=', null)
                                    ->orderBy('id', 'desc')
                                    ->get();

        return $data;
    }

}
