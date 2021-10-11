<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\Set;
use App\Models\SetPrice;
use App\Models\SubProduct;
use App\Models\SubproductPrice;
use App\Models\ProductDillerPrice;


class CurrenciesController extends Controller
{
    public function index()
    {
        $currencies = Currency::orderBy('type', 'desc')->get();

        $mainCurrency = Currency::where('type', 1)->first();

        return view('admin::admin.currencies.index', compact('currencies', 'mainCurrency'));
    }

    public function edit($id)
    {
        $currency = Currency::findOrFail($id);

        $mainCurrency = Currency::where('type', 1)->first();

        return view('admin::admin.currencies.edit', compact('currency', 'mainCurrency'));
    }

    public function update(Request $request, $id)
    {
        $currency = Currency::findOrFail($id);

        $active = $request->get('active') ? 1 : 0;
        $exchange_dependable = $request->get('exchange_dependable') ? 1 : 0;
        $toValidate['title_'.$this->lang->lang] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);

        $currency->type = $request->get('type');
        $currency->correction_factor = $request->get('correction_factor');
        $currency->abbr = $request->get('abbr');
        $currency->rate = $request->get('type') == 1 ? 1 : $request->get('rate');
        $currency->active = $active;
        $currency->exchange_dependable = $exchange_dependable;
        $currency->save();

        if ($request->get('type') == 1) {
            $this->changeMainCurrency($currency);
        }

        $this->changeActiveCountries($currency);
        $this->generatePrices($currency);

        $currency->translations()->delete();

