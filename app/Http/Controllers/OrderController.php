<?php

namespace App\Http\Controllers;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Controller;
use Admin\Http\Controllers\FrisboController;
use GuzzleHttp\Client;
use App\FrontUser;
use App\Models\Cart;
use App\Models\FrontUserUnlogged;
use App\Models\FrontUserAddress;
use App\Models\Promocode;
use App\Models\PromocodeType;
use App\Models\Set;
use App\Models\Product;
use App\Models\SubProduct;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Payment;
use App\Models\Delivery;
use App\Models\CRMOrders;
use App\Models\CRMOrderItem;
use App\Models\FeedBack;
use Session;

use App\Http\Controllers\MaibApi\MaibPayment;

class OrderController extends Controller
{
    public $token;

    /**
     *   Order Shiping Page render
     */
    public function order()
    {
        $message = false;
        $user = $this->checkIfLogged();
        $cartSubprods = Cart::where('user_id', $user['user_id'])
                            ->where('parent_id', null)
                            ->where('subproduct_id', '!=', null)
                            ->get();

        $cartSets = Cart::where('user_id', $user['user_id'])
                          ->where('parent_id', null)
                          ->where('set_id', '!=', 0)
                          ->get();

        foreach ($cartSubprods as $key => $cartSubprod) {
            if ($cartSubprod->stock_qty == 0) {
                Cart::where('id', $cartSubprod->id)->delete();
                $message = "Unu sau mai multe produse din cos au fost deja cumparate";
            }
        }

        foreach ($cartSets as $key => $cartSet) {
            if ($cartSet->stock_qty == 0) {
                Cart::where('id', $cartSubprod->id)->delete();
                $message = "Unu sau mai multe seturi din cos au fost deja cumparate";
            }
        }
        $message = "Unu sau mai multe seturi din cos au fost deja cumparate";

        $cart = new CartController();
        $carts = $cart->getCartItems();

        return view('front.'. $this->device .'.dynamic.order-shipping', compact('message', 'carts'));
    }

    /**
     *  Order Payment Page render
     */
    public function orderPayment($orderId)
    {
        $user = $this->checkIfLogged();

        if ($user['status'] == 'guest') {
            $order = CRMOrders::where('id', $orderId)->where('guest_user_id',  $user['guest_id'])->where('step', 1)->first();
        }else{
            $order = CRMOrders::where('id', $orderId)->where('user_id', $user['user_id'])->where('step', 1)->first();
        }

        $cart = new CartController();
        $carts = $cart->getCartItems();

        if (!is_null($order)) {
            return view('front.'. $this->device .'.dynamic.order-payment', compact('order', 'carts'));
        }else{
            abort(404);
        }
    }

    /**
     *   Post:: get user full info
     */
    public function getUser(Request $request)
    {
        $data['payment_id'] = 0;

        if (\Auth::guard('persons')->user()){
            $country = Country::find(@$_COOKIE['country_id']);
            $data['mode'] = "auth";
            $data['frontUser'] = FrontUser::with([
                                'address.getCountryById',
                                'address.getCountryById.translation',
                                'address.getCountryById.deliveries',
                                'address.getCountryById.mainDelivery',
                                'address.getCountryById.payments',
                                'address.getCountryById.payments.payment.translation'
                            ])
                            ->find(Auth::guard('persons')->id());

            if (!is_null($data['frontUser']->address)) {
                $data['phone_code'] = $data['frontUser']->address->phone_code;
                $data['phone'] = $data['frontUser']->address->phone;
            }else{
                $data['phone_code'] = $country->phone_code;
                $data['phone'] = $data['frontUser']->phone;
            }
            $data['payment_id'] = $data['frontUser']->payment_id;
        }else{
            $country = Country::find(@$_COOKIE['country_id']);
            $data['mode'] = "guest";
            $data['country'] = $country->id;
            $data['frontUser'] = FrontUserUnlogged::where('user_id', @$_COOKIE['user_id'])->first();
            $data['phone_code'] = $country->phone_code;
            $data['phone'] = $data['frontUser']->phone;
        }

        if (@$_COOKIE['country_delivery_id']) {
            $data['country'] = @$_COOKIE['country_delivery_id'];
        }

        return $data;
    }

