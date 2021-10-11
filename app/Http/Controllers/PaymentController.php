<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CartController;
use Admin\Http\Controllers\FrisboController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaypalController;

use GuzzleHttp\Client;
use App\Base as Model;
use App\Models\FrontUserUnlogged;
use App\Models\FrontUser;
use App\Models\Currency;
use App\Models\CRMOrders;
use App\Models\CRMOrderItem;
use App\Models\Cart;
use App\Models\Promocode;
use App\Models\PromocodeType;
use App\Models\Country;
use Session;
use PDF;

class PaymentController extends Controller
{
    private $token;

    /**
     *  Post:: get payment methods (payop)
     */
    public function getPaymentMethods(Request $request)
    {
        try {
            $client = new \GuzzleHttp\Client();

            $urlPaydo = "https://paydo.com/v1/instrument-settings/payment-methods/available-for-user";
            $urlPayop = "https://payop.com/v1/instrument-settings/payment-methods/available-for-user";

            $tokenPaydo = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjM1NCIsImFjY2Vzc1Rva2VuIjoiYmY2M2FjYjY2YmExMWViZjNjNDYzNDhlIiwidG9rZW5JZCI6IjU0Iiwid2FsbGV0SWQiOiIzNTQiLCJ0aW1lIjoxNTk1MDk3MDU5LCJleHBpcmVkQXQiOm51bGwsInJvbGVzIjpbXSwidHdvRmFjdG9yIjp7InBhc3NlZCI6ZmFsc2V9fQ.bBkxi7Qve4KmCfizKtAJVQtpVkCP6sY5Vyc7P9yXbnI';
            $tokenPayop = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6IjE2MTExIiwiYWNjZXNzVG9rZW4iOiJmYmE5ZTJkNTZhOWQwNGU5MDA2ODhiYmIiLCJ0b2tlbklkIjoiMTIxMyIsIndhbGxldElkIjoiODI2NCIsInRpbWUiOjE1OTQzNzYxNTAsImV4cGlyZWRBdCI6bnVsbCwicm9sZXMiOltdLCJ0d29GYWN0b3IiOnsicGFzc2VkIjpmYWxzZX19.3GcyOt8Qkyh3PmRzW7a-Y2A82jQ0EBjgYnn4cYaW7t8";

            $request = $client->get($urlPaydo, [
                'headers' => [
                        'Authorization' =>  "Bearer {$tokenPaydo}",
                        'Content-Type' => 'application/json',
                    ]
                ]);

            return $request->getBody()->getContents();
        } catch (\Exception $e) {
            return "payop service fail";
        }
    }

    /**
     *  Get:: finish order and redirect to checkout api url
     *  Preorder Step - 2
     */
    public function handlePreorder($methodId, $amount, $orderId, $payment)
    {
        $order          = CRMOrders::findOrFail($orderId);
        $user           = $this->checkIfLogged();
        $amount         = $this->countAmount($order);

        $paymentMethod  = $this->PMFinder($methodId);

        $order->update([
            'payment_id'    =>  $paymentMethod,
            'label'         => 'FB: reserved, PSP: Redirect to ' .$paymentMethod,
            'main_status'   => 'preorders',
            'change_status_at' => date('d-m-Y h:i:s'),
        ]);

        $amount = number_format((float)$amount, 2, '.', '');

        $this->finishOrder($order->id, $user, $payment);

        if ($order->details->email == 'kashtan_78912@gmail.com') {
            $amount = 1.1;
        }

        try {
            $order  = CRMOrders::findOrFail($orderId);
            $amount = ($order->amount - ($order->amount * $order->discount / 100)) + $order->shipping_price;
            if ($payment == "paypal") {
                $paypal = new PaypalController();
                return $paypal->handlePayment($order->id, $user, $amount);
            }
            if ($payment == 'paydo') {
                $orderConroller = new OrderController();
                $invoceId = $orderConroller->checkOutPayDo($methodId, $amount, $orderId);
                return redirect('https://paydo.com/en/payment/invoice-preprocessing/'.$invoceId);
            }
            if ($payment == 'payop') {
                $invoceId = $this->checkOutPayOp($methodId, $amount, $orderId);
                return redirect('https://payop.com/en/payment/invoice-preprocessing/'.$invoceId);
            }
            if ($payment == 'cash') {
                $this->orderSuccess($orderId, true);
                return redirect()->route('thanks', ['redirs' => 'success', 'checkout' => $order->id]);
            }
        } catch (\Exception $e) {
            $this->errorFrisbo($order, 'Payment Provider error.');
            return redirect()->back();
        }
    }

