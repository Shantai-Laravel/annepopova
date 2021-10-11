<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Lang;
use App\Models\Country;
use App\Models\Currency;
use App\Models\FeedBack;
use App\Models\Warehouse;
use App\Base as Model;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Mollie\Laravel\Facades\Mollie;
use Session;
use View;

class Controller extends BaseController
{
    use AuthorizesRequests,  DispatchesJobs, ValidatesRequests;

    protected $langs;

    protected $lang;

    protected $siteType;

    protected $device;

    protected $warehouse;

    protected $mainCurrency;

    public function __construct()
    {
        $MD = 140; // Moldova ID

        $this->langs    = Lang::all();
        $this->lang     = Lang::where('lang', session('applocale') ?? 'ro')->first();
        $this->mainCurrency   = Currency::where('type', 1)->first();

        if (\Request::segment(2) == 'homewear') {
            $this->siteType = 'homewear';
        }elseif(\Request::segment(2) == 'bijoux'){
            $this->siteType = 'bijoux';
        }else{
            $this->siteType = '';
        }

        $this->country  = Country::where('id', @$_COOKIE['country_id'])->first();
        if (is_null($this->country)) {
            $this->country = Country::where('main', 1)->first();
        }

        $this->warehouse = strtolower(Warehouse::find(Model::$warehouse)->name);

        $this->currency = Currency::where('id', @$_COOKIE['currency_id'])->first();
        if (is_null($this->currency)) {
            $this->currency = Currency::first();
        }

        $this->device = 'desktop';
        if ($this->isMobile()) {
            $this->device = 'mobile';
        }

        if (!Auth::check()) {
            return redirect('/auth/login');
        }
    }

    public function isMobile()
    {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", @$_SERVER["HTTP_USER_AGENT"]);
    }

    public function apiFrisbo()
    {
        $client = new \GuzzleHttp\Client();
        $url = "https://api.frisbo.ro/v1/organizations/183/products";

        $token ="eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImtpZCI6IlFqRTROa00zTnprNFJVSTROalU0UVVSRFFUUkNOak13UVRBd1JrVXhRamxDT1RRNU1rWTJNdyJ9.eyJpc3MiOiJodHRwczovL2ZyaXNiby1yby5ldS5hdXRoMC5jb20vIiwic3ViIjoiYXV0aDB8Mjc2IiwiYXVkIjpbImh0dHBzOi8vYXBpLmZyaXNiby5ybyIsImh0dHBzOi8vZnJpc2JvLXJvLmV1LmF1dGgwLmNvbS91c2VyaW5mbyJdLCJpYXQiOjE1NzM2NDM3MTksImV4cCI6MTU3MzczMDExOSwiYXpwIjoiMkNoeUgyeXNRYThOdHdEV0tFem1oekpfa2p5cHlkMk0iLCJzY29wZSI6Im9wZW5pZCBwcm9maWxlIGVtYWlsIiwiZ3R5IjoicGFzc3dvcmQifQ.KPG1WsME-OFFhcbGksHNigGYPZynvC91pnSA5vg3EDscrpM4pSyHkOZfWAJSaHM8mwOrLCORK_BOPF7IJAaYAIXivy2GdcVg0UN5AMnPW872T4_P-LbjgDmpAIfK59j9Mwq-pvOifKvHnfhGSePIgW2zMG8HU3e7rcBx4xsRsbN_8s9u86Oifh5_QvQy_hqm7YTEmX3xBFkwlNokfxS9kxhN9Z5kj38W8R40r7NuoKvYTffU5Vwq3qU0FmQvAKEafgzU3O53PUG-v8jHUsXda5wF7eBCPy1YF3H80L8ZHOkJZPDDBSCY8diFdwrV2AsFzAmGsD5e3czjMYkNlo2YqQ";
        $request = $client->get($url, [
            'headers' => [
                    'Authorization' =>  "Bearer {$token}"
                ]
            ]);

       echo "<pre>";
       dd(json_decode($request->getBody()->getContents()));
    }

    public function adyen()
    {
        $token = 'AQEjhmfuXNWTK0Qc+iSZpkk5q+i0fRahjooxzd4/Dgob/UCYvTkQwV1bDb7kfNy1WIxIIkxgBw==-2jepkBaywcknnvngmUEtHxuagEHnL+8nOXGdQuv7bLw=-838md9FTt2SJCecU';

        $client = new \GuzzleHttp\Client();
        $url = "https://checkout-test.adyen.com/v51/payments";

        $request = $client->post($url, [
            'headers' => [
                    'X-API-Key' => $token,
                    'Content-Type' => 'application/json',
                ],
            'json' => [
                "amount"=> [
                "currency" => "EUR",
                "value" => 0.1
            ],
              "reference" => "100200",
              "paymentMethod" => [
                "type"=> "scheme",
                "number" => "4111111111111111",
                "expiryMonth" => "10",
                "expiryYear" => "2020",
                "holderName" => "John Smith",
                "cvc" => "737"
            ],
              "returnUrl" => "https://juliaallert.com",
              "merchantAccount" => "ITMallOUECOM"
            ],
        ]);

        dd(json_decode($request->getBody()->getContents()));
    }


    // Mollie methods
    public function mollie()
    {
        $methods = Mollie::api()->methods()->allActive(['include' => 'pricing']);

        // $mollie = Mollie::api()->methods()->get("creditcard", ["include" => "issuers,pricing"]);
        // dd($mollie);

        return view('front.mollie.index', compact('methods'));
    }

