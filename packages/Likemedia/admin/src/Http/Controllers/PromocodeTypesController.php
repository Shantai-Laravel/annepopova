<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PromocodeType;
use App\Models\Traduction;
use App\Models\Product;
use App\Models\TraductionTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;


class PromocodeTypesController extends Controller
{
    public function index(Request $request)
    {
        $promocodeTypes = PromocodeType::get();

        return view('admin::admin.promocodeTypes.index', compact('promocodeTypes'));
    }


    public function create()
    {
        return view('admin::admin.promocodeTypes.create');
    }


    public function store(Request $request)
    {
        $toValidate['name'] = 'required|max:255|unique:promocode_types';
        $toValidate['discount'] = 'required|max:255';
        $toValidate['times'] = 'required|max:255';
        $toValidate['treshold'] = 'required|max:255';
        $toValidate['period'] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);

        $promocodeType = new PromocodeType();
        $promocodeType->name  = $request->name;
        $promocodeType->discount  = $request->discount;
        $promocodeType->times  = $request->times;
        $promocodeType->treshold  = $request->treshold;
        $promocodeType->period  = $request->period;
        $promocodeType->save();

        Session::flash('message', 'New promocode has been created!');

        return redirect('back/promocodesType/');
    }


    public function show($id)
    {
        return redirect()->route('promotions.index');
    }


    public function edit($id)
    {
        $promocode = PromocodeType::findOrFail($id);

        return view('admin::admin.promocodeTypes.edit', compact('promocode'));
    }


    public function update(Request $request, $id)
    {
        $toValidate['name'] = 'required|max:255';
        $toValidate['discount'] = 'required|max:255';
        $toValidate['times'] = 'required|max:255';
        $toValidate['treshold'] = 'required|max:255';
        $toValidate['period'] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);

        $promocodeType = PromocodeType::findOrFail($id);
        $promocodeType->name  = $request->name;
        $promocodeType->discount  = $request->discount;
        $promocodeType->times  = $request->times;
        $promocodeType->treshold  = $request->treshold;
        $promocodeType->period  = $request->period;
        $promocodeType->save();

        Session::flash('message', 'New promocode has been updated!');
        return redirect()->back();
    }


    public function destroy($id)
    {
        $promotion = Promocode::findOrFail($id);

        $promotion->delete();

        session()->flash('message', 'Promocode has been deleted!');

        return redirect()->route('promocodes.index');
    }


    public function getPromocodeTypes(Request $request)
    {
        $promoType = PromocodeType::where('id', $request->get('value'))->first();

        $date =  empty($request->get('date')) ? date('d.m.Y') : date('d.m.Y', strtotime($request->get('date')));

        $data = view('admin::admin.promocodes.promoTypeBlock', compact('promoType', 'date'))->render();

        return json_encode($data);
    }

}
