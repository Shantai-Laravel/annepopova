<?php

namespace App\Providers;

use App\Base as Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\FrontUserUnlogged;
use App\Models\Lang;
use App\Models\Module;
use App\Models\Cart;
use App\Models\Page;
use App\Models\WishList;
use App\Models\WishListSet;
use App\Models\ProductCategory;
use App\Models\Collection;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Set;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Entry;
use Illuminate\Http\Request;


class AppServiceProvider extends ServiceProvider
{
    public function isMobile() {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", @$_SERVER["HTTP_USER_AGENT"]);
    }

    public function boot(Request $request)
    {
        $this->app['request']->server->set('HTTPS', true);

        \URL::forceScheme('https');
        $this->checkDevice($request);

        $notShippingCounrty = false;
        $ip = @$_SERVER['HTTP_X_FORWARDED_FOR'];

        $userCountry = Country::where('active', 1)->where('main', 1)->first();
        $initWareHouse = @$_COOKIE['warehouse_id'];

        if (!@$_COOKIE['country_id'] && $request->method() == 'GET'){

            try {
                // $ip = $_SERVER['REMOTE_ADDR'];
                $details = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip={$ip}"));

                $userCountry = Country::where('iso', $details->geoplugin_countryCode)->where('active', 1)->first();

                if (is_null($userCountry)) {
                    $userCountry = Country::where('active', 1)->where('main', 1)->first();
                    $notShippingCounrty = true;
                }else{
                    $notShippingCounrty = false;
                }

                setcookie('country_id', $userCountry->id, time() + 10000000, '/');
                setcookie('lang_id', $userCountry->lang->lang, time() + 10000000, '/');
                setcookie('currency_id', $userCountry->currency_id, time() + 10000000, '/');
            } catch (\Exception $e) {
                setcookie('country_id', $userCountry->id ?? 1, time() + 10000000, '/');
                setcookie('lang_id', $userCountry->lang->lang ?? 1, time() + 10000000, '/');
                setcookie('currency_id', $userCountry->currency_id, time() + 10000000, '/');
            }


            if (!@$_COOKIE['warehouse_id']) {
                setcookie('warehouse_id', $userCountry->warehouse_id ?? 1, time() + 10000000, '/');
                $initWareHouse = $userCountry->warehouse_id;
            }
        }

        if (@$_COOKIE['lang_id']) {
            $lang = Lang::where('lang', @$_COOKIE['lang_id'])->first();
            if (is_null($lang)) {
                $lang = Lang::where('default', '1')->first();
            }
        }else{
            $lang = Lang::where('lang', $userCountry->lang->lang ?? 'ro')->first();
        }

        if (\Request::segment(2) == 'homewear') {
            $siteType = 'homewear';
        }elseif(\Request::segment(2) == 'bijoux'){
            $siteType = 'bijoux';
        }else{
            $siteType = 'homewear';
        }

        if (!is_null($lang)) {
            if (is_null($userCountry)) {
                $userCountry = Country::first();
            }

            // if ($request->method() == 'GET') {
            //     Entry::create([
            //         'ip' => @$_SERVER['HTTP_X_FORWARDED_FOR'],
            //         'country' => $userCountry->iso,
            //         'date' => date('Y-m-d h:i:s'),
            //         'url' => url()->current(),
            //     ]);
            // }

            $currentLang = Lang::where('lang', \Request::segment(1))->first()->lang ?? $lang->lang;
            session(['applocale' => $currentLang]);
            \App::setLocale($currentLang);

            $lang = Lang::where('lang', session('applocale'))->first();

            $country = Country::where('id', @$_COOKIE['country_id'] ?? $userCountry->id)->first();

            $mainCurrency = Currency::where('type', 1)->first();
            $mainWarehouse = Warehouse::where('default', 1)->first();
            $currency = Currency::where('id', @$_COOKIE['currency_id'])->first() ?? $mainCurrency;
            $warehouse = Warehouse::where('id', $initWareHouse)->first() ?? $mainWarehouse;

            // Currency and Prices form Moldova
            if ($country->iso == 'MD') {
                $mainCurrency = Currency::where('id', $country->currency_id)->first();
                $currency = $mainCurrency;
            }

            $unloggedUser = FrontUserUnlogged::where('user_id', @$_COOKIE['user_id'])->first();

            $seo['title'] = env('SEO_TITLE');
            $seo['keywords'] = env('SEO_KEYWORDS');
            $seo['description'] = env('SEO_DESCRIPTION');

            $this->setUserId();

            if ($request->method() == 'GET') {
                if(\Request::segment(1) == 'back'){
                    View::share('menu', Module::where('parent_id', 0)->orderBy('position')->get());
                }else{
                    if ($siteType == 'homewear') {
                        $categoriesMenuLoungewear = ProductCategory::where('parent_id', 0)->where('active', 1)->where('homewear', 1)->orderBy('position', 'asc')->get();
                        $collectionsMenuLoungewear = Collection::where('active', 1)->orderBy('position', 'asc')->where('homewear', 1)->get();
                        View::share('categoriesMenuLoungewear', $categoriesMenuLoungewear);
                        View::share('collectionsMenuLoungewear', $collectionsMenuLoungewear);
                    }else{
                        $categoriesMenuJewelry = ProductCategory::where('parent_id', 0)->where('active', 1)->where('bijoux', 1)->orderBy('position', 'asc')->get();
                        $collectionsMenuJewelry = Collection::where('active', 1)->where('bijoux', 1)->orderBy('position', 'asc')->get();
                        View::share('categoriesMenuJewelry', $categoriesMenuJewelry);
                        View::share('collectionsMenuJewelry', $collectionsMenuJewelry);
                    }

                    $this->shareCarts();
                }

                View::share('site', $siteType);
                View::share('langs', Lang::orderBy('id', 'asc')->get());
                View::share('lang', $lang);
                View::share('countries', Country::orderBy('id', 'asc')->where('active', 1)->get());
                View::share('country', $country);
                View::share('currencies', Currency::orderBy('id', 'asc')->get());
                View::share('currency', !is_null($currency) ? $currency : $mainCurrency);
                View::share('warehouse', !is_null($warehouse) ? $warehouse : $mainWarehouse);
                View::share('mainCurrency', $mainCurrency);
                View::share('seoData', $seo);
                View::share('unloggedUser', $unloggedUser);
                View::share('notShippingCounrty', $notShippingCounrty);
                View::share('productList', json_encode([]));
                View::share('list', json_encode([]));
            }


            Model::$lang        = $lang->id;
            Model::$site        = $siteType;
            Model::$currency    = $currency->id;
            Model::$mainCurrency = $mainCurrency->id;
            Model::$warehouse   = $warehouse->id;
            Model::$warehouseName   = $warehouse->name;

        }else{
            exit('language is not exists!');
        }
    }