    /**
     *  Post:: event on change country
     */
    public function changeCountry(Request $request)
    {
        return Country::with([
                        'translation',
                        'deliveries.delivery.translation',
                        'mainDelivery',
                        'payments.payment.translation'
                    ])
                    ->where('id', $request->get('countryId'))
                    ->where('active', 1)
                    ->first();
    }

    public function orderPaymentMD($amount, $order, $payment)
    {
        if ($payment == 'maib') {
            $maibPayment = new MaibPayment();
            $maibPayment->pay($amount, $order, $payment);
        }else{
            dd('cash');
        }

    }

    public function checkOutPayOp($methodId, $amount, $orderId)
    {
        // $amount = 0.31;
        // $amount = 1.01;
        $secretKey = '7b75335711fc1f6a347bb831';
        $orderHash = ['id' => $orderId, 'amount' => $amount, 'currency' => 'EUR'];
        ksort($orderHash, SORT_STRING);
        $dataSet = array_values($orderHash);
        $dataSet[] = $secretKey;

        $signature = hash('sha256', implode(':', $dataSet));

        $client = new \GuzzleHttp\Client();
        $url = "https://payop.com/v1/invoices/create";

        $tokenPayop = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjE2MTExIiwiYWNjZXNzVG9rZW4iOiJmYmE5ZTJkNTZhOWQwNGU5MDA2ODhiYmIiLCJ0b2tlbklkIjoiMjA4Iiwid2FsbGV0SWQiOiI4MjY0IiwidGltZSI6MTU3Nzc4NjkxNSwiZXhwaXJlZEF0IjoxNTg1Njg4NDAwLCJyb2xlcyI6W119.toEBTY107EeiAAnXz1KEYiMf-q7RcBUS0LiwDPYOcNM";

        $order = CRMOrders::find($orderId);

        $request = $client->post($url, [
            'headers' => [
                    'Authorization' =>  "Bearer {$tokenPayop}",
                    'Content-Type' => 'application/json',
                ],
            'json' => [
                "publicKey" => 'application-1a421d0a-3ecc-42b5-9429-650b7fae882a',
                "order" => [
                        "id" => $orderId,
                        "amount" => $amount,
                        "currency" =>  "EUR",
                        "items" => [
                            [
                               "id" => "1",
                               "name" => "ds",
                               "price" => $amount
                           ],
                        ],
                        "description" => ""
                    ],
                    "signature" => $signature,
                    "payer" => [
                        "email" => $order->details->email,
                        "phone" => '+'. $order->details->code .' '. $order->details->phone,
                        "name" => $order->details->contact_name,
                        "extraFields" => []
                    ],
                    "paymentMethod" => $methodId,
                    "language" => "en",
                    "resultUrl" => url($this->lang->lang.'/order/payment/success/'.$orderId),
                    "failPath" =>  url($this->lang->lang.'/order/payment/fail/'.$orderId),
                ],
        ]);

        $invoceId = json_decode($request->getBody()->getContents());

        return $invoceId->data;
    }