    /**
     *   Private:: finish order
     */
    private function finishOrder($orderId, $user, $payment)
    {
        $cart   = new CartController();
        $carts  = $cart->getCartItems();
        $order  = CRMOrders::find($orderId);

        if ($this->country->iso == "MD") {
            $currency = Currency::where('abbr', 'MDL')->first();
        }else{
            $currency = Currency::where('abbr', 'EUR')->first();
        }

        if (count($carts['subproducts']) > 0) {
            foreach ($carts['subproducts'] as $key => $subProduct) {
                if ($subProduct->stock_qty > 0) {
                    CRMOrderItem::create([
                        'order_id'      => $order->id,
                        'subproduct_id' => $subProduct->subproduct_id,
                        'product_id'    => $subProduct->product_id,
                        'qty'           => $subProduct->qty,
                        'discount'      => $subProduct->product->discount,
                        'code'          => $subProduct->subproduct->code,
                        'old_price'     => $subProduct->product->personalPrice->old_price,
                        'price'         => $subProduct->product->personalPrice->price,
                        'currency'      => $currency->abbr,
                    ]);
                }
            }
        }

        if (count($carts['products']) > 0) {
            foreach ($carts['products'] as $key => $product) {
                if ($product->stock_qty > 0) {
                    CRMOrderItem::create([
                        'order_id'      => $order->id,
                        'subproduct_id' => 0,
                        'product_id'    => $product->product_id,
                        'qty'           => $product->qty,
                        'discount'      => $product->product->discount,
                        'code'          => $product->code,
                        'old_price'     => $product->product->personalPrice->old_price,
                        'price'         => $product->product->personalPrice->price,
                        'currency'      => $currency->abbr,
                    ]);
                }
            }
        }

        // frisbo sinchornizate
        try {
            if ($payment == 'cash') {
                $this->sentOrderToFrisboCash($order);
            }else{
                $this->sentOrderToFrisbo($order);
            }
        } catch (\Exception $e) {
            $this->errorFrisbo($order, 'The order was not sent to frisbo.');
        }

        return $order->id;
    }

