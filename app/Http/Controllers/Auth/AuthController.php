<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use Session;
use App\FrontUser as Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\WishList;
use App\Models\FrontUser;
use App\Models\FrontUserUnlogged;
use App\Models\Country;
use App\Models\Lang;


class AuthController extends Controller
{
    public function renderLogin()
    {
        return redirect()->route('home');
    }

  /**
   *  post action (vue js)
   *  Check if user authorized, return user
   */
  public function checkAuth()
  {
      $userdata = FrontUser::find(Auth::guard('persons')->id());

      return response()->json(['userdata' => $userdata]);
  }

  /**
   *  post action (vue js)
   *  Authorization, return user
   */
    public function login(Request $request)
    {
        $validator = validator($request->all(), [
            'email' => 'required',
            'password' => 'required|min:4'
        ]);

        if ($validator->fails()) {
            $data['status'] = 'false';
            $data['errors'] = $validator->errors()->all();

            return $data;
        }

        if (Auth::guard('persons')->attempt($request->all())) {
            $checkWish = $this->checkWishList(Auth::guard('persons')->id());
            $checkCart = $this->checkCart(Auth::guard('persons')->id());
            $checkStoc = $this->checkStockOfCart(Auth::guard('persons')->id());
            $data['status'] = 'true';

            $client = Client::findOrFail(Auth::guard('persons')->id());
            $country = Country::findOrFail($client->country_id);

            setcookie('country_id', $country->id, time() + 10000000, '/');
            setcookie('currency_id', $client->currency_id, time() + 10000000, '/');
            setcookie('warehouse_id', $country->warehouse_id, time() + 10000000, '/');
        }else {
            $data['status'] = 'false';
            $data['errors'] = [trans('front.login.error')];
        }

        setcookie('promocode', '', time() + 10000000, '/');

        return $data;
    }

    public function guestLogin(Request $request)
    {
        $frontUser = FrontUserUnlogged::where('user_id', @$_COOKIE['user_id'])->first();

        if (is_null($frontUser)) {
            $frontUser = FrontUserUnlogged::create([
                'user_id' => @$_COOKIE['user_id'],
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'email' => $request->get('email'),
                'country_id' => @$_COOKIE['country_id'],
                'lang_id' => @$_COOKIE['lang_id'],
                'currency_id' => @$_COOKIE['currency_id'],
            ]);
        }

        $data['status'] = 'true';

        // setcookie('promocode', '', time() + 10000000, '/');

        return $data;
    }

    /**
    *  post action
    *  get phone codes
    */
    public function getPhoneCodesList()
    {
        $data['countries'] = Country::where('active', 1)->get();

        $currentCountry = Country::where('id', @$_COOKIE['country_id'])->first();
        if (is_null($currentCountry)) {
            $currentCountry = Country::where('main', 1)->first();
        }

        $data['currentCountry'] = $currentCountry;

        return $data;
    }

    /**
    *  post action
    *  Logout
    */
    public function logout()
    {
        Auth::guard('persons')->logout();

        setcookie('promocode', '', time() + 10000000, '/');

        return redirect('/'.$this->lang->lang.'/homewear');
    }

    /**
    *  get action
    *  Authorization with google, facebook...
    */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
    *  post action
    *  Authorization with google, facebook...
    */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        $checkUser = FrontUser::where('email', $user->getEmail())->first();

        if (is_null($checkUser)) {
            $authUser = FrontUser::where($provider, $user->getId())->first();

            if (is_null($authUser)) {
                $lang = Lang::where('lang', @$_COOKIE['lang_id'])->first();

                $authUser = FrontUser::create([
                    'lang_id'       => $lang ? $lang->id : null,
                    'country_id'    => @$_COOKIE['country_id'],
                    'currency_id'   => @$_COOKIE['currency_id'],
                    'email'         => $user->getEmail(),
                    'name'          => $user->getName(),
                     $provider      => $user->getId(),
                    'remember_token' => $user->token,
                ]);
            }
        }else{
            $checkUser->update([
                $provider => $user->getId(),
            ]);
            $authUser = $checkUser;
        }

        $this->checkCart($authUser->id);
        $this->checkWishList($authUser->id);
        $this->checkStockOfCart($authUser->id);


        setcookie('promocode', '', time() + 10000000, '/');

        Auth::guard('persons')->login($authUser);

        return redirect('/' . @$_COOKIE['lang_id'] . '/cart');
    }

    /**
    *  private method
    *  Check items in cart and set authorized user id
    */
    private function checkCart($user_id)
    {
        $message = 'false';
        $products = Cart::where('user_id', @$_COOKIE['user_id'])->get();
        $products_id = Cart::where('user_id', $user_id)->pluck('product_id')->toArray();

        if($products->count() > 0) {
            $message = 'Nu uita că ai articole suplimentare în coș dintr-o vizită anterioară pe site.';
            Session::flash('message', $message);
            foreach ($products as $key => $product) {
                if(in_array($product->product_id, $products_id)) {
                    Cart::where('id', $product->id)->delete();
                    Cart::where('user_id', $user_id)->where('product_id', $product->product_id)->increment('qty', $product->qty);
                }else{
                    Cart::where('id', $product->id)->update([
                        'is_logged' => 1,
                        'user_id' => $user_id
                    ]);
                }
            }
        }
        return $message;
    }

    /**
    *  private method
    *  Check stock in cart
    */
    public function checkStockOfCart($user_id)
    {
        $cartProducts = Cart::where('user_id', $user_id)->get();
        $message = "false";
        if ($cartProducts->count() > 0) {
            foreach ($cartProducts as $key => $cartProduct) {
                if (!is_null($cartProduct->product)) {
                    if ($cartProduct->product->stock == 0) {
                        Session::flash('messageStok', $message);
                        return $message;
                    }
                }
                if (!is_null($cartProduct->subproduct)) {
                    if ($cartProduct->subproduct->stoc == 0) {
                        $message = "Unul sau mai multe dintre articolele din coșul dvs. de cumpărături sunt vândute. Mutați-le la favoritele dvs. pentru a le putea urmări, ar putea să revină în stoc.";
                        Session::flash('messageStok', $message);
                        return $message;
                    }
                }
            }
        }

        return 'false';
    }

    /**
    *  private method
    *  Check items in wishlist and set authorized user id
    */
    private function checkWishList($user_id)
    {
        $message = 'false';
        $products = WishList::where('user_id', @$_COOKIE['user_id'])->get();

        if($products->count() > 0) {
            $message = 'Nu uita că ai articole suplimentare în wishlist dintr-o vizită anterioară pe site.';
            foreach ($products as $key => $product) {
                WishList::where('id', $product->id)->update([
                    'is_logged' => 1,
                    'user_id' => $user_id
                ]);
            }
        }
        return $message;
    }
}
