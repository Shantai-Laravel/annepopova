<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\FrontUser;
use App\Models\UserField;
use App\Models\Order;
use App\Models\Retur;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Lang;
use App\Models\Payment;
use App\Models\DillerGroup;


class DillerController extends Controller
{
  public function index()
  {
    $users = FrontUser::orderBy('created_at', 'desc')->where('customer_type', 'diller')->get();

    return view('admin::admin.dillers.index', compact('users'));
  }

  public function create()
  {
    $countries = Country::where('active', 1)->get();
    $currencies = Currency::where('active', 1)->get();
    $languages = Lang::where('active', 1)->get();
    $payments = Payment::get();
    $dillerGroups = DillerGroup::get();

    return view('admin::admin.dillers.create', compact('countries', 'currencies', 'languages', 'payments', 'dillerGroups'));
  }

  public function store(Request $request)
  {
    $toValidate = [];
    $toValidate['name'] = 'required|min:3';
    $toValidate['company'] = 'required|min:3';
    $toValidate['email'] = 'required:unique:front_users|email';
    $toValidate['phone'] = 'required|unique:front_users';
    $toValidate['password'] = 'required|min:4';
    $toValidate['repeatpassword'] = 'required|same:password';

    $validator = $this->validate($request, $toValidate);
    $active = $request->active == 'on' ? 1 : 0;

    $user = FrontUser::create([
        'country_id' => $request->country_id,
        'currency_id' => $request->currency_id,
        'lang_id' => $request->language_id,
        'active_diller' => $active,
        'name' => $request->name,
        'surname' => $request->surname,
        'email' => $request->email,
        'phone' => $request->phone,
        'company' => $request->company,
        'password' => bcrypt($request->password),
        'remember_token' => $request->_token,
        'payment_id' => $request->payment_id,
        'customer_type' => $request->customer_type,
        'diller_group_id' => $request->diller_group,
    ]);

    $user->address()->create([
        'country' => $request->country,
        'region' => $request->region,
        'location' => $request->location,
        'address' => $request->address,
        'code' => $request->code,
        'apartment' => $request->apartment,
        'payment_id' => $request->payment_id,
    ]);

    session()->flash('message', 'User has been created!');

    if ($request->customer_type == 'diller') {
        return redirect('/back/dillers/'. $user->id .'/edit');
    }else{
        return redirect('/back/frontusers/'. $user->id .'/edit');
    }
  }

  public function edit($id)
  {
    $user = FrontUser::with('address')->findOrFail($id);
    $countries = Country::where('active', 1)->get();
    $currencies = Currency::where('active', 1)->get();
    $languages = Lang::where('active', 1)->get();
    $payments = Payment::get();
    $dillerGroups = DillerGroup::get();

    return view('admin::admin.dillers.edit', compact('user', 'countries', 'currencies', 'languages', 'payments', 'dillerGroups'));
  }

  public function update(Request $request, $id)
  {
    $toValidate = [];
    $toValidate['name'] = 'required';
    $toValidate['company'] = 'required';
    $toValidate['email'] = 'required|email';
    $toValidate['phone'] = 'required';

    $validator = $this->validate($request, $toValidate);

    $active = $request->active == 'on' ? 1 : 0;

    $user = FrontUser::find($id);

    $user->country_id = $request->country_id;
    $user->currency_id = $request->currency_id;
    $user->lang_id = $request->language_id;
    $user->company = $request->company;
    $user->active_diller = $active;
    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->payment_id = $request->payment_id;
    $user->customer_type = $request->customer_type;
    $user->diller_group_id = $request->diller_group;

    $user->save();

    $user->address()->delete();

    $user->address()->create([
        'country' => $request->country,
        'region' => $request->region,
        'location' => $request->location,
        'address' => $request->address,
        'code' => $request->code,
        'apartment' => $request->apartment,
    ]);

    session()->flash('message', 'User has been updated!');

    if ($request->customer_type == 'diller') {
        return redirect('/back/dillers/'. $user->id .'/edit');
    }else{
        return redirect('/back/frontusers/'. $user->id .'/edit');
    }
  }

  public function addAddress(Request $request, $id) {
      $toValidate['country'] = 'required';
      $toValidate['region'] = 'required';
      $toValidate['location'] = 'required';
      $toValidate['address'] = 'required';

      $validator = $this->validate($request, $toValidate);

      $user = FrontUser::find($id);

      $user->address()->create([
          'addressname' => $request->addressname,
          'country' => $request->country,
          'region' => $request->region,
          'location' => $request->location,
          'address' => $request->address,
          'code' => $request->code,
          'homenumber' => $request->homenumber,
          'entrance' => $request->entrance,
          'floor' => $request->floor,
          'apartment' => $request->apartment,
          'comment' => $request->comment
      ]);

      return redirect()->back()->withInput()->withSuccess(trans('front.success'));
  }

  public function updateAddress(Request $request, $user_id, $address_id)
  {
      $toValidate['country'] = 'required';
      $toValidate['region'] = 'required';
      $toValidate['location'] = 'required';
      $toValidate['address'] = 'required';

      $validator = $this->validate($request, $toValidate);

      $user = FrontUser::find($user_id);

      $user->address()->where('id', $address_id)->update([
          'country' => $request->country,
          'region' => $request->region,
          'location' => $request->location,
          'address' => $request->address,
          'code' => $request->code,
          'homenumber' => $request->homenumber,
          'entrance' => $request->entrance,
          'floor' => $request->floor,
          'apartment' => $request->apartment,
          'comment' => $request->comment
      ]);

      return redirect()->back()->withInput();
  }

  public function editPassword($id)
  {
    $user = FrontUser::findOrFail($id);
    return view('admin::admin.dillers.editPassword', compact('user'));
  }

  public function updatePassword(Request $request, $id)
  {
    $toValidate = [];
    $toValidate['oldpassword'] = 'required';
    $toValidate['newpassword'] = 'required|min:4';
    $toValidate['repeatpassword'] = 'required|same:newpassword';

    $validator = $this->validate($request, $toValidate);

    $user = FrontUser::where('id', $id)->first();

    if (!Hash::check($request->oldpassword, $user->password)) {
        return redirect()->back()->withInput()->withErrors('Incorrect old password');
    }

    $user->password = bcrypt($request->newpassword);
    $user->save();

    session()->flash('message', 'Password has been updated!');

    return redirect()->route('dillers.index');
  }

  public function destroy($id)
  {
    $user = FrontUser::findOrFail($id);

    $user->address()->delete();
    $user->delete();

    session()->flash('message', 'User has been deleted!');

    return redirect()->route('dillers.index');
  }
}