        foreach ($this->langs as $lang):
            $currency->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('title_' . $lang->lang),
            ]);
        endforeach;

        return redirect()->back();
    }

    public function store(Request $request)
    {
        $toValidate['name_'.$this->lang->lang] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);
        $active = $request->get('active') ? 1 : 0;
        $exchange_dependable = $request->get('exchange_dependable') ? 1 : 0;

        $currency = new Currency();
        $currency->type = $request->get('type');
        $currency->abbr = $request->get('abbr');
        $currency->rate = $request->get('type') == 1 ? 1 : $request->get('rate');
        $currency->active = $active;
        $currency->exchange_dependable = $exchange_dependable;
        $currency->save();

        if ($request->get('type') == 1) {
            $this->changeMainCurrency($currency);
        }
        $this->generatePrices($currency);

        foreach ($this->langs as $lang):
            $currency->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('name_' . $lang->lang),
            ]);
        endforeach;

        Session::flash('message', 'New item has been created!');

        return redirect()->route('currencies.index');
    }

    private function changeMainCurrency($currency)
    {
        $currencies = Currency::where('id', '!=', $currency->id)->get();

        if (count($currencies) > 0) {
            foreach ($currencies as $key => $currencyItem) {
                Currency::where('id', $currencyItem->id)->update([
                    'type' => 0,
                ]);
            }
        }

        Currency::where('id', $currency->id)->update(['type' => 1]);

        $mainCurrency = Currency::where('type', 1)->first();
        $countries = Country::where('active', 0)->get();

        foreach ($countries as $key => $country) {
            Country::where('id', $country->id)->update([
                'currency_id' => $mainCurrency->id,
            ]);
        }
    }

    public function changeActiveCountries($currency)
    {
        $countries = $currency->countries()->get();

        if ($countries->count() > 0) {
            foreach ($countries as $key => $country) {
                if ($currency->active == 1) {
                    Country::where('id', $country->id)->update([
                        'active' => 1,
                    ]);
                }else{
                    Country::where('id', $country->id)->update([
                        'active' => 0,
                    ]);
                }
            }
        }
    }

    public function deactivateCurrency($id)
    {
        $currency = Currency::findOrFail($id);
        $countries = $currency->countries()->get();
        $mainCurrency = Currency::where('type', 1)->first();

        if ($currency->active == 1) {

            if (!is_null($mainCurrency)) {
                if ($countries->count() > 0) {
                    foreach ($countries as $key => $country) {
                        Country::where('id', $country->id)->update([
                            'currency_id' => $mainCurrency->id,
                        ]);
                    }
                }

                $currency->update(['active' => 0]);

                session()->flash('message', 'Item has been deactivated!');
            }else{
                session()->flash('message', 'Error, the main currency missing.');
            }

        }else{
            $currency->update(['active' => 1]);
        }

        return redirect()->route('currencies.index');
    }

    public function destroy($id)
    {
        $currency = Currency::findOrFail($id);
        $countries = $currency->countries()->get();
        $mainCurrency = Currency::where('type', 1)->first();


        if (!is_null($mainCurrency)) {
            if ($countries->count() > 0) {
                foreach ($countries as $key => $country) {
                    Country::where('id', $country->id)->update([
                        'currency_id' => $mainCurrency->id,
                    ]);
                }
            }

            ProductDillerPrice::where('currency_id', $id)->delete();
            ProductPrice::where('currency_id', $id)->delete();
            $currency->delete();

            session()->flash('message', 'Item has been deleted!');
        }else{
            session()->flash('message', 'Error, the main currency missing.');
        }

        return redirect()->route('currencies.index');
    }


    public function regeneratePrices()
    {
        $currencies = Currency::orderBy('type', 'desc')->get();
        $mainCurrency = Currency::where('type', 1)->first();
        $products = Product::get();
        $sets = Set::get();
        $subproducts = SubProduct::get();

        if ($currencies->count() > 0) {
            foreach ($currencies as $key => $currency) {

                // generate products prices
                if ($products->count() > 0) {
                    foreach ($products as $key => $product) {
                        $checkProductPrice = $product->prices()->where('currency_id', $currency->id)->first();

                        if (!$checkProductPrice) {
                            $product->prices()->create([
                                'currency_id' => $currency->id,
                                'dependable' => $mainCurrency->id == $currency->id ? 1 : 0,
                            ]);
                        }

                        $this->countByRateProductsPrice($product, $mainCurrency, $currency);
                    }
                }

                // generate sets prices
                if ($sets->count() > 0) {
                    foreach ($sets as $key => $set) {
                        $checkSetPrice = $set->prices()->where('currency_id', $currency->id)->first();
                        if (!$checkSetPrice) {
                            $set->prices()->create([
                                'currency_id' => $currency->id,
                                'dependable' => $mainCurrency->id == $currency->id ? 1 : 0,
                            ]);
                        }

                        $this->countByRateSetsPrice($set, $mainCurrency, $currency);
                    }
                }

                // generate sets prices
                if ($subproducts->count() > 0) {
                    foreach ($subproducts as $key => $subproduct) {
                        $checkSubproductPrice = $subproduct->prices()->where('currency_id', $currency->id)->first();
                        if (!$checkSubproductPrice) {
                            $subproduct->prices()->create([
                                'currency_id' => $currency->id,
                                'dependable' => $mainCurrency->id == $currency->id ? 1 : 0,
                            ]);
                        }

                        $this->countByRateSubproductsPrice($subproduct, $mainCurrency, $currency);
                    }
                }
            }
        }

        return redirect()->back();
    }

    private function generatePrices($currency)
    {
        $mainCurrency = Currency::where('type', 1)->first();
        $products = Product::get();

        if ($products->count() > 0) {
            foreach ($products as $key => $product) {
                $checkProductPrice = $product->prices()->where('currency_id', $currency->id)->first();
                if (!$checkProductPrice) {
                    $product->prices()->create([
                        'currency_id' => $currency->id,
                        'dependable' => $mainCurrency->id == $currency->id ? 1 : 0,
                    ]);
                }

                $this->countByRateProductsPrice($product, $mainCurrency, $currency);
            }
        }

        $dillerGroupController = new \Admin\Http\Controllers\DillerGroupsController();

        $dillerGroupController->generateDillersPrices();
    }

    public function countByRateProductsPrice($product, $mainCurrency, $currency)
    {
        if ($currency->type != 1) {
            $mainProductPrice = ProductPrice::where('product_id', $product->id)->where('currency_id', $mainCurrency->id)->first();

            if (!is_null($mainProductPrice)) {
                $exchange = $mainProductPrice->old_price * $currency->rate * $currency->correction_factor;
                $exchangeB2B = $mainProductPrice->b2b_old_price * $currency->rate * $currency->correction_factor;
                // if ($product->dependable_price == 1) {
                if ($currency->exchange_dependable == 1) {
                    ProductPrice::where('product_id', $product->id)
                                ->where('currency_id', $currency->id)
                                ->update([
                                    'old_price' => $exchange,
                                    'price' => (int)$exchange - ((int)$exchange * (int)$product->discount / 100),
                                    'b2b_price' => $exchangeB2B,
                                    'b2b_old_price' => (int)$exchangeB2B - ((int)$exchangeB2B * (int)$product->discount / 100),
                                    'dependable' => $mainCurrency->id == $currency->id ? 1 : 0,
                                ]);
                }
            }
        }
    }

    private function countByRateSetsPrice($set, $mainCurrency, $currency)
    {
        if ($currency->type != 1) {

            $mainSetPrice = SetPrice::where('set_id', $set->id)->where('currency_id', $mainCurrency->id)->first();

            if (!is_null($mainSetPrice)) {

                $exchange = $mainSetPrice->old_price * $currency->rate;
                // if ($set->dependable_price == 1) {
                if ($currency->exchange_dependable == 1) {

                    SetPrice::where('set_id', $set->id)
                                ->where('currency_id', $currency->id)
                                ->update([
                                    'old_price' => $exchange,
                                    'price' => $exchange - ($exchange * $set->discount / 100),
                                    'dependable' => $mainCurrency->id == $currency->id ? 1 : 0,
                                ]);
                }
            }
        }
    }

    private function countByRateSubproductsPrice($subproduct, $mainCurrency, $currency)
    {
        if ($currency->type != 1) {

            $mainSubproductPrice = SubproductPrice::where('subproduct_id', $subproduct->id)->where('currency_id', $mainCurrency->id)->first();

            if (!is_null($mainSubproductPrice)) {

                $exchange = $mainSubproductPrice->old_price * $currency->rate;
                // if ($subproduct->dependable_price == 1) {
                if ($currency->exchange_dependable == 1) {

                    SubproductPrice::where('subproduct_id', $subproduct->id)
                                ->where('currency_id', $currency->id)
                                ->update([
                                    'old_price' => $exchange,
                                    'price' => $exchange - ($exchange * $subproduct->discount / 100),
                                    'dependable' => $mainCurrency->id == $currency->id ? 1 : 0,
                                ]);
                }
            }
        }
    }
}