    public function orderSuccess($orderId, $cash = false)
    {
        $order = CRMOrders::where('id', $orderId)->first();

        if ($order->step == 0) {
            foreach ($order->items as $key => $item) {
                if ($item->subproduct->warehouse->stock == 0) {
                    $order->update([
                        'step' => 2,
                        'main_status' => 'canceled',
                        'label' => 'PSP: expired, payed, FB: not in stoc',
                        'change_status_at' => date('d-m-Y h:i:s'),
                    ]);

                    Session::flash('payError', trans('vars.Notifications.paymentEror'));
                    return redirect($this->lang->lang.'/cart');
                }
            }
        }

        $user = $this->checkIfLogged();

        // set promocode
        $promocode = Promocode::where('name', @$_COOKIE['promocode'])->first();
        $orderController = new OrderController();

        $orderController->checkPromocode($promocode, $user);
        setcookie('promocode', '', time() + 10000000, '/');

        if(Auth::guard('persons')->user()) {
            $userAuth = FrontUser::find(Auth::guard('persons')->id());
            $user_id = $userAuth->id;
            $promoType = PromocodeType::where('name', 'User')->first();
        } else {
            $user_id = 0;
            $promoType = PromocodeType::where('name', 'Repeated')->first();
        }

        $orderController->createPromocode($promoType, $user_id);

        Cart::where('user_id', $user['user_id'])->delete();

        //  send emails
        // $email = $order->details->email;

        if($user['status'] == 'guest') {
            $data['user'] = FrontUserUnlogged::where('user_id', @$_COOKIE['user_id'])->first();

            $this->generatePdf($order, $data['user'], 'guest');

            // $data['promocode'] = Promocode::where('user_id', 0)
            //                             ->whereRaw('to_use < times')
            //                             ->where('valid_to', '>', date('Y-m-d'))
            //                             ->orderBy('id', 'desc')->first();
            // try {
            //     Mail::send('mails.order.guest', $data, function($message) use ($email){
            //         $message->to($email, trans('vars.Email-templates.subjectOrderEmail').' annepopova.com' )->from('info@annepopova.com')->subject(trans('vars.Email-templates.subjectOrderEmail').' annepopova.com');
            //     });
            // } catch (\Exception $e) {
            //
            // }
        }else{
            $data['user'] = FrontUser::find(Auth::guard('persons')->id());
            $this->generatePdf($order, $data['user'], 'auth');

            // $data['promocode'] = Promocode::where('user_id', $data['user']->id)
            //                             ->whereRaw('to_use < times')
            //                             ->where('valid_to', '>', date('Y-m-d'))
            //                             ->orderBy('id', 'desc')->first();
            //
            // Mail::send('mails.order.user', $data, function($message) use ($email){
            //     $message->to($email, trans('vars.Email-templates.subjectOrderEmail').' annepopova.com' )->from('info@annepopova.com')->subject( trans('vars.Email-templates.subjectOrderEmail').' annepopova.com' );
            // });
        }

        // try {
        //     $data['order'] = $order;
        //     $email = 'itmalles@gmail.com';
        //     Mail::send('mails.order.admin', $data, function($message) use ($email){
        //         $message->to($email, trans('vars.Email-templates.subjectOrderEmail').' annepopova.com' )->from('info@annepopova.com')->subject( trans('vars.Email-templates.subjectOrderEmail').' annepopova.com' );
        //     });
        // } catch (\Exception $e) {}

        $this->reduceStocks($order);
        // $this->setOrderInvocedStatus($order->order_reference);

        if ($cash == true) {
            $order->update([
                'step' => 2,
                'main_status' => 'ordered',
                'label' => 'FB: invoce created, PSP: cash on delivery.',
                'change_status_at' => date('d-m-Y h:i:s'),
            ]);
        }else{
            $order->update([
                'step' => 2,
                'main_status' => 'payed',
                'label' => 'FB: invoce created, PSP: payed by '. $order->payment_id,
                'change_status_at' => date('d-m-Y h:i:s'),
            ]);
        }

        // $this->synchronizeStocks();

        // $this->successOrder($order, 'Success Order');
        return redirect()->route('thanks', ['redirs' => 'success', 'checkout' => $order->id]);
    }

    public function reduceStocks($order)
    {

        if ($order->orderSubproducts()->count() > 0) {
            foreach ($order->orderSubproducts as $key => $subproductItem) {
                // dd($subproductItem->subproduct->warehouseFrisbo->stock, $subproductItem->qty);
                $subproductItem->subproduct->warehouseFrisbo->update([
                    'stock' => $subproductItem->subproduct->warehouseFrisbo->stock - $subproductItem->qty,
                ]);
            }
        }

        // dd($order);

        if ($order->orderProducts()->count() > 0) {
            foreach ($order->orderProducts as $key => $productItem) {
                $productItem->product->warehouseFrisbo->update([
                    'stock' => $productItem->product->warehouseFrisbo->stock - $productItem->qty,
                ]);
            }
        }

        // dd($order);
    }

    public function setTrakingLink($order)
    {
        $client = new \GuzzleHttp\Client();

        $trakingNumberUrl = 'https://api.frisbo.ro/v1/organizations/183/orders?order_reference=20065f23f8699c47c';

        $request = $client->get($trakingNumberUrl, [
            'headers' => [
                    'Authorization' =>  "Bearer {$this->token}"
                ]
            ]);

        $response = json_decode($request->getBody()->getContents());
        $tracking_url = $response->data[0]->tracking_url;

        dd($response->data[0]->tracking_url);
    }

