<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\FrontUserUnlogged;


class GuestUserController extends Controller
{
  public function index()
  {
    $users = FrontUserUnlogged::orderBy('created_at', 'desc')->get();

    return view('admin::admin.guestUsers.index', compact('users'));
  }

  public function create()
  {
    $countries = Country::where('active', 1)->get();
    $currencies = Currency::where('active', 1)->get();
    $languages = Lang::where('active', 1)->get();
    $payments = Payment::get();

    return view('admin::admin.frontusers.create', compact('countries', 'currencies', 'languages', 'payments'));
  }

  public function store(Request $request)
  {
    $toValidate = [];
    $toValidate['name'] = 'required|min:3';
    $toValidate['surname'] = 'required|min:3';
    $toValidate['email'] = 'required:unique:front_users|email';
    $toValidate['phone'] = 'required|unique:front_users';
    $toValidate['terms_agreement'] = 'required';
    $toValidate['password'] = 'required|min:4';
    $toValidate['repeatpassword'] = 'required|same:password';

    $validator = $this->validate($request, $toValidate);

    $user = FrontUser::create([
        'country_id' => $request->country_id,
        'currency_id' => $request->currency_id,
        'lang_id' => $request->language_id,
        'name' => $request->name,
        'surname' => $request->surname,
        'email' => $request->email,
        'phone' => $request->phone,
        'birthday' => $request->birthday == '' ? null: $request->birthday,
        'terms_agreement' => $request->terms_agreement == 'on' ? 1 : 0,
        'promo_agreement' => $request->promo_agreement == 'on' ? 1 : 0,
        'personaldata_agreement' => $request->personaldata_agreement == 'on' ? 1 : 0,
        'password' => bcrypt($request->password),
        'remember_token' => $request->_token,
        'payment_id' => $request->payment_id,
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

    return redirect()->route('frontusers.index')->withInput();
  }

  public function edit($id)
  {
    $user = FrontUser::with('address')->findOrFail($id);
    $countries = Country::where('active', 1)->get();
    $currencies = Currency::where('active', 1)->get();
    $languages = Lang::where('active', 1)->get();
    $payments = Payment::get();

    return view('admin::admin.frontusers.edit', compact('user', 'countries', 'currencies', 'languages', 'payments'));
  }

  public function update(Request $request, $id)
  {
    $toValidate = [];
    $toValidate['name'] = 'required';
    $toValidate['surname'] = 'required';
    $toValidate['email'] = 'required|email';
    $toValidate['phone'] = 'required';
    $toValidate['terms_agreement'] = 'required';

    $validator = $this->validate($request, $toValidate);

    $user = FrontUser::find($id);

    $user->country_id = $request->country_id;
    $user->currency_id = $request->currency_id;
    $user->lang_id = $request->language_id;
    $user->surname = $request->surname;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->birthday = $request->birthday == '' ? null: $request->birthday;
    $user->terms_agreement = $request->terms_agreement == 'on' ? 1 : 0;
    $user->promo_agreement = $request->promo_agreement == 'on' ? 1 : 0;
    $user->personaldata_agreement = $request->personaldata_agreement == 'on' ? 1 : 0;
    $user->payment_id = $request->payment_id;

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

    return redirect()->back();
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
    return view('admin::admin.frontusers.editPassword', compact('user'));
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

    return redirect()->route('frontusers.index');
  }

  public function destroy($id)
  {
    $user = FrontUserUnlogged::findOrFail($id);
    
    // $user->address()->delete();
    $user->delete();

    session()->flash('message', 'User has been deleted!');

    return redirect()->back();
  }
}
