<?php

namespace Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Models\Promocode;
use App\Models\PromocodeType;
use App\Models\Product;


class PromocodesController extends Controller
{
    public function index(Request $request)
    {
        $sort['by'] = 'id';

        if ($request->get('sort') == 'name') {
            $sort['by'] = 'name';
        }

        if ($request->get('sort') == 'valid') {
            $sort['by'] = 'valid_to';
        }

        $promocodesAll = Promocode::get();
        $this->updatePromocodesStatus($promocodesAll);

        $promocodes = Promocode::when($request->get('type'), function($query) use ($request){
                            return $query->where('type_id', $request->get('type'));
                        })
                        ->when($request->get('sort'), function($query) use ($request){
                            return $query->orderBy($request->get('sort'), 'asc');
                        })
                        ->when(!$request->get('sort'), function($query) use ($request){
                            return $query->orderBy('id', 'desc');
                        })
                        ->get();


        $promocodeTypes = Promocode::orderBy($sort['by'], 'desc')->groupBy('type_id')->get();

        return view('admin::admin.promocodes.index', compact('promocodes', 'promocodeTypes'));
    }

    public function updatePromocodesStatus($promocodes)
    {
        if (count($promocodes) > 0) {
            foreach ($promocodes as $key => $promocode) {
                if (strtotime(date('m/d/Y')) > strtotime($promocode->valid_to)){
                    Promocode::where('id', $promocode->id)->update([ 'status' => 'expired' ]);
                }else{
                    if ($promocode->to_use == 0){
                        Promocode::where('id', $promocode->id)->update([ 'status' => 'valid' ]);
                    }
                    if ($promocode->to_use == $promocode->times){
                        Promocode::where('id', $promocode->id)->update([ 'status' => 'used' ]);
                    }
                    if (($promocode->to_use < $promocode->times) && ($promocode->to_use > 0 )){
                        Promocode::where('id', $promocode->id)->update([ 'status' => 'partially' ]);
                    }
                }
            }
        }
    }


    public function create()
    {
        $types = PromocodeType::get();
        $promoType = PromocodeType::where('id', 0)->first();
        $date = date('d.m.Y');
        return view('admin::admin.promocodes.create', compact('types', 'promoType', 'date'));
    }


    public function store(Request $request)
    {
        $promoType = PromocodeType::where('id', $request->type)->first();

        $toValidate['type'] = 'required|max:255';
        $toValidate['valid_from'] = 'required|max:255';
        $toValidate['valid_to'] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);

        $promotion = new Promocode();
        $promotion->name  = $promoType->name;
        $promotion->type_id  = $request->type;
        $promotion->treshold  = $request->treshold;
        $promotion->discount  = $promoType->discount;
        $promotion->period  = $promoType->period;
        $promotion->times  = $promoType->times;
        $promotion->to_use  = 0;
        $promotion->user_id  = 0;
        $promotion->valid_from  = $request->valid_from;
        $promotion->valid_to  = $request->valid_to;
        $promotion->save();

        Promocode::where('id', $promotion->id)->update([
            'name' => $promoType->name.'-'.$promotion->id,
        ]);

        Session::flash('message', 'New promocode has been created!');

        return redirect('back/promocodes/');
    }


    public function show($id)
    {
        return redirect()->route('promotions.index');
    }


    public function edit($id)
    {
        $promocode = Promocode::findOrFail($id);
        $types = PromocodeType::get();
        $promoType = PromocodeType::where('id', $promocode->type_id)->first();
        $date = date('d.m.Y');

        return view('admin::admin.promocodes.edit', compact('promocode', 'types', 'promoType', 'date'));
    }


    public function update(Request $request, $id)
    {
        $promoType = PromocodeType::where('id', $request->type)->first();

        $toValidate['type'] = 'required|max:255';
        $toValidate['valid_from'] = 'required|max:255';
        $toValidate['valid_to'] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);

        $promotion = Promocode::findOrFail($id);
        $promotion->name  = $promoType->name.'-'.$promotion->id;
        $promotion->type_id  = $request->type;
        $promotion->treshold  = $request->treshold;
        $promotion->discount  = $promoType->discount;
        $promotion->period  = $promoType->period;
        // $promotion->times  = $promoType->times;
        // $promotion->to_use  = 0;
        // $promotion->user_id  = 0;
        $promotion->valid_from  = $request->valid_from;
        $promotion->valid_to  = $request->valid_to;
        $promotion->save();

        Session::flash('message', 'New promocode has been created!');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $promotion = Promocode::findOrFail($id);

        $promotion->delete();

        session()->flash('message', 'Promocode has been deleted!');

        return redirect()->route('promocodes.index');
    }

}