    public function molliePayment($id)
    {
        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => 'EUR',
                'value' => '30.00', // You must send the correct number of decimals, thus we enforce the use of strings
            ],
            'method' => $id,
            'description' => 'Test payment',
            'webhookUrl' => route('webhooks.mollie'),
            'redirectUrl' => route('order.success', ['method' => $id]),
        ]);

        $payment = Mollie::api()->payments()->get($payment->id);

        return redirect($payment->getCheckoutUrl(), 303);
    }

    public function testSwagger()
    {
        $client = new \GuzzleHttp\Client();
        $loginUrl = "https://provectapos.com/api/api/Account/login";

        $request = $client->get($loginUrl, [
            'form_params' => [
                    'login' =>  "manager@anapopova.com",
                    'password' =>  "1111",
                ]
            ]);

        // token.Employees[0].Organisation.Id
        $response = json_decode($request->getBody()->getContents());
        $token = $response->Employees[0]->Organisation->Code;

        // dd($token);

        // Get Products
        $url = "https://provectapos.com/api/api/". $token ."/Product/search";

        $request = $client->get($url, [
                'form_params' => [
                    'From' =>  "0",
                    'To' =>  "100",
                ]
            ]);

        $response = json_decode($request->getBody()->getContents());

        dd($response);
        // dd($response->Employees[0]->Organisation->Id);
        // dd($client);
    }

    public function webhooks()
    {
        dd('webhooks');
    }

    public function redirectUrl($method)
    {
        $method = Mollie::api()->methods()->get($method, ["include" => "issuers,pricing"]);

        return view('front.mollie.callBack', compact('method'));
    }

    public function getCountriesList(Request $request)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $ipDetails = @json_decode(file_get_contents("https://ipinfo.io/{$ip}"));

        if ($ipDetails->country) {
            $data['selected'] = Country::with(['translation', 'lang', 'currency'])->where('iso', $ipDetails->country)->first();
        }else{
            $data['selected'] = Country::with(['translation', 'lang', 'currency'])->where('active', 1)->first();
        }
        $data['countries'] = Country::with(['translation', 'lang'])->orderBy('iso', 'asc')->get();
        $data['languages'] = Lang::get();
        $data['currencies'] = Currency::get();

        return $data;
    }

    public function saveCountryUser(Request $request)
    {
        $country = Country::where('id', $request->get('countryId'))->first();
        $lang = Lang::where('id', $request->get('langId'))->first();
        $currency = Currency::where('id', $request->get('currencyId'))->first();

        if (!is_null($country)) {
            setcookie('country_id', $country->id, time() + 10000000, '/');
            setcookie('lang_id', $lang->lang, time() + 10000000, '/');
            setcookie('currency_id', $currency->id, time() + 10000000, '/');
        }

        return $lang->lang;
    }

    public function dismissCountryUser(Request $request)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $ipDetails = @json_decode(file_get_contents("https://ipinfo.io/{$ip}"));

        $country = Country::where('active', 1)->where('iso', $ipDetails->country)->first();
        $lang = Lang::where('id', $country->lang_id)->first();

        if (!is_null($country) && !is_null($lang)) {
            setcookie('country_id', $country->id, time() + 10000000, '/');
            setcookie('lang_id', $lang->lang, time() + 10000000, '/');
            setcookie('currency_id', $country->currency_id, time() + 10000000, '/');
        }

        return $lang->lang;
    }

    public function setUserSettings(Request $request)
    {
        $url = str_replace(url('/'), '', url()->previous());
        $previousLang = $this->lang->lang;

        $country = Country::where('id', $request->get('country_id'))->first();
        $lang = Lang::where('id', $request->get('lang_id'))->first();
        $currency = Currency::where('id', $request->get('currency_id'))->first();

        if (!is_null($country)) {
            setcookie('country_id', $country->id, time() + 10000000, '/');
            setcookie('warehouse_id', $country->warehouse_id, time() + 10000000, '/');
        }

        if (!is_null($lang)) {
            setcookie('lang_id', $lang->lang, time() + 10000000, '/');
            $url =  str_replace('/'.$previousLang, '/'.$lang->lang, $url);
        }

        if ($country->iso == "MD") {
            if (!is_null($currency)) {
                setcookie('currency_id', $country->currency_id, time() + 10000000, '/');
            }
        }else{
            if (!is_null($currency)) {
                if ($currency->abbr == "MDL") {
                    $currency = Currency::where('type', 1)->first();
                    setcookie('currency_id', $currency->id, time() + 10000000, '/');
                }else{
                    setcookie('currency_id', $currency->id, time() + 10000000, '/');
                }
            }
        }

        return redirect($url);
    }

    public function missingDeliveryCountry(Request $request)
    {
        if ($request->get('name') && $request->get('email')) {
            $feedback = new FeedBack();
            $feedback->first_name = $request->get('name');
            $feedback->email = $request->get('email');
            $feedback->phone = $request->get('phone');
            $feedback->subject = 'Individual delivery.';
            $feedback->status = 'new';
            $feedback->save();

            $data['feedback'] = $feedback;

            $email = 'iovitatudor@gmail.com';

            $status = Mail::send('mails.individualDelivery', $data, function($message) use ($email){
                $message->to($email, 'Individual delivery.')->from('support@haskel.store')->subject('Individual delivery.');
            });

            Session::flash('message', 'Va multumim, in scrut timp managerii nostri va vor contacta.');
        }
        return redirect()->back();
    }

}