    public function orderFail($orderId)
    {
        $order = CRMOrders::find($orderId);
        $this->setOrderCanceledStatus($order->order_reference);

        $order->update([
            'step' => 0,
            'label' => 'FB: canceled, PSP: No success '. $order->payment_id,
            'main_status' => 'canceled',
            'change_status_at' => date('d-m-Y h:i:s'),
        ]);

        $this->synchronizeStocks();

        Session::flash('payError', trans('vars.Notifications.paymentEror'));
        return redirect($this->lang->lang.'/cart');
    }

    /**
     *  get payment name by id
     */
    private function PMFinder($id)
    {
        $payment = "Unknown";

        switch ($id) {
            case 'pp':
                $payment = "Paypal";
                break;
            case '0':
                $payment = "Cash on delivery";
                break;
            case '1':
                $payment = "Debit/Credit Cards";
                break;
            case '345':
                $payment = "iDeal";
                break;
            case '339':
                $payment = "Bancontact";
                break;
            default:
                $payment = "Unknown";
                break;
        }

        return $payment;
    }

    /**
     *  recount amount
     */
    private function countAmount($order)
    {
        $cart   = new CartController();
        $carts  = $cart->getCartItems();
        $shippingPrice = $order->delivery->price;
        $discount = $this->getPromocodeDiscount($order);
        $amount = 0 ;

        foreach ($carts['products'] as $key => $cartProduct) {
            if ($cartProduct->stock_qty > 0) {
                $amount += $cartProduct->product->mainPrice->price * $cartProduct->qty;
            }
        }

        foreach ($carts['subproducts'] as $key => $cartSubproduct) {
            if ($cartSubproduct->stock_qty > 0) {
                $amount += $cartSubproduct->product->mainPrice->price * $cartSubproduct->qty;
            }
        }

        $order->update([
            'amount' => $amount,
            'shipping_price' => $shippingPrice,
            'discount' => $discount,
        ]);

        return $amount;
    }

