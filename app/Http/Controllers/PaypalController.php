<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CartController;
use Admin\Http\Controllers\FrisboController;
use App\Http\Controllers\OrderController;
use GuzzleHttp\Client;
use App\Models\FrontUserUnlogged;
use App\Models\Currency;
use App\Models\CRMOrders;
use App\Models\CRMOrderItem;
use App\Models\Cart;
use App\Models\Promocode;
use App\Models\PromocodeType;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use Session;

class PaypalController extends Controller
{
    private $_api_context;

    public function __construct()
    {
        /** PayPal api context **/
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
            $paypal_conf['client_id'],
            $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

        /**
        *
        */
        public function handlePayment($orderId, $user, $total)
        {
            $order  = CRMOrders::find($orderId);

            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $item_1 = new Item();
            $item_1->setName($order->order_hash) /** item name **/
                        ->setCurrency('EUR')
                        ->setQuantity(1)
                        ->setPrice($total); /** unit price **/


            $item_list = new ItemList();
            $item_list->setItems(array($item_1));


            $amount = new Amount();
            $amount->setCurrency('EUR')->setTotal($total);

            $transaction = new Transaction();

            $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription('Your transaction description');

            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(URL::route('status', ['orderId' => $order->id])) /** Specify return URL **/
                            ->setCancelUrl(URL::route('status', ['orderId' => $order->id]));

            $payment = new Payment();
            $payment->setIntent('Sale')
                    ->setPayer($payer)
                    ->setRedirectUrls($redirect_urls)
                    ->setTransactions(array($transaction));


            try {
                $payment->create($this->_api_context);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                if (\Config::get('app.debug')) {
                    \Session::put('error', 'Connection timeout');
                    return redirect()->route('paywithpaypal');
                } else {
                    \Session::put('error', 'Some error occur, sorry for inconvenient');
                    return redirect()->route('paywithpaypal');
                }
            }

            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }
            /** add payment ID to session **/
            Session::put('paypal_payment_id', $payment->getId());
                if (isset($redirect_url)) {
                    /** redirect to paypal **/
                    return redirect()->away($redirect_url);
            }

            \Session::put('error', 'Unknown error occurred');
            return redirect()->route('paywithpaypal');
        }

        public function getPaymentStatus(Request $request, $orderId)
        {
            /** Get the payment ID before session clear **/
            $payment_id = Session::get('paypal_payment_id');
            $order = CRMOrders::find($orderId);
            $paymentController = new PaymentController();

            /** clear the session payment ID **/
            Session::forget('paypal_payment_id');
            if (empty($request->get('PayerID')) || empty($request->get('token'))) {
                Session::put('error', 'Payment failed');
                return redirect()->route('cart');
            }

            $payment_id = $request->get('paymentId');
            $payment = Payment::get($payment_id, $this->_api_context);
            $execution = new PaymentExecution();
            $execution->setPayerId($request->get('PayerID'));

            /**Execute the payment **/
            $result = $payment->execute($execution, $this->_api_context);
            if ($result->getState() == 'approved') {
                $paymentController->orderSuccess($orderId);
                return redirect()->route('thanks', ['redirs' => 'success', 'checkout' => $order->id]);
            }

            return $paymentController->orderFail($orderId);
            // return redirect()->route('order-payment', ['orderId' => $order->id]);
        }

}
