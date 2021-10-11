<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\WishList;
use App\Models\Order;
use App\Models\Retur;
use App\Models\Cart;
use App\Models\Lang;
use App\Models\Currency;
use App\Models\Country;
use App\Models\FrontUserUnlogged;
use App\FrontUser;
use Illuminate\Support\Facades\Mail;


class RegistrationController extends Controller
{
    /**
    *  post action (vue js)
    *  register user, return user
    */
    public function registration(Request $request)
    {
        $validator = validator($request->all(), [
            'email' => 'required|unique:front_users',
            'name' => 'required|min:3',
            'phone' => 'required|min:3',
            'password' => 'required|min:4',
            'agree' => 'required'
        ]);

        if ($validator->fails()) {
            $data['status'] = 'false';
            $data['errors'] = $validator->errors()->all();

            return $data;
        }

        $lang = Lang::where('lang', @$_COOKIE['lang_id'])->first();
        $currency = Currency::where('id', @$_COOKIE['currency_id'])->first();
        $country = Country::where('id', @$_COOKIE['country_id'])->first();

        $user = FrontUser::create([
            'lang_id' => $lang->id,
            'country_id' => @$currency->id,
            'currency_id' => $country->id,
            'name' => $request->get('name'),
            'phone' => $request->get('phone'),
            'email' => $request->get('email'),
            'email' => $request->get('email'),
            'customer_type' => $request->get('consumerType'),
            'company' => $request->get('company'),
            'password' => bcrypt($request->get('password')),
            'remember_token' => $request->get('_token')
        ]);

        $unloggedUser = FrontUserUnlogged::where('user_id', @$_COOKIE['user_id'])->first();

        $this->mergeCart($unloggedUser, $user->id);
        $this->mergeWishList($unloggedUser, $user);

        Auth::guard('persons')->login($user);

        $email = $request->get('email');
        $data['name'] = $user->name;
        $status = Mail::send('mails.auth.register', $data, function($message) use ($email) {
            $message->to($email);
            $message->from('info@annepopova.com');
            $message->subject(trans('vars.Email-templates.emailRegistrationSubject').'info@annepopova.com');
        });

        return response()->json(['user'=> Auth::guard('persons')->user()], 200);
    }

    /**
    *  private method
    *  merge cart of guest, whist registered and authorized
    */
    private function mergeCart($unloggedUser, $user_id) {
        $cartProducts = Cart::where('user_id', @$_COOKIE['user_id'])->get();

        if(count($cartProducts) > 0) {
            foreach ($cartProducts as $cartProduct) {
                $cartProduct->user_id = $user_id;
                $cartProduct->save();
            }
        }
    }

    /**
    *  private method
    *  merge wishList of guest, whist registered and authorized
    */
    private function mergeWishList($unloggedUser, $user) {
        $wishListProducts = WishList::where('user_id', @$_COOKIE['user_id'])->get();

        if(count($wishListProducts) > 0) {
            foreach ($wishListProducts as $wishListProduct) {
                $wishListProduct->user_id = $user->id;
                $wishListProduct->save();
            }
        }
    }

    /**
    *  private method
    *  merge orders of guest, whist registered and authorized
    */
    private function mergeOrders($unloggedUser, $user) {
        $orders = Order::where('user_id', $unloggedUser->id)->get();

        if(count($orders) > 0) {
            foreach ($orders as $order) {
                $order->user_id = $user->id;
                $order->save();
            }
        }
    }

}