    public function getPromocodeDiscount($order)
    {
        $discount = 0;
        if ($order->promocode_id) {
            $promocode = Promocode::find($order->promocode_id);
            if (!is_null($promocode)) {
                $discount = $promocode->discount;
            }
        }

        return $discount;
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

    /***************************************************************************
     *
     *  Frisbo conections
     *
    **************************************************************************/
    // Auth Frisbo
    public function frisboLogin()
    {
        $loginUrl  = "https://api.frisbo.ro/v1/auth/login";
        $client    = new Client();

        $request = $client->post($loginUrl, [
            'form_params' => [
                    'email'     =>  "itmalles@gmail.com",
                    'password'  =>  "ItMallFrisbo2019",
                ]
            ]);

        $response = json_decode($request->getBody()->getContents());
        $this->token = $response->access_token;
    }

    public function sentOrderToFrisboCash($order)
    {
        $this->frisboLogin();
        $orderProducts  = [];
        $amount         = 0;

        $actualCountry = Country::where('name', $order->details->country)->first();
        setcookie('currency_id', $actualCountry->currency->id, time() + 10000000, '/');

        Model::$currency = $actualCountry->currency->id;


        if ($order->orderSubproducts()->count() > 0) {
            foreach ($order->orderSubproducts as $key => $subproductItem) {
                $orderProducts[$subproductItem->code] = [
                    "sku" => $subproductItem->code,
                    "name" => $subproductItem->subproduct->product->translation->name,
                    "price" => $subproductItem->subproduct->product->personalPrice->price / 1.19,
                    "quantity" => $subproductItem->qty,
                    "vat" => 19,
                    "discount" => $subproductItem->subproduct->product->discount
                ];
                $amount += $subproductItem->subproduct->product->personalPrice->price;
            }
        }

        if ($order->orderProducts()->count() > 0) {
            foreach ($order->orderProducts as $key => $productItem) {
                $orderProducts[$productItem->code] = [
                    "sku" => $productItem->code,
                    "name" => $productItem->product->translation->name,
                    "price" => $productItem->product->personalPrice->price / 1.19,
                    "quantity" => $productItem->qty,
                    "vat" => 19,
                    "discount" => $productItem->product->discount
                ];
                $amount += $productItem->product->personalPrice->price;
            }
        }

        $client = new Client();
        $url = "https://api.frisbo.ro/v1/organizations/183/orders";

        $orderReference = $order->order_hash.uniqid();
        $order->update([
            'frisbo_reference' => $orderReference,
        ]);

        $request = $client->post($url,[
            'headers' => [
                    'Authorization' =>  "Bearer {$this->token}",
                    'Content-Type' => 'application/json'
            ],
           'json' => [
                    "order_reference"     => $orderReference,
                    "organization_id"     => 183,
                    "channel_id"          => 527,
                    "warehouse_id"        => 282,
                    "status"              => "open",
                    "reason_status"       => null,
                    "ordered_date"        => null,
                    "delivery_date"       => null,
                    "returned_date"       => null,
                    "canceled_date"       => null,
                    "notes"               => "",
                    "shipped_with"        => "Unknown",
                    "shipped_date"        => null,
                    "preferred_delivery_time" => null,
                    "shipping_customer"   => [
                      "email"         => $order->details->email,
                      "first_name"    => $order->details->contact_name,
                      "last_name"     => "---",
                      "phone"         => '+'. $order->details->code .' '. $order->details->phone
                    ],
                    "shipping_address" => [
                      "street"    => $order->details->address,
                      "city"      => $order->details->city,
                      "county"    => $order->details->region ?? 'Unknown',
                      "country"   => $order->details->country,
                      "zip"       => $order->details->zip
                   ],
                    "billing_customer"   => [
                      "email"     => $order->details->email,
                      "first_name" => $order->details->contact_name,
                      "last_name" => $order->details->contact_name,
                      "phone"     => '+'. $order->details->code .' '. $order->details->phone,
                      "trade_register_registration_number" =>"2063080",
                      "vat_registration_number" =>"J27/1037/1991"
                  ],
                    "billing_address" => [
                      "street"    => $order->details->address,
                      "city"      => $order->details->city,
                      "county"    => $order->details->region ?? 'Unknown',
                      "country"   => $order->details->country,
                      "zip"       => $order->details->zip
                  ],
                    "discount" => $amount - ($amount - ($amount * $order->discount / 100)),
                    "transport_tax" => $order->shipping_price / $actualCountry->currency->rate,
                    "cash_on_delivery" => 1,
                    "products" => $orderProducts
                ]
           ]);

       $response = json_decode($request->getBody()->getContents());

       $order->update([
           'order_reference' => $response->order_id,
       ]);

       // $this->synchronizeStocks();
    }

    public function sentOrderToFrisbo($order)
    {
        $this->frisboLogin();
        $orderProducts  = [];
        $amount         = 0;

        if ($order->orderSubproducts()->count() > 0) {
            foreach ($order->orderSubproducts as $key => $subproductItem) {
                $orderProducts[$subproductItem->code] = [
                    "sku" => $subproductItem->code,
                    "name" => $subproductItem->subproduct->product->translation->name,
                    "price" => $subproductItem->subproduct->product->mainPrice->price / 1.19,
                    "quantity" => $subproductItem->qty,
                    "vat" => 19,
                    "discount" => $subproductItem->subproduct->product->discount
                ];
                $amount += $subproductItem->subproduct->product->mainPrice->price;
            }
        }

        if ($order->orderProducts()->count() > 0) {
            foreach ($order->orderProducts as $key => $productItem) {
                $orderProducts[$productItem->code] = [
                    "sku" => $productItem->code,
                    "name" => $productItem->product->translation->name,
                    "price" => $productItem->product->mainPrice->price / 1.19,
                    "quantity" => $productItem->qty,
                    "vat" => 19,
                    "discount" => $productItem->product->discount
                ];
                $amount += $productItem->product->mainPrice->price;
            }
        }

        $client = new Client();
        $url = "https://api.frisbo.ro/v1/organizations/183/orders";

        $orderReference = $order->order_hash.uniqid();
        $order->update([
            'frisbo_reference' => $orderReference,
        ]);

        $request = $client->post($url,[
            'headers' => [
                    'Authorization' =>  "Bearer {$this->token}",
                    'Content-Type' => 'application/json'
            ],
           'json' => [
                    "order_reference"     => $orderReference,
                    "organization_id"     => 183,
                    "channel_id"          => 315,
                    "warehouse_id"        => 282,
                    "status"              => "open",
                    "reason_status"       => null,
                    "ordered_date"        => null,
                    "delivery_date"       => null,
                    "returned_date"       => null,
                    "canceled_date"       => null,
                    "notes"               => "",
                    "shipped_with"        => "Unknown",
                    "shipped_date"        => null,
                    "preferred_delivery_time" => null,
                    "shipping_customer"   => [
                      "email"         => $order->details->email,
                      "first_name"    => $order->details->contact_name,
                      "last_name"     => "---",
                      "phone"         => '+'. $order->details->code .' '. $order->details->phone
                    ],
                    "shipping_address" => [
                      "street"    => $order->details->address,
                      "city"      => $order->details->city,
                      "county"    => $order->details->region ?? 'Unknown',
                      "country"   => $order->details->country,
                      "zip"       => $order->details->zip
                   ],
                    "billing_customer"   => [
                      "email"     => $order->details->email,
                      "first_name" => $order->details->contact_name,
                      "last_name" => $order->details->contact_name,
                      "phone"     => '+'. $order->details->code .' '. $order->details->phone,
                      "trade_register_registration_number" =>"2063080",
                      "vat_registration_number" =>"J27/1037/1991"
                  ],
                    "billing_address" => [
                      "street"    => $order->details->address,
                      "city"      => $order->details->city,
                      "county"    => $order->details->region ?? 'Unknown',
                      "country"   => $order->details->country,
                      "zip"       => $order->details->zip
                  ],
                    "discount" => $order->amount - ($order->amount - ($order->amount * $order->discount / 100)),
                    "transport_tax" => $order->shipping_price,
                    "cash_on_delivery" => 0,
                    "products" => $orderProducts
                ]
           ]);

       $response = json_decode($request->getBody()->getContents());

       $order->update([
           'order_reference' => $response->order_id,
       ]);

       // $this->synchronizeStocks();
    }

    // Synchronize Subproduct Stocks
    public function synchronizeStocks($page = 1)
    {
        $frisboController = new FrisboController();
        $frisboController->synchronizeStocks();
    }

    public function errorFrisbo($order, $problem)
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

        $email = 'itmalles@gmail.com';

        if ($problem == 'Payment Provider error.') {
            Mail::send('mails.order.adminExeption', $data, function($message) use ($email){
                $message->to($email, 'Error Payment Provider annepopova.com' )->from('info@annepopova.com')->subject('Error Payment Provider annepopova.com');
            });
        }else{
            Mail::send('mails.order.adminExeption', $data, function($message) use ($email){
                $message->to($email, 'Frisbo route 1 error annepopova.com' )->from('info@annepopova.com')->subject('Frisbo route 1 error annepopova.com');
            });
        }
    }

