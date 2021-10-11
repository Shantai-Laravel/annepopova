<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Delivery;
use App\Models\Currency;
use App\Models\CountryDelivery;

class DeliveriesController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::get();
        $mainCurrency = Currency::where('type', 1)->first();

        return view('admin::admin.deliveries.index', compact('deliveries', 'mainCurrency'));
    }

    public function edit($id)
    {
        $delivery = Delivery::findOrFail($id);
        $mainCurrency = Currency::where('type', 1)->first();

        return view('admin::admin.deliveries.edit', compact('delivery', 'mainCurrency'));
    }

    public function update(Request $request, $id)
    {
        $delivery = Delivery::findOrFail($id);

        $toValidate['title_'.$this->lang->lang] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);

        $delivery->alias = $request->get('title_'.$this->lang->lang);
        $delivery->price = $request->get('price');
        $delivery->delivery_time = $request->get('delivery_time');
        $delivery->save();

        $this->setPricePerCountry($delivery);
        $delivery->translations()->delete();

        foreach ($this->langs as $lang):
            $delivery->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('title_' . $lang->lang),
            ]);
        endforeach;

        return redirect()->back();
    }

    public function setPricePerCountry($delivery)
    {
        $countries = $delivery->countries()->get();

        if ($countries->count() > 0) {
            foreach ($countries as $key => $country) {
                if ($country->inherit_price == 0) {
                    CountryDelivery::where('id', $country->id)->update([
                        'price' => $delivery->price,
                    ]);
                }
            }
        }

    }

    public function store(Request $request)
    {
        $toValidate['name_'.$this->lang->lang] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);

        $delivery = new Delivery();
        $delivery->alias = str_slug($request->get('name_'.$this->lang->lang));
        $delivery->price = $request->get('price');
        $delivery->delivery_time = $request->get('delivery_time');
        $delivery->save();

        foreach ($this->langs as $lang):
            $delivery->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('name_' . $lang->lang),
            ]);
        endforeach;

        Session::flash('message', 'New item has been created!');

        return redirect()->route('delivery.index');
    }

    public function destroy($id)
    {
        $delivery = Delivery::findOrFail($id);

        $delivery->delete();

        session()->flash('message', 'Item has been deleted!');

        return redirect()->route('delivery.index');
    }
}
