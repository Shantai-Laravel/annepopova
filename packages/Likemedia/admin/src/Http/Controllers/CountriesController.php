<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\Lang;
use App\Models\CountryDelivery;
use App\Models\Warehouse;


class CountriesController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('active', 'desc')->orderBy('main', 'desc')->get();

        return view('admin::admin.countries.index', compact('countries'));
    }

    public function create()
    {
        $currencies = Currency::get();
        $deliveries = Delivery::get();
        $payments = Payment::get();

        return view('admin::admin.countries.create', compact('currencies', 'deliveries', 'payments'));
    }

    public function edit($id)
    {
        $country = Country::findOrFail($id);
        $currencies = Currency::get();
        $deliveries = Delivery::get();
        $payments = Payment::get();
        $warehouses = Warehouse::get();

        return view('admin::admin.countries.edit', compact('country', 'currencies', 'deliveries', 'payments', 'warehouses'));
    }

    public function update(Request $request, $id)
    {
        $country = Country::findOrFail($id);
        $flag = $request->get('flag_old');

        $toValidate['title_'.$this->lang->lang] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);

        $active = $request->get('active') ? 1 : 0;
        $main = $request->get('main') ? 1 : 0;

        if ($request->file('flag')) {
            @unlink(public_path('images/flags/og/'.$country->flag));
            @unlink(public_path('images/flags/16x16/'.$country->flag));
            @unlink(public_path('images/flags/24x24/'.$country->flag));
            @unlink(public_path('images/flags/32x32/'.$country->flag));
            @unlink(public_path('images/flags/48x48/'.$country->flag));
            @unlink(public_path('images/flags/64x64/'.$country->flag));
            @unlink(public_path('images/flags/128x128/'.$country->flag));

            $flag = $this->uploadImage($request);
        }

        $country->name = $request->get('name_native');
        $country->iso = $request->get('iso');
        $country->iso3 = $request->get('iso3');
        $country->phone_code = $request->get('phone_code');
        $country->vat = $request->get('vat');
        $country->active = $active;
        $country->lang_id = $request->get('lang');
        $country->currency_id = $request->get('currency');
        $country->warehouse_id = $request->get('warehouse');
        $country->flag = $flag;

        $country->save();

        if ($main == 1) {
            $this->setMainCountry($country);
        }

        $this->addPaymentsAndDeliveryMethods($request, $country);

        $country->translations()->delete();

        foreach ($this->langs as $lang):
            $country->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('title_' . $lang->lang),
            ]);
        endforeach;

        Session::flash('message', 'New item has been updated!');

        return redirect()->back();
    }

    public function setMainCountry($country)
    {
        Country::where('main', 1)->update(['main' => 0]);
        $country->update(['main' => 1, 'active' => 1]);
    }

    private function uploadImage($request)
    {
        $file = $request->file('flag');
        $flag = uniqid().$file->getClientOriginalName();
        $image_resize = Image::make($file->getRealPath());

        $image_resize->save(public_path('images/flags/og/' .$flag), 75);
        $image_resize->resize(128, 128)->save('images/flags/128x128/' .$flag, 85);
        $image_resize->resize(64, 64)->save('images/flags/64x64/' .$flag, 85);
        $image_resize->resize(48, 48)->save('images/flags/48x48/' .$flag, 85);
        $image_resize->resize(32, 32)->save('images/flags/32x32/' .$flag, 85);
        $image_resize->resize(24, 24)->save('images/flags/24x24/' .$flag, 85);
        $image_resize->resize(16, 16)->save('images/flags/16x16/' .$flag, 85);

        return $flag;
    }

    public function store(Request $request)
    {
        $toValidate['title_'.$this->lang->lang] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);

        $active = $request->get('active') ? 1 : 0;
        $main = $request->get('main') ? 1 : 0;
        $flag = null;

        if ($request->file('flag')) {
            $flag = $this->uploadImage($request);
        }

        $country = new Country();
        $country->name = $request->get('name_native');
        $country->iso = $request->get('iso');
        $country->iso3 = $request->get('iso3');
        $country->phone_code = $request->get('phone_code');
        $country->vat = $request->get('vat');
        $country->active = $active;
        $country->lang_id = $request->get('lang');
        $country->currency_id = $request->get('currency');
        $country->flag = $flag;
        $country->save();

        if ($main == 1) {
            $this->setMainCountry($country);
        }

        foreach ($this->langs as $lang):
            $country->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('title_' . $lang->lang),
            ]);
        endforeach;

        Session::flash('message', 'New item has been created!');

        return redirect()->route('countries.index');
    }

    public function destroy($id)
    {
        $country = Country::findOrFail($id);

        @unlink(public_path('images/flags/og/'.$country->flag));
        @unlink(public_path('images/flags/16x16/'.$country->flag));
        @unlink(public_path('images/flags/24x24/'.$country->flag));
        @unlink(public_path('images/flags/32x32/'.$country->flag));
        @unlink(public_path('images/flags/48x48/'.$country->flag));
        @unlink(public_path('images/flags/64x64/'.$country->flag));
        @unlink(public_path('images/flags/128x128/'.$country->flag));

        $country->delete();

        session()->flash('message', 'Item has been deleted!');

        return redirect()->route('countries.index');
    }

    private function addPaymentsAndDeliveryMethods($request, $country)
    {
        if ($request->get('deliveries')) {
            $country->deliveries()->delete();
            foreach ($request->get('deliveries') as $delivery => $value) {
                $deliveryItem = Delivery::where('id', $delivery)->first();

                $inheritPrice = $this->getInheritPriceField($request, $deliveryItem);
                $price = $this->getPriceField($request, $deliveryItem);

                $country->deliveries()->create([
                    'delivery_id' => $delivery,
                    'inherit_price' => $inheritPrice,
                    'price' => $inheritPrice ? $deliveryItem->price : $price,
                    'delivery_time' => $request->get('deliveryTime')[$delivery],
                ]);
            }
        }

        if ($request->get('payments')) {
            $country->payments()->delete();
            foreach ($request->get('payments') as $payment => $value) {
                $country->payments()->create([
                    'payment_id' => $payment,
                ]);
            }
        }
    }

    private function getInheritPriceField($request, $delivery)
    {
        if ($request->get('deliveryInherit')) {
            foreach ($request->get('deliveryInherit') as $deliveryId => $inherit) {
                if ($delivery->id == $deliveryId) {
                    return 1;
                }
            }
        }
        return 0;
    }

    private function getPriceField($request, $delivery)
    {
        if ($request->get('deliveryPrice')) {
            foreach ($request->get('deliveryPrice') as $deliveryId => $price) {
                if ($delivery->id == $deliveryId) {
                    return $price;
                }
            }
        }
        return $delivery->price;
    }

}