    public function checkDevice($request)
    {
        if ($request->method() == 'GET') {
            $device = 'desktop';
            if ($this->isMobile()) {
                $device = 'mobile';
            }
            View::share('device', $device);
        }
    }

    public function shareCarts()
    {
        View::composer('*', function ($view)
        {
            if(auth('persons')->guest() && isset($_COOKIE['user_id'])){
                $cartProducts = Cart::with(['product.translation','product.mainImage'])
                              ->where('user_id', $_COOKIE['user_id'])->where('parent_id', null)->orderBy('id', 'desc')->get();

                $wishProducts = WishList::with(['product.translation','product.mainImage', 'subproduct'])
                                      ->where('user_id', $_COOKIE['user_id'])->get();

                $wishListIds = $wishProducts->pluck('product_id')->toArray();
            }else{
                $cartProducts = Cart::with(['product.translation','product.mainImage', 'subproduct'])
                                        ->where('user_id', auth('persons')->id())->where('parent_id', null)->orderBy('id', 'desc')->get();

                $wishProducts = WishList::with(['product.translation','product.mainImage', 'subproduct'])
                                    ->where('user_id', auth('persons')->id())->get();

                $wishListIds = $wishProducts->pluck('product_id')->toArray();
            }

            View::share('cartProducts', json_encode($cartProducts));
            View::share('wishProducts', json_encode($wishProducts));
            View::share('wishListIds', $wishListIds);
        });
    }

    public function setUserId()
    {
        $user_id = md5(rand(0, 9999999).date('Ysmsd'));

        if (\Cookie::has('user_id')) {
            $value = \Cookie::get('user_id');
        }else{
            setcookie('user_id', $user_id, time() + 10000000, '/');
            $value = \Cookie::get('user_id');
        }
    }

    protected function setStocks()
    {
        $sets = Set::select('id')->get();

        foreach ($sets as $key => $set) {
            $stock = false;
            if ($set->products->count() > 0) {
                foreach ($set->products as $key => $product) {
                    if ($product->subproducts->count() > 0) {
                        foreach ($product->subproducts as $key => $subproduct) {
                            if (($stock == false || $subproduct->stoc < $stock) && $subproduct->stoc !== 0) {
                                $stock = $subproduct->stoc;
                            }
                        }
                    }else{
                        if ($stock == false || $product->stock < $stock) {
                            $stock = $product->stock;
                        }
                    }
                }
            }
            $set->update(['stock' => $stock]);
        }

        $this->setStocksOuts($sets);
    }

    protected function setStocksOuts($sets)
    {
        foreach ($sets as $key => $set) {
            $stock = false;
            if ($set->products->count() > 0) {
                foreach ($set->products as $key => $product) {
                    if ($product->subproducts->count() > 0) {
                        $subproductStock = 0;
                        foreach ($product->subproducts as $key => $subproduct) {
                            $subproductStock += $subproduct->stoc;
                        }
                        if ($subproductStock == 0) {
                            $set->update(['stock' => $stock]);
                            break;
                        }
                    }else{
                        if ($product->stock == 0) {
                            $set->update(['stock' => $product->stock]);
                            break;
                        }
                    }
                }
            }
        }
    }

    public function _bot_detected() {
        return (
            isset($_SERVER['HTTP_USER_AGENT'])
            && preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT'])
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