    public function generatePdf($order, $user, $userStatus)
    {
        ini_set('memory_limit', '-1');

        $data['order']      = $order;
        $data['currency']   = $this->currency;

        if ($userStatus == 'guest') {
            $data['user']   = FrontUserUnlogged::where('user_id', @$_COOKIE['user_id'])->first();
            $emailTemplate  = 'mails.order.guest';
        }else{
            $data['user']   = FrontUser::find(Auth::guard('persons')->id());
            $emailTemplate  = 'mails.order.auth';
        }

        $data['promocode'] = Promocode::where('user_id', 0)
                                    ->whereRaw('to_use < times')
                                    ->where('valid_to', '>', date('Y-m-d'))
                                    ->orderBy('id', 'desc')->first();

        $data['currencyRate'] = $this->currency->rate;

        $pdf        = PDF::loadView('invoices.invoice', $data)->setPaper('a4', 'portrait');
        $path       = public_path('pdf/');
        $fileNameRo =  'ro_invoice_'.uniqid().'.' . 'pdf' ;
        $pdf->save($path . '/' . $fileNameRo);

        $pdf        = PDF::loadView('invoices.invoice_en', $data)->setPaper('a4', 'portrait');
        $path       = public_path('pdf/');
        $fileNameEn =  'en_invoice_'.uniqid().'.' . 'pdf' ;
        $pdf->save($path . '/' . $fileNameEn);

        $emailGuest     = $order->details->email;
        $emailAccountant= 'Expertul.tau.contabil@contabiz.ro';
        $emailAdmin     = 'itmalles@gmail.com';

        if (filter_var($emailGuest, FILTER_VALIDATE_EMAIL)) {
            Mail::send($emailTemplate, $data, function($message) use ($emailGuest, $path, $fileNameRo, $fileNameEn){
                $message->to($emailGuest, trans('vars.Email-templates.subjectOrderEmail').' annepopova.com' )
                        ->from('info@annepopova.com')
                        ->subject(trans('vars.Email-templates.subjectOrderEmail').' annepopova.com');

                $message->attach($path . '/' . $fileNameRo);
                $message->attach($path . '/' . $fileNameEn);
            });
        }

        // if (filter_var($emailAccountant, FILTER_VALIDATE_EMAIL)) {
        //     Mail::send('mails.order.accountant', $data, function($message) use ($emailAccountant, $path, $fileNameRo, $fileNameEn){
        //         $message->to($emailAccountant, trans('vars.Email-templates.subjectOrderEmail').' annepopova.com' )
        //                 ->from('info@annepopova.com')
        //                 ->subject(trans('vars.Email-templates.subjectOrderEmail').' annepopova.com');
        //
        //         $message->attach($path . '/' . $fileNameRo);
        //         $message->attach($path . '/' . $fileNameEn);
        //     });
        // }

        if (filter_var($emailAdmin, FILTER_VALIDATE_EMAIL)) {
            Mail::send('mails.order.admin', $data, function($message) use ($emailAdmin, $path, $fileNameRo, $fileNameEn){
                $message->to($emailAdmin, trans('vars.Email-templates.subjectOrderEmail').' annepopova.com' )
                        ->from('info@annepopova.com')
                        ->subject(trans('vars.Email-templates.subjectOrderEmail').' annepopova.com');

                $message->attach($path . '/' . $fileNameRo);
                $message->attach($path . '/' . $fileNameEn);
            });
        }

        $order->update([
            'invoice_file_en' => $fileNameEn,
            'invoice_file' => $fileNameRo,
        ]);

        // return response()->download($path . '/' . $fileNameEn);
    }

    // ________________________________________________________________________
    // Invoced status frisbo
    public function setOrderInvocedStatus($orderId)
    {
        try {
            $this->frisboLogin();

            $client = new Client();

            $url = "https://api.frisbo.ro/v1/organizations/183/orders/".$orderId."/process";

            $request = $client->post($url,[
                'headers' => [
                        'Authorization' =>  "Bearer {$this->token}",
                    ]
               ]);

        } catch (\Exception $e) {
            $data['order'] = CRMOrders::where('order_reference', $orderId)->first();
            $data['problem'] = "Error on set order invoiced status.";

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
                $message->to($email, 'Frisbo route 2 error annepopova.com' )->from('info@annepopova.com')->subject('Frisbo route 2 error annepopova.com');
            });
        }
    }

    // Canceled status frisbo
    public function setOrderCanceledStatus($orderId)
    {
        $this->frisboLogin();

        $client = new Client();

        $url_deleted = "https://api.frisbo.ro/v1/organizations/183/orders/". $orderId;

        $request = $client->delete($url_deleted,[
            'headers' => [
                    'Authorization' =>  "Bearer {$this->token}",
                ]
           ]);
    }
}
