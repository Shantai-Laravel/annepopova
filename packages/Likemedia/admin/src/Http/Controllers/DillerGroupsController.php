<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\FrontUser;
use App\Models\DillerGroup;
use App\Models\UserField;
use App\Models\Order;
use App\Models\Retur;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Lang;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductDillerPrice;


class DillerGroupsController extends Controller
{
  public function index()
  {
    $groups = DillerGroup::orderBy('created_at', 'desc')->get();

    return view('admin::admin.diller-groups.index', compact('groups'));
  }


  public function setPrices()
  {
      $products = Product::get();

      foreach ($products as $key => $product) {
          $this->generateForProduct($product);
      }
  }

  public function create()
  {
    $currencies = Currency::where('active', 1)->get();

    return view('admin::admin.diller-groups.create', compact('currencies'));
  }

  public function store(Request $request)
  {
    $toValidate = [];
    $toValidate['name'] = 'required|min:3';

    $validator = $this->validate($request, $toValidate);

    $group = DillerGroup::create([
        'name' => $request->name,
        'type' => $request->type,
        'discount' => $request->discount,
        'applied_on' => $request->applied_on,
    ]);

    if ($request->currencies) {
        foreach ($request->currencies as $key => $currency) {
            $group->groupCurencies()->create(['currency_id' => $key]);
        }
    }

    // $this->generateDillersPrices();

    session()->flash('message', 'Group has been created!');

    return redirect()->route('diller-groups.index')->withInput();
  }

  public function edit($id)
  {
      $group = DillerGroup::findOrFail($id);
      $groupCurrencies = $group->groupCurencies->pluck('currency_id')->toArray();
      $currencies = Currency::where('active', 1)->get();

      return view('admin::admin.diller-groups.edit', compact('group', 'currencies', 'groupCurrencies'));
  }

  public function update(Request $request, $id)
  {
      $toValidate = [];
      $toValidate['name'] = 'required';

      $validator = $this->validate($request, $toValidate);

      $group = DillerGroup::find($id);

      $group->name = $request->name;
      $group->type = $request->type;
      $group->discount = $request->discount;
      $group->applied_on = $request->applied_on;

      $group->save();
      $group->groupCurencies()->delete();

      if ($request->currencies) {
          foreach ($request->currencies as $key => $currency) {
              $group->groupCurencies()->create(['currency_id' => $key]);
          }
      }

      // $this->generateDillersPrices();

      session()->flash('message', 'User has been updated!');

      return redirect()->back();
  }

  public function generateDillersPrices()
  {
      $products = Product::get();

      foreach ($products as $key => $product) {
          $this->generateForProduct($product);
      }
  }

  public function generateForProduct($product)
  {
      $dillerGroups = DillerGroup::get();

      ProductDillerPrice::where('product_id', $product->id)->delete();

      foreach ($dillerGroups as $key => $dillerGroup) {
          foreach ($product->prices as $key => $price) {
              if ($dillerGroup->applied_on == 'b2b') {
                  ProductDillerPrice::create([
                      'product_id' => $product->id,
                      'diller_group_id' => $dillerGroup->id,
                      'currency_id' => $price->currency->id,
                      'old_price' => $price->b2b_old_price - ($price->b2b_old_price * $dillerGroup->discount / 100),
                      'price' => $price->b2b_old_price - ($price->b2b_old_price * $dillerGroup->discount / 100),
                      // 'old_price' => $price->b2b_old_price - ($price->b2b_old_price * $dillerGroup->discount / 100),
                      // 'price' => $price->b2b_price - ($price->b2b_price * $dillerGroup->discount / 100),
                  ]);
              }else{
                  ProductDillerPrice::create([
                      'product_id' => $product->id,
                      'diller_group_id' => $dillerGroup->id,
                      'currency_id' => $price->currency->id,
                      'old_price' => $price->old_price - ($price->old_price * $dillerGroup->discount / 100),
                      'price' => $price->old_price - ($price->old_price * $dillerGroup->discount / 100),
                      // 'old_price' => $price->old_price - ($price->old_price * $dillerGroup->discount / 100),
                      // 'price' => $price->price - ($price->price * $dillerGroup->discount / 100),
                  ]);
              }
          }
      }
  }

  public function destroy($id)
  {
    $group = DillerGroup::findOrFail($id);
    $group->groupCurencies()->delete();
    $group->prices()->delete();

    $group->delete();

    session()->flash('message', 'Group has been deleted!');

    return redirect()->route('diller-groups.index');
  }
}