    public function checkOutPayDo($methodId, $amount, $orderId)
    {
        $order = CRMOrders::find($orderId);

        if ($order->details->email == 'kashtan_78912@gmail.com') {
            $amount = 1.1;
        }

        $secretKey = 'a4e9259c213ef4e0add2f9c4';
        $orderHash = ['id' => $orderId, 'amount' => $amount, 'currency' => 'EUR'];
        ksort($orderHash, SORT_STRING);
        $dataSet = array_values($orderHash);
        $dataSet[] = $secretKey;

        $signature = hash('sha256', implode(':', $dataSet));

        $client = new \GuzzleHttp\Client();
        $url = "https://paydo.com/v1/invoices/create";

        $tokenPaydo = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjM1NCIsImFjY2Vzc1Rva2VuIjoiYmY2M2FjYjY2YmExMWViZjNjNDYzNDhlIiwidG9rZW5JZCI6IjU0Iiwid2FsbGV0SWQiOiIzNTQiLCJ0aW1lIjoxNTk1MDk3MDU5LCJleHBpcmVkQXQiOm51bGwsInJvbGVzIjpbXSwidHdvRmFjdG9yIjp7InBhc3NlZCI6ZmFsc2V9fQ.bBkxi7Qve4KmCfizKtAJVQtpVkCP6sY5Vyc7P9yXbnI';

        $request = $client->post($url, [
            'headers' => [
                    'Authorization' =>  "Bearer {$tokenPaydo}",
                    'Content-Type' => 'application/json',
                ],
            'json' => [
                "publicKey" => 'application-61c29b2e-e99e-45ea-88d9-1712b9c55bc6',
                "order" => [
                        "id" => $orderId,
                        "amount" => $amount,
                        "currency" =>  "EUR",
                        "items" => [
                            [
                               "id" => "1",
                               "name" => "ds",
                               "price" => $amount
                           ],
                        ],
                        "description" => ""
                    ],
                    "signature" => $signature,
                    "payer" => [
                        "email" => $order->details->email,
                        "phone" => '+'. $order->details->code .' '. $order->details->phone,
                        "name" => $order->details->contact_name,
                        "extraFields" => []
                    ],
                    "paymentMethod" => $methodId,
                    "language" => "en",
                    "resultUrl" => url($this->lang->lang.'/order/payment/success/'.$orderId),
                    "failPath" =>  url($this->lang->lang.'/order/payment/fail/'.$orderId),
                ],
        ]);

        $invoceId = json_decode($request->getBody()->getContents());

        return $invoceId->data;
    }

    /**
     *  Order shipping render page
     *  Preorder Method1
     */
     public function orderShipping(Request $request)
     {
         $user = $this->checkIfLogged();
         $userAddress = 0;

         $country    = Country::find($request->get('country'));
         $currency   = Currency::where('abbr', $request->get('cartData')['currency'])->first();
         $delivery   = Delivery::find($request->get('cartData')['delivery']);
         $payment    = Payment::find($request->get('payment'));
         $promocode  = Promocode::where('name', @$request->get('cartData')['promocode'])->first();

         // set empty data of users
         if ($user['status'] == 'auth') {
             $frontUser = FrontUser::find($user['user_id']);
             FrontUser::where('id', $frontUser->id)->update([
                 'name' => $frontUser->name ? $frontUser->name : $request->get('name'),
                 'email' => $frontUser->email ? $frontUser->email : $request->get('email'),
                 'phone' => $frontUser->phone ? $frontUser->phone : $request->get('phone'),
                 'code' => $frontUser->code ? $frontUser->code : $request->get('code'),
             ]);
         }else{
             $frontGuest = FrontUserUnlogged::where('user_id', $user['user_id'])->first();
             FrontUserUnlogged::where('id', $frontGuest->id)->update([
                 'name' => $frontGuest->name ? $frontGuest->name : $request->get('name'),
                 'email' => $frontGuest->email ? $frontGuest->email : $request->get('email'),
                 'phone' => $frontGuest->phone ? $frontGuest->phone : $request->get('phone'),
                 'code' => $frontGuest->code ? $frontGuest->code : $request->get('code'),
             ]);
         }

         // set default address
         if ($request->get('saveAddress') && $user['status'] !== 'guest'){
             if ($request->get('defaultPayment')){
                 FrontUser::where('id', $user['user_id'])->update([
                     'payment_id' => $request->get('payment')
                 ]);
             }else{
                 FrontUser::where('id', $user['user_id'])->update([
                     'payment_id' => 0
                 ]);
             }

             FrontUserAddress::where('front_user_id', $user['user_id'])->delete();
             $userAddress = FrontUserAddress::create([
                 'front_user_id' => $user['user_id'],
                 'country'       => $request->get('country'),
                 'region'        => $request->get('region'),
                 'location'      => $request->get('city'),
                 'address'       => $request->get('address'),
                 'code'          => $request->get('zip'),
                 'homenumber'    => $request->get('apartment'),
                 'phone_code'    => $request->get('phone_code'),
                 'phone'         => $request->get('phone'),
             ]);
         }



         // create order
         $ordersCount = CRMOrders::get();
         $order = CRMOrders::create([
             'order_hash'        => 2000 + $ordersCount->count(),
             'user_id'           => $user['status'] == 'auth' ? $user['user_id'] : 0,
             'guest_user_id'     => $user['status'] == 'guest' ? $user['guest_id'] : 0,
             'address_id'        => $userAddress ? $userAddress->id : 0,
             'promocode_id'      => !is_null($promocode) ? $promocode->id : null,
             'currency_id'       => !is_null($currency) ? $currency->id : null,
             'delivery_id'       => $request->get('cartData')['delivery'],
             'country_id'        => $request->get('country'),
             'amount'            => $request->get('cartData')['amount'],
             'main_status'       => 'preorders',
             'change_status_at'  => date('Y-m-d'),
             'step'              => 1,
             'label'             => 'With shipping details',
         ]);

         // create order details
         $order->details()->create([
             'contact_name'      => $request->get('name'),
             'email'             => $request->get('email'),
             'promocode'         => !is_null($promocode) ? $promocode->name : null,
             'code'              => $request->get('phone_code'),
             'phone'             => $request->get('phone'),
             'currency'          => @$currency->abbr,
             'payment'           => @$payment->translation->name,
             'delivery'          => @$delivery->translation->name,
             'country'           => @$country->translation->name,
             'region'            => $request->get('region'),
             'city'              => $request->get('city'),
             'address'           => $request->get('address'),
             'apartment'         => $request->get('apartment'),
             'zip'               => $request->get('zip'),
             'delivery_price'    => @$delivery->price,
             'tax_price'         => $request->get('cartData')['tax'],
         ]);

         return $order->id;
     }

