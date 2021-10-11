<?php

namespace App\Http\Controllers;

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

class order_old extends Controller
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

    // /**
    //  *  Post:: get order payment methods
    //  */
    // public function orderGetPayments(Request $request)
    // {
    //     $client = new \GuzzleHttp\Client();
    //
    //     $urlPaydo = "https://paydo.com/v1/instrument-settings/payment-methods/available-for-user";
    //     $urlPayop = "https://payop.com/v1/instrument-settings/payment-methods/available-for-user";
    //
    //     $tokenPaydo = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjM1MyIsImFjY2Vzc1Rva2VuIjoiYmY2M2FjYjY2YmExMWViZjNjNDYzNDhlIiwidG9rZW5JZCI6IjciLCJ3YWxsZXRJZCI6IjM1MyIsInRpbWUiOjE1Nzc3ODMwMzYsImV4cGlyZWRBdCI6MTU4NTY4ODQwMCwicm9sZXMiOltdfQ.rFmFOrKIzsin1pp-SV1Iru23lsIKMTly2SKGRAhzeOo';
    //     $tokenPayop = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjE2MTExIiwiYWNjZXNzVG9rZW4iOiJmYmE5ZTJkNTZhOWQwNGU5MDA2ODhiYmIiLCJ0b2tlbklkIjoiMTIxMyIsIndhbGxldElkIjoiODI2NCIsInRpbWUiOjE1OTQzNzYxNTAsImV4cGlyZWRBdCI6bnVsbCwicm9sZXMiOltdLCJ0d29GYWN0b3IiOnsicGFzc2VkIjpmYWxzZX19.3GcyOt8Qkyh3PmRzW7a-Y2A82jQ0EBjgYnn4cYaW7t8";
    //     // "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjE2MTExIiwiYWNjZXNzVG9rZW4iOiJmYmE5ZTJkNTZhOWQwNGU5MDA2ODhiYmIiLCJ0b2tlbklkIjoiMjA4Iiwid2FsbGV0SWQiOiI4MjY0IiwidGltZSI6MTU3Nzc4NjkxNSwiZXhwaXJlZEF0IjoxNTg1Njg4NDAwLCJyb2xlcyI6W119.toEBTY107EeiAAnXz1KEYiMf-q7RcBUS0LiwDPYOcNM";
    //
    //     $request = $client->get($urlPayop, [
    //         'headers' => [
    //                 'Authorization' =>  "Bearer {$tokenPayop}",
    //                 'Content-Type' => 'application/json',
    //             ]
    //         ]);
    //
    //     return $request->getBody()->getContents();
    // }

    // public function PMFinder($id)
    // {
    //     $payment = "Unknown";
    //
    //     switch ($id) {
    //         case '1':
    //             $payment = "Debit/Credit Cards";
    //             break;
    //         case '345':
    //             $payment = "iDeal";
    //             break;
    //         case '339':
    //             $payment = "Bancontact";
    //             break;
    //         default:
    //             $payment = "Cache";
    //             break;
    //     }
    //
    //     return $payment;
    // }

    // public function countAmount($order)
    // {
    //     $cart   = new CartController();
    //     $carts  = $cart->getCartItems();
    //     $amount = $order->delivery->price;
    //
    //     if ($this->country->iso == 'MD') {
    //         $currency = $this->currency;
    //         if (!is_null($currency)) {
    //             $amount = $amount * $currency->rate;
    //         }
    //     }
    //
    //     foreach ($carts['products'] as $key => $cartProduct) {
    //         if ($cartProduct->stock_qty > 0) {
    //             $amount += $cartProduct->product->mainPrice->price * $cartProduct->qty;
    //         }
    //     }
    //
    //     foreach ($carts['subproducts'] as $key => $cartSubproduct) {
    //         if ($cartSubproduct->stock_qty > 0) {
    //             $amount += $cartSubproduct->product->mainPrice->price * $cartSubproduct->qty;
    //         }
    //     }
    //
    //     $order->update(['ammount' => $amount]);
    // }

    public function orderPaymentMD($amount, $order, $payment)
    {
        if ($payment == 'maib') {
            $maibPayment = new MaibPayment();
            $maibPayment->pay($amount, $order, $payment);
        }else{
            dd('cash');
        }

    }

    // /**
    //  *  Get:: finish order and redirect to checkout api url
    //  *  Preorder Method2
    //  */
    // public function orderPaymentByMethod($methodId, $amount, $orderId, $payment)
    // {
    //     $user = $this->checkIfLogged();
    //
    //     $order = CRMOrders::where('id', $orderId)->first();
    //     $amount = $this->countAmount($order);
    //
    //     if ($methodId == 0) {
    //         return $this->orderPaymentMD($amount, $order, $payment);
    //     }
    //
    //     $paymentMethod = $this->PMFinder($methodId);
    //
    //     $order->update([
    //         'payment_id' => $paymentMethod,
    //         'label' => 'FB: reserved, PSP: Redirect to ' .$paymentMethod,
    //         'main_status' => 'preorders',
    //     ]);
    //
    //     $amount = number_format((float)$amount, 2, '.', '');
    //
    //     $this->finishOrder($order->id, $user);
    //
    //     if (!is_null($order)){
    //         try {
    //             // return redirect()->route('thanks', ['redirs' => 'success', 'checkout' => $order->id]);
    //                 if ($payment == 'paydo') {
    //                     $invoceId = $this->checkOutPayDo($methodId, $amount, $orderId);
    //                     return redirect('https://paydo.com/en/payment/invoice-preprocessing/'.$invoceId);
    //                 }else{
    //                     $invoceId = $this->checkOutPayOp($methodId, $amount, $orderId);
    //                     return redirect('https://payop.com/en/payment/invoice-preprocessing/'.$invoceId);
    //                 }
    //             } catch (\Exception $e) {
    //                 $this->errorFrisbo($order, 'Payment Provider error.');
    //                 return redirect()->back();
    //             }
    //     }else{
    //         abort(404);
    //     }
    // }

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
        // $amount = $amount - 15;
        if ($order->details->email == 'kashtan_78912@gmail.com') {
            $amount = 1.1;
        }
        // $amount = 1.1;
        $secretKey = 'cbd990f042dff3a2aa18886c';
        $orderHash = ['id' => $orderId, 'amount' => $amount, 'currency' => 'EUR'];
        ksort($orderHash, SORT_STRING);
        $dataSet = array_values($orderHash);
        $dataSet[] = $secretKey;

        $signature = hash('sha256', implode(':', $dataSet));

        $client = new \GuzzleHttp\Client();
        $url = "https://paydo.com/v1/invoices/create";

        $tokenPaydo = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjM1MyIsImFjY2Vzc1Rva2VuIjoiYmY2M2FjYjY2YmExMWViZjNjNDYzNDhlIiwidG9rZW5JZCI6IjciLCJ3YWxsZXRJZCI6IjM1MyIsInRpbWUiOjE1Nzc3ODMwMzYsImV4cGlyZWRBdCI6MTU4NTY4ODQwMCwicm9sZXMiOltdfQ.rFmFOrKIzsin1pp-SV1Iru23lsIKMTly2SKGRAhzeOo';

        $request = $client->post($url, [
            'headers' => [
                    'Authorization' =>  "Bearer {$tokenPaydo}",
                    'Content-Type' => 'application/json',
                ],
            'json' => [
                "publicKey" => 'application-2fd74b89-5a65-4df0-be0e-dcc2903f1013',
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

    // public function orderSuccess($orderId)
    // {
    //     $order = CRMOrders::where('id', $orderId)->first();
    //
    //     if ($order->step == 0) {
    //         foreach ($order->items as $key => $item) {
    //             if ($item->subproduct->stoc == 0) {
    //                 $order->update([
    //                     'step' => 2,
    //                     'main_status' => 'canceled',
    //                     'label' => 'PSP: expired, payed, FB: not in stoc',
    //                 ]);
    //
    //                 Session::flash('payError', trans('vars.Notifications.paymentEror'));
    //                 return redirect($this->lang->lang.'/cart');
    //             }
    //         }
    //     }
    //
    //     $user = $this->checkIfLogged();
    //
    //     // set promocode
    //     $promocode = Promocode::where('name', @$_COOKIE['promocode'])->first();
    //     $this->checkPromocode($promocode, $user);
    //     setcookie('promocode', '', time() + 10000000, '/');
    //
    //     if(Auth::guard('persons')->user()) {
    //         $userAuth = FrontUser::find(Auth::guard('persons')->id());
    //         $user_id = $userAuth->id;
    //         $promoType = PromocodeType::where('name', 'user')->first();
    //     } else {
    //         $user_id = 0;
    //         $promoType = PromocodeType::where('name', 'repeated')->first();
    //     }
    //
    //     $this->createPromocode($promoType, $user_id);
    //
    //     Cart::where('user_id', $user['user_id'])->delete();
    //
    //     //  send emails
    //     $email = $order->details->email;
    //     if($user['status'] == 'guest') {
    //         $data['user'] = FrontUserUnlogged::where('user_id', @$_COOKIE['user_id'])->first();
    //         $data['promocode'] = Promocode::where('user_id', 0)
    //                                     ->whereRaw('to_use < times')
    //                                     ->where('valid_to', '>', date('Y-m-d'))
    //                                     ->orderBy('id', 'desc')->first();
    //
    //         Mail::send('mails.order.guest', $data, function($message) use ($email){
    //             $message->to($email, trans('vars.Email-templates.subjectOrderEmail').' juliaallert.com' )->from('julia.allert.fashion@gmail.com')->subject(trans('vars.Email-templates.subjectOrderEmail').' juliaallert.com');
    //         });
    //     }else{
    //         $data['user'] = FrontUser::find(Auth::guard('persons')->id());
    //         $data['promocode'] = Promocode::where('user_id', $data['user']->id)
    //                                     ->whereRaw('to_use < times')
    //                                     ->where('valid_to', '>', date('Y-m-d'))
    //                                     ->orderBy('id', 'desc')->first();
    //
    //         Mail::send('mails.order.user', $data, function($message) use ($email){
    //             $message->to($email, trans('vars.Email-templates.subjectOrderEmail').' juliaallert.com' )->from('julia.allert.fashion@gmail.com')->subject( trans('vars.Email-templates.subjectOrderEmail').' juliaallert.com' );
    //         });
    //     }
    //
    //     $this->setOrderInvocedStatus($order->order_reference);
    //
    //     $order->update([
    //         'step' => 2,
    //         'main_status' => 'payed',
    //         'label' => 'FB: invoce created, PSP: payed by '. $order->payment_id,
    //     ]);
    //
    //     $this->synchronizeStocks();
    //
    //     $this->successOrder($order, 'Success Order');
    //
    //     return redirect()->route('thanks', ['redirs' => 'success', 'checkout' => $order->id]);
    // }

    // public function orderFail($orderId)
    // {
    //     $order = CRMOrders::find($orderId);
    //     $this->setOrderCanceledStatus($order->order_reference);
    //
    //     $order->update([
    //         'step' => 0,
    //         'label' => 'FB: canceled, PSP: No success '. $order->payment_id,
    //         'main_status' => 'canceled',
    //     ]);
    //
    //     $this->synchronizeStocks();
    //
    //     Session::flash('payError', trans('vars.Notifications.paymentEror'));
    //     return redirect($this->lang->lang.'/cart');
    // }

    // /**
    //  *   Private:: finish order
    //  */
    // public function finishOrder($orderId, $user)
    // {
    //     $cart = new CartController();
    //     $carts = $cart->getCartItems();
    //     $order = CRMOrders::find($orderId);
    //
    //     if ($this->country->iso == "MD") {
    //         $currency = Currency::where('abbr', 'MDL')->first();
    //     }else{
    //         $currency = Currency::where('abbr', 'EUR')->first();
    //     }
    //
    //     if (count($carts['subproducts']) > 0) {
    //         foreach ($carts['subproducts'] as $key => $subProduct) {
    //             if ($subProduct->stock_qty > 0) {
    //                 CRMOrderItem::create([
    //                     'order_id'      => $order->id,
    //                     'subproduct_id' => $subProduct->subproduct_id,
    //                     'product_id'    => $subProduct->product_id,
    //                     'qty'           => $subProduct->qty,
    //                     'discount'      => $subProduct->product->discount,
    //                     'code'          => $subProduct->subproduct->code,
    //                     'old_price'     => $subProduct->product->mainPrice->old_price,
    //                     'price'         => $subProduct->product->mainPrice->price,
    //                     'currency'      => $currency->abbr,
    //                 ]);
    //             }
    //         }
    //     }
    //
    //     if (count($carts['products']) > 0) {
    //         foreach ($carts['products'] as $key => $product) {
    //             if ($product->stock_qty > 0) {
    //                 CRMOrderItem::create([
    //                     'order_id'      => $order->id,
    //                     'subproduct_id' => 0,
    //                     'product_id'    => $product->product_id,
    //                     'qty'           => $product->qty,
    //                     'discount'      => $product->product->discount,
    //                     'code'          => $product->code,
    //                     'old_price'     => $product->product->mainPrice->old_price,
    //                     'price'         => $product->product->mainPrice->price,
    //                     'currency'      => $currency->abbr,
    //                 ]);
    //             }
    //         }
    //     }
    //
    //     // frisbo sinchornizate
    //     try {
    //         $this->sentOrderToFrisbo($order);
    //     } catch (\Exception $e) {
    //         $this->errorFrisbo($order, 'The order was not sent to frisbo.');
    //     }
    //
    //     return $order->id;
    // }

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
             // 'payment_id'        => $request->get('payment'),
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
    private function checkPromocode($promocode, $user)
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
    private function createPromocode($promoType, $userId) {
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

        // $data['sets'] = Cart::with(['children', 'set.price', 'set.translation', 'set.mainPhoto', 'set.products.translation', 'set.products.mainImage', 'set.products.firstSubproduct', 'set.products.subproducts.parameterValue.translation'])
        //                               ->where('user_id', $userId)
        //                               ->where('parent_id', null)
        //                               ->where('set_id', '!=', 0)
        //                               ->orderBy('id', 'desc')
        //                               ->get();

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
         // foreach ($data['subproducts'] as $key => $subproduct) {
         //     $this->validateSubproductStock($subproduct);
         // }
         // foreach ($data['sets'] as $key => $set) {
         //     $this->validateSetStock($set);
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
               ->orderBy('id', 'desc')->first();


       // CRMOrders::where('id', $order->id)->update([
       //     'fbq' => 1,
       // ]);
        //
        // if(count($promocode) > 0) {
        //     $products = Product::where('created_at', '>=', date('Y-m-d', strtotime('-15 days')))
        //                ->orderBy('created_at', 'desc')
        //                ->limit(5)
        //                ->get();

            return view('front.'. $this->device .'.dynamic.thanks', compact('user', 'promocode', 'order'));
        }else{
            return view('front.'. $this->device .'.dynamic.thanks', compact('user', 'promocode', 'order'));
        }
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


     // /*****************************************
     //  *
     //  *  Frisbo conections
     //  *
     //  ****************************************/
     // public function sentOrderToFrisbo($order)
     // {
     //     $this->frisboLogin();
     //
     //     $orderProducts = [];
     //
     //     $amount = 0;
     //
     //     if ($order->orderSubproducts()->count() > 0) {
     //         foreach ($order->orderSubproducts as $key => $subproductItem) {
     //             $orderProducts[$subproductItem->code] = [
     //                 "sku" => $subproductItem->code,
     //                 "name" => $subproductItem->subproduct->product->translation->name,
     //                 "price" => $subproductItem->subproduct->product->mainPrice->price / 1.19,
     //                 "quantity" => $subproductItem->qty,
     //                 "vat" => 19,
     //                 "discount" => $subproductItem->subproduct->product->discount
     //             ];
     //             $amount += $subproductItem->subproduct->product->mainPrice->price;
     //         }
     //     }
     //
     //     if ($order->orderSets()->count() > 0) {
     //         foreach ($order->orderSets as $key => $setItem) {
     //
     //             if ($setItem->children()->count() > 0) {
     //                 foreach ($setItem->children as $key => $subproductSet) {
     //                     if (array_key_exists($subproductSet->subproduct->code, $orderProducts)) {
     //                         $orderProducts[$subproductSet->subproduct->code] = [
     //                             "sku" => $subproductSet->subproduct->code,
     //                             "name" => $subproductSet->subproduct->product->translation->name,
     //                             "price" => $subproductSet->subproduct->product->mainPrice->price / 1.19,
     //                             "quantity" => $orderProducts[$subproductSet->subproduct->code]['quantity'] + 1,
     //                             "vat" => 19,
     //                             "discount" => $subproductSet->subproduct->product->discount
     //                         ];
     //                         $amount += $subproductSet->subproduct->product->mainPrice->price;
     //
     //                     }else{
     //                         $orderProducts[$subproductSet->subproduct->code] = [
     //                             "sku" => $subproductSet->subproduct->code,
     //                             "name" => $subproductSet->subproduct->product->translation->name,
     //                             "price" => $subproductSet->subproduct->product->mainPrice->price / 1.19,
     //                             "quantity" => 1,
     //                             "vat" => 19,
     //                             "discount" => $subproductSet->subproduct->product->discount
     //                         ];
     //                         $amount += $subproductSet->subproduct->product->mainPrice->price;
     //                     }
     //
     //                 }
     //             }
     //         }
     //     }
     //
     //     $client = new Client();
     //     $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImtpZCI6IlFqRTROa00zTnprNFJVSTROalU0UVVSRFFUUkNOak13UVRBd1JrVXhRamxDT1RRNU1rWTJNdyJ9.eyJpc3MiOiJodHRwczovL2ZyaXNiby1yby5ldS5hdXRoMC5jb20vIiwic3ViIjoiYXV0aDB8Mjc2IiwiYXVkIjpbImh0dHBzOi8vYXBpLmZyaXNiby5ybyIsImh0dHBzOi8vZnJpc2JvLXJvLmV1LmF1dGgwLmNvbS91c2VyaW5mbyJdLCJpYXQiOjE1NzQwNjc5NzYsImV4cCI6MTU3NDE1NDM3NiwiYXpwIjoiMkNoeUgyeXNRYThOdHdEV0tFem1oekpfa2p5cHlkMk0iLCJzY29wZSI6Im9wZW5pZCBwcm9maWxlIGVtYWlsIiwiZ3R5IjoicGFzc3dvcmQifQ.gFxzikSw_FArUwhVhQsMDFoIF685IroIAZC5D8Yz92pcvF1CpLnI8_idONBqaV24Mstxj5qtsyJEzs0hbK9QCNsgQtPD7DSTdsVy_VwN2CWKlvyPZr7xs2nBDo4yTuhmYMxUaoY_waeVYQ-at04Cr8EjrXq8scypyIgwfw5NEV9Z1nyNlNdl2-EOIfMRvXt4iasriB0gCyiHo5uCO_pBp5noHFBDo1tV6A6EDV6ljHn8MTGFoywJKT1Y1oBLk6ZiUDqgOpnJ4l5RyYdGCbAso2DXe4b7lA64mkXPmFeoP7TUZ1nn0Z2nR7mULdRoTxNkKtLNJgxJaq269A9k8tnyVQ";
     //
     //     $url = "https://api.frisbo.ro/v1/organizations/183/orders";
     //
     //     $request = $client->post($url,[
     //         'headers' => [
     //                 'Authorization' =>  "Bearer {$this->token}",
     //                 'Content-Type' => 'application/json'
     //         ],
     //        'json' => [
     //                 "order_reference"     => $order->order_hash.uniqid(),
     //                 "organization_id"     => 183,
     //                 "channel_id"          => 315,
     //                 "warehouse_id"        => 282,
     //                 "status"              => "open",
     //                 "reason_status"       => null,
     //                 "ordered_date"        => null,
     //                 "delivery_date"       => null,
     //                 "returned_date"       => null,
     //                 "canceled_date"       => null,
     //                 "notes"               => "",
     //                 "shipped_with"        => "Unknown",
     //                 "shipped_date"        => null,
     //                 "preferred_delivery_time" => null,
     //                 "shipping_customer"   => [
     //                   "email"         => $order->details->email,
     //                   "first_name"    => $order->details->contact_name,
     //                   "last_name"     => $order->details->contact_name,
     //                   "phone"         => '+'. $order->details->code .' '. $order->details->phone
     //                 ],
     //                 "shipping_address" => [
     //                   "street"    => $order->details->address,
     //                   "city"      => $order->details->city,
     //                   "county"    => $order->details->region ?? 'Unknown',
     //                   "country"   => $order->details->country,
     //                   "zip"       => $order->details->zip
     //                ],
     //                 "billing_customer"   => [
     //                   "email"     => $order->details->email,
     //                   "first_name" => $order->details->contact_name,
     //                   "last_name" => $order->details->contact_name,
     //                   "phone"     => '+'. $order->details->code .' '. $order->details->phone,
     //                   "trade_register_registration_number" =>"2063080",
     //                   "vat_registration_number" =>"J27/1037/1991"
     //               ],
     //                 "billing_address" => [
     //                   "street"    => $order->details->address,
     //                   "city"      => $order->details->city,
     //                   "county"    => $order->details->region ?? 'Unknown',
     //                   "country"   => $order->details->country,
     //                   "zip"       => $order->details->zip
     //               ],
     //                 "discount" => $amount - ($order->amount - 15),
     //                 "transport_tax" => "15",
     //                 "cash_on_delivery" => 1,
     //                 "products" => $orderProducts
     //             ]
     //        ]);
     //
     //
     //    $response = json_decode($request->getBody()->getContents());
     //
     //    $order->update([
     //        'order_reference' => $response->order_id,
     //    ]);
     //
     //    // $this->frisboLogin();
     //    $this->synchronizeStocks();
     // }

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

     // // Synchronize Subproduct Stocks
     // public function synchronizeStocks($page = 1)
     // {
     //     $frisboController = new FrisboController();
     //     $frisboController->synchronizeStocks();
     //
     //     // $client = new Client();
     //     //
     //     // $getProductsUrl = "https://api.frisbo.ro/v1/organizations/183/products?page=".$page;
     //     //
     //     // $request = $client->get($getProductsUrl, [
     //     //     'headers' => [
     //     //             'Authorization' =>  "Bearer {$this->token}"
     //     //         ]
     //     //     ]);
     //     //
     //     // $response = json_decode($request->getBody()->getContents());
     //     //
     //     // $i = 0;
     //     // if ($response->data) {
     //     //     foreach ($response->data as $key => $responseProduct) {
     //     //         if (count($responseProduct->storage) > 0) {
     //     //             Subproduct::where('code', $responseProduct->sku)->update(['stoc' => $responseProduct->storage[0]->available_stock]);
     //     //             $i++;
     //     //         }else{
     //     //             Subproduct::where('code', $responseProduct->sku)->update(['stoc' => 0]);
     //     //         }
     //     //     }
     //     // }
     //     //
     //     // if (count($response->data) == 100) {
     //     //     $page = $page + 1;
     //     //     $this->synchronizeStocks($page);
     //     // }
     // }

     // // ________________________________________________________________________
     // // Invoced status frisbo
     // public function setOrderInvocedStatus($orderId)
     // {
     //     try {
     //         $this->frisboLogin();
     //
     //         $client = new Client();
     //
     //         $url = "https://api.frisbo.ro/v1/organizations/183/orders/".$orderId."/process";
     //
     //         $request = $client->post($url,[
     //             'headers' => [
     //                     'Authorization' =>  "Bearer {$this->token}",
     //                 ]
     //            ]);
     //     } catch (\Exception $e) {
     //         $data['order'] = CRMOrders::where('order_reference', $orderId)->first();
     //         $data['problem'] = "Error on set order invoiced status.";
     //
     //         $data['orderProducts'] = CRMOrderItem::where('order_id', $data['order']->id)
     //                                     ->where('parent_id', 0)
     //                                     ->where('subproduct_id', 0)
     //                                     ->where('product_id', '!=', 0)
     //                                     ->get();
     //
     //         $data['orderSubproducts'] = CRMOrderItem::where('order_id', $data['order']->id)
     //                                     ->where('parent_id', 0)
     //                                     ->where('subproduct_id', '!=', 0)
     //                                     ->get();
     //
     //         $data['orderSets'] = CRMOrderItem::where('order_id', $data['order']->id)
     //                                     ->where('parent_id', 0)
     //                                     ->where('set_id', '!=', 0)
     //                                     ->get();
     //
     //         $email = 'itmalles@gmail.com';
     //
     //         Mail::send('mails.order.adminExeption', $data, function($message) use ($email){
     //             $message->to($email, 'Frisbo route 2 error juliaallert.com' )->from('julia.allert.fashion@gmail.com')->subject('Frisbo route 2 error juliaallert.com');
     //         });
     //     }
     // }
     //
     // // Canceled status frisbo
     // public function setOrderCanceledStatus($orderId)
     // {
     //     $this->frisboLogin();
     //
     //     $client = new Client();
     //
     //     $url_deleted = "https://api.frisbo.ro/v1/organizations/183/orders/". $orderId;
     //
     //     $request = $client->delete($url_deleted,[
     //         'headers' => [
     //                 'Authorization' =>  "Bearer {$this->token}",
     //             ]
     //        ]);
     // }

     // public function errorFrisbo($order, $problem)
     // {
     //     $data['order'] = $order;
     //     $data['problem'] = $problem;
     //
     //     $data['orderProducts'] = CRMOrderItem::where('order_id', $data['order']->id)
     //                                 ->where('parent_id', 0)
     //                                 ->where('subproduct_id', 0)
     //                                 ->where('product_id', '!=', 0)
     //                                 ->get();
     //
     //     $data['orderSubproducts'] = CRMOrderItem::where('order_id', $data['order']->id)
     //                                 ->where('parent_id', 0)
     //                                 ->where('subproduct_id', '!=', 0)
     //                                 ->get();
     //
     //     $data['orderSets'] = CRMOrderItem::where('order_id', $data['order']->id)
     //                                 ->where('parent_id', 0)
     //                                 ->where('set_id', '!=', 0)
     //                                 ->get();
     //
     //     $email = 'itmalles@gmail.com';
     //
     //     if ($problem == 'Payment Provider error.') {
     //         Mail::send('mails.order.adminExeption', $data, function($message) use ($email){
     //             $message->to($email, 'Error Payment Provider juliaallert.com' )->from('julia.allert.fashion@gmail.com')->subject('Error Payment Provider juliaallert.com');
     //         });
     //     }else{
     //         Mail::send('mails.order.adminExeption', $data, function($message) use ($email){
     //             $message->to($email, 'Frisbo route 1 error juliaallert.com' )->from('julia.allert.fashion@gmail.com')->subject('Frisbo route 1 error juliaallert.com');
     //         });
     //     }
     // }

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
         // $email = 'iovitatudor@gmail.com';


         Mail::send('mails.order.adminExeption', $data, function($message) use ($email){
             $message->to($email, 'Success Order juliaallert.com' )->from('julia.allert.fashion@gmail.com')->subject('Success order juliaallert.com');
         });
     }

     public function paymentCollback()
     {
         // code...
     }
}