    /**
     *   Private:: check promocode
     */
    public function checkPromocode($promocode, $user)
    {
        if (!is_null($promocode)) {
            // if ($promocode->status == 'valid') {
                $promocode->update([
                    'status' => 'used',
                    'to_use' => $promocode->to_use + 1,
                ]);
            // }
        }

        setcookie('promocode', '', time() + 10000000, '/');
    }

    /**
     *  private method
     *  Create promocode
     */
    public function createPromocode($promoType, $userId) {
        if (!is_null($promoType)) {
            $promocode = Promocode::create([
              'user_id' => $userId,
              'name' => $promoType->name.''.str_random(5),
              'type_id' => $promoType->id,
              'discount' => $promoType->discount,
              'valid_from' => date('Y-m-d'),
              'valid_to' => date('Y-m-d', strtotime(' + '.$promoType->period.' days')),
              'period' => $promoType->period,
              'treshold' => $promoType->treshold,
              'to_use' => 0,
              'times' => $promoType->times,
              'status' => 'valid',
              'user_id' => $userId
            ]);

            return $promocode;
        }
    }

    // get updated carts of user
    private function getAllCarts($userId)
    {
        $this->validateStocks($userId);

        $data['products'] = Cart::with(['product.mainPrice', 'product.translation', 'product.mainImage'])
                                      ->where('user_id', $userId)
                                      ->where('parent_id', null)
                                      ->where('product_id', '!=', null)
                                      ->orderBy('id', 'desc')
                                      ->get();

        $data['subproducts'] = Cart::with(['subproduct.price', 'subproduct.product.translation', 'subproduct.product.mainImage', 'subproduct.parameterValue.translation'])
                                    ->where('user_id', $userId)
                                    ->where('parent_id', null)
                                    ->where('subproduct_id', '!=', 0)
                                    ->orderBy('id', 'desc')
                                    ->get();

        return $data;
    }

    private function checkIfLogged()
    {
        if(auth('persons')->guest()) {
            $guest = FrontUserUnlogged::where('user_id', @$_COOKIE['user_id'])->first();
            if (!is_null($guest)) {
                return array('is_logged' => 1, 'user_id' => @$_COOKIE['user_id'], 'status' => 'guest', 'guest_id' => $guest->id);
            }else{
                return array('is_logged' => 0, 'user_id' => @$_COOKIE['user_id'], 'status' => 'user');
            }
        }else{
            return array('is_logged' => 1, 'user_id' => auth('persons')->id(), 'status' => 'auth');
        }
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
         $qty       = $productCart->qty >= $stock_qty ? $stock_qty : $productCart->qty;

         $productCart->update(['stock_qty' => $stock_qty, 'qty' => $qty]);
     }

     /**
      *  get action
      *  Render thank you page
      */
     public function thanks(Request $request)
     {
         if (!$request->get('redirs')) { return redirect('/');}
         if (!$request->get('checkout')) { return redirect('/');}

        if(Auth::guard('persons')->guest()) {
            $user = FrontUserUnlogged::where('user_id', @$_COOKIE['user_id'])->first();
            $order = CRMOrders::where('guest_user_id', $user->id)->where('id', $request->get('checkout'))->orderBy('id', 'desc')->first();
            $user_id = 0;
        }else{
            $user = FrontUser::find(Auth::guard('persons')->id());
            $order = CRMOrders::where('user_id', $user->id)->where('id', $request->get('checkout'))->orderBy('id', 'desc')->first();
            $user_id = $user->id;
        }

        $promocode = Promocode::where('user_id', $user_id)
                                ->whereRaw('to_use < times')
                                ->where('valid_to', '>', date('Y-m-d'))
                                ->orderBy('id', 'desc')
                                ->first();


       // CRMOrders::where('id', $order->id)->update([
       //     'fbq' => 1,
       // ]);
        //
        // dd($promocode);
        // if(count($promocode) > 0) {
        //     $products = Product::where('created_at', '>=', date('Y-m-d', strtotime('-15 days')))
        //                ->orderBy('created_at', 'desc')
        //                ->limit(5)
        //                ->get();

            return view('front.'. $this->device .'.dynamic.thanks', compact('user', 'promocode', 'order'));
        // }else{
        //     return view('front.'. $this->device .'.dynamic.thanks', compact('user', 'promocode', 'order'));
        // }
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

     // Auth Frisbo
     public function frisboLogin()
     {
         $loginUrl = "https://api.frisbo.ro/v1/auth/login";

         $client = new Client();

         $request = $client->post($loginUrl, [
             'form_params' => [
                     'email' =>  "itmalles@gmail.com",
                     'password' =>  "ItMallFrisbo2019",
                 ]
             ]);

         $response = json_decode($request->getBody()->getContents());

         $this->token = $response->access_token;
     }



     public function successOrder($order, $problem)
     {
         $data['order'] = $order;
         $data['problem'] = $problem;

         $data['orderProducts'] = CRMOrderItem::where('order_id', $data['order']->id)
                                     ->where('parent_id', 0)
                                     ->where('subproduct_id', 0)
                                     ->where('product_id', '!=', 0)
                                     ->get();

         $data['orderSubproducts'] = CRMOrderItem::where('order_id', $data['order']->id)
                                     ->where('parent_id', 0)
                                     ->where('subproduct_id', '!=', 0)
                                     ->get();

         $data['orderSets'] = CRMOrderItem::where('order_id', $data['order']->id)
                                     ->where('parent_id', 0)
                                     ->where('set_id', '!=', 0)
                                     ->get();

         $email = 'itmalles@gmail.com';


         Mail::send('mails.order.adminExeption', $data, function($message) use ($email){
             $message->to($email, 'Success Order juliaallert.com' )->from('julia.allert.fashion@gmail.com')->subject('Success order juliaallert.com');
         });
     }

     public function paymentCollback()
     {
         $paymentController = new PaymentController();
         return $paymentController->orderSuccess($orderId);
     }
 }
