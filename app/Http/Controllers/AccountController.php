<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\WishListController;
use App\Models\Cart;
use App\Models\Country;
use App\Models\Returns;
use App\Models\Retur;
use App\Models\ReturProduct;
use App\Models\CRMOrders;
use App\Models\Promocode;
use App\FrontUser;


class AccountController extends Controller
{
    /**
     *  get action
     *  Render Personal data page
     */
    public function index() {
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $countries = Country::where('active', 1)->get();

        return view('front.'. $this->device .'.account.personalData', compact('userdata', 'countries'));
    }

    public function getPromocodes()
    {
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $promocodes = Promocode::where('user_id',  auth('persons')->id())->get();

        return view('front.'. $this->device .'.account.promocodes', compact('userdata', 'promocodes'));
    }

    public function getCart()
    {
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $carts['products'] = Cart::with(['product.mainPrice', 'product.personalPrice', 'product.translation', 'product.mainImage', 'product.category'])
                                      ->where('user_id', $userdata->id)
                                      ->where('parent_id', null)
                                      ->where('product_id', '!=', null)
                                      ->orderBy('id', 'desc')
                                      ->get();

        $carts['subproducts'] = Cart::with(['subproduct.price', 'subproduct.product.mainPrice', 'subproduct.product.personalPrice', 'subproduct.product.translation', 'subproduct.product.mainImage', 'subproduct.product.category', 'subproduct.parameterValue.translation'])
                                            ->where('user_id', $userdata->id)
                                            ->where('parent_id', null)
                                            ->where('subproduct_id', '!=', 0)
                                            ->orderBy('id', 'desc')
                                            ->get();

        $carts['sets'] = Cart::with(['children.subproduct.product.translation', 'children.subproduct.product.category', 'children.subproduct.product.mainPrice', 'children.subproduct.product.mainImage', 'children.subproduct.parameterValue.translation', 'set.price', 'set.mainPrice', 'set.personalPrice', 'set.translation', 'set.mainPhoto', 'set.collection', 'set.products.translation', 'set.products.mainImage', 'set.products.firstSubproduct',           'set.products.subproducts.parameterValue.translation'])
                                      ->where('user_id', $userdata->id)
                                      ->where('parent_id', null)
                                      ->where('set_id', '!=', 0)
                                      ->orderBy('id', 'desc')
                                      ->get();

        return view('front.'. $this->device .'.account.cart', compact('userdata', 'carts'));
    }

    public function getWishlist()
    {
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $wishList = new WishListController();

        $wishs = $wishList->getWishItems();

        return view('front.'. $this->device .'.account.wishlist', compact('userdata', 'wishs'));
    }
    /**
     *  post action
     *  Save personal data
     */
    public function savePersonalData(Request $request) {

        $this->validate($request, array(
          'name' => 'required|min:3',
          'phone' => 'required',
          'email' => 'required'
        ));

        $userdata = FrontUser::where('id', auth('persons')->id())->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'name' => $request->name,
            'code' => '+'.$request->code,
        ]);

        return redirect()->back()->withSuccess(trans('front.cabinet.success'));
    }
    /**
     *  post action
     *  Save password
     */
    public function savePass(Request $request) {
        $this->validate($request, array(
            'newpass' => 'required|min:3',
            'repeatnewpass' => 'required|same:newpass'
        ));

        $user = FrontUser::where('id', auth('persons')->id())->first();

        // if (!Hash::check($request->oldpass, $user->password)) {
        //     return redirect()->back()->withErrors(trans('front.cabinet.changePass.error'));
        // }

        $user->password = bcrypt($request->newpass);
        $user->save();

        return redirect()->back()->withSuccess(trans('front.cabinet.success'));
    }
    /**
     *  post action
     *  Create new address
     */
    public function addAddress(Request $request) {
        $this->validate($request, array(
          'country_id' => 'required',
          'region' => 'required',
          'city' => 'required',
          'address' => 'required'
        ));

        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $userdata->address()->create([
            'country' => $request->get('country_id'),
            'region' => $request->get('region'),
            'location' => $request->get('city'),
            'address' => $request->get('address'),
            'homenumber' => $request->get('homenumber'),
            'code' => $request->get('code'),
        ]);

        return redirect()->back()->withSuccess(trans('front.cabinet.success'));
    }

    /**
     *  get action
     *  Delete account
     */
    public function removeAccount() {
        $user = FrontUser::where('id', auth('persons')->id())->delete();

        return redirect()->back()->withSuccess('Account was deleted!');
    }

    /**
     *  post action
     *  Update address
     */
    public function saveAddress(Request $request, $id) {
        $this->validate($request, array(
          'region' => 'required',
          'city' => 'required',
          'address' => 'required'
        ));

        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $address = $userdata->address()->where('id', $id)->first();

        if(count($address) > 0) {
          $address->country = $request->get('country_id');
          $address->region = $request->get('region');
          $address->location = $request->get('city');
          $address->address = $request->get('address');
          $address->homenumber = $request->get('homenumber');
          $address->code = $request->get('code');
          $address->save();
        }

        return redirect()->back()->withSuccess(trans('front.cabinet.success'));
    }

    /**
     *  get action
     *  Render History page
     */
    public function history() {
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $orders = CRMOrders::where('user_id', auth('persons')->id())->get();

        return view('front.'. $this->device .'.account.history', compact('userdata', 'orders'));
    }

    /**
     *  post action
     *  Add history items to cart
     */
    public function historyCart($id) {
        $order = Order::find($id);

        foreach ($order->orderProducts as $order):
            Cart::create([
              'product_id' => $order->product_id,
              'subproduct_id' => $order->subproduct_id,
              'user_id' => auth('persons')->id(),
              'qty' => $order->qty,
              'is_logged' => 1
            ]);
        endforeach;

        return redirect()->route('cart')->withSuccess(trans('front.cabinet.historyAll.historyCart'));
    }
    /**
     *  get action
     *  Render HistoryOpen page
     */
    public function historyOrder($id) {
        $userdata = FrontUser::where('id', auth('persons')->id())->first();
        $order = CRMOrders::find($id);

        return view('front.'. $this->device .'.account.historyOpen', compact('userdata', 'order'));
    }

    /**
     *  post action
     *  Add history products to cart
     */
    public function historyCartProduct($id) {
        $order = OrderProduct::find($id);

        Cart::create([
          'product_id' => $order->product_id,
          'subproduct_id' => $order->subproduct_id,
          'user_id' => auth('persons')->id(),
          'qty' => $order->qty,
          'is_logged' => 1
        ]);

        return redirect()->route('cart')->withSuccess(trans('front.cabinet.historyAll.historyCartProduct'));
    }
    /**
     *  get action
     *  Render Return Page
     */
    public function returns() {
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $returns = Returns::where('user_id', auth('persons')->id())->get();

        return view('front.'. $this->device .'.account.returns.return', compact('userdata', 'returns'));
    }

    public function showReturn($id)
    {
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $return = Returns::where('user_id', auth('persons')->id())->where('id', $id)->first();

        return view('front.'. $this->device .'.account.returns.showReturn', compact('userdata', 'return'));
    }

    public function createReturn()
    {
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $orders = CRMOrders::where('user_id', auth('persons')->id())
                        ->where('change_status_at', '>=', date('Y-m-d', strtotime('-20'.' days')))
                        ->where('main_status', 'completed')
                        ->get();

        return view('front.'. $this->device .'.account.returns.createReturn', compact('userdata', 'orders'));
    }

    public function createReturnFromOrder($id)
    {
        $userdata = FrontUser::where('id', auth('persons')->id())->first();

        $order = CRMOrders::where('user_id', auth('persons')->id())
                        ->where('change_status_at', '>=', date('Y-m-d', strtotime('-20'.' days')))
                        ->where('main_status', 'completed')
                        ->where('id', $id)
                        ->first();

        return view('front.'. $this->device .'.account.returns.createReturnFromOrder', compact('userdata', 'order'));
    }

    public function storeReturn(Request $request)
    {
        $paypal_email = null;
        $bank = null;
        $iban = null;

        if ($request->get('return_method') == 'paypal') {
            $paypal_email = $request->get('paypal_email');
        }else{
            $bank = $request->get('bank');
            $iban = $request->get('iban');
        }
        if (count($request->get('item_id')) > 0) {

            $order = CRMOrders::find($request->get('order_id'));

            $img = '';

            if (!empty($request->file('img'))) {
                $img = time() . '-' . $request->img->getClientOriginalName();
                $request->img->move('images/returns', $img);
            }

            $return = Returns::create([
                'user_id' => $order->user_id ? $order->user_id : 0,
                'guest_id' => $order->guest_user_id ? $order->guest_user_id : 0,
                'order_id' => $order->id,
                'payment' => $request->get('return_method'),
                'reason' => $request->get('reason'),
                'additional' => $request->get('additional'),
                'datetime' => date('Y-m-d h:i'),
                'image' => $img,
                'iban' => $iban,
                'bank' => $bank,
                'paypal_email' => $paypal_email,
            ]);

            foreach ($request->get('item_id') as $key => $item) {
                CRMOrderItem::where('id', $item)->update([
                    'return_id' => $return->id,
                ]);
            }

        }else{
            Session::flash('error', 'select some products please!');
            return redirect()->back();
        }

        return redirect()->back();
    }

    /**
     *  get action
     *  Render ReturnOpen page
     */
    public function returnOrder($id) {
        $userdata = FrontUser::where('id', auth('persons')->id())->first();
        $order = Order::find($id);
        $orderProductsId = $order->orderProducts()->pluck('id')->toArray();

        $returnProducts = ReturProduct::whereIn('orderProduct_id', $orderProductsId)->get();
        if(count($returnProducts) > 0) {
            $return = Retur::find($returnProducts[0]->return_id);
        } else {
            $return = [];
        }

        return view('front.'. $this->device .'.cabinet.returnOpen', compact('userdata', 'order', 'return'));
    }
    /**
     *  post action
     *  Add new product to return
     */
    public function addProductsToReturn($id) {
        $orderProduct = OrderProduct::find($id);
        if(count($orderProduct) > 0) {
            if(request('returnOrder') == 1) {
                if(request('return_id') != 0) {
                    $return = Retur::find(request('return_id'));

                    if(count($return) > 0) {
                        $return->returnProducts()->create([
                            'orderProduct_id' => $orderProduct->id,
                            'product_id' => $orderProduct->product_id,
                            'subproduct_id' => $orderProduct->subproduct_id,
                            'qty' => $orderProduct->qty
                        ]);
                    }
                } else {
                     $return = Retur::create([
                         'user_id' => auth('persons')->id(),
                         'is_active' => 0,
                         'status' => 'new'
                     ]);

                     $return->returnProducts()->create([
                         'orderProduct_id' => $orderProduct->id,
                         'product_id' => $orderProduct->product_id,
                         'subproduct_id' => $orderProduct->subproduct_id,
                         'qty' => $orderProduct->qty
                     ]);
                }

                if(count($return) > 0) {
                    $return->amount = $this->getAmount($return->returnProducts);
                    $return->save();
                }

                return redirect()->back()->withSuccess(trans('front.cabinet.returnAll.addSuccess'));

            } else {
                 $returnProducts = ReturProduct::where('return_id', request('return_id'))->get();
                 $return = Retur::find($returnProducts[0]->return_id);

                 if(count($returnProducts) > 1 && count($return) > 0) {
                     ReturProduct::where('orderProduct_id', $orderProduct->id)->where('product_id', $orderProduct->product_id)->delete();

                     $return->amount = $this->getAmount($return->returnProducts);
                     $return->save();
                 } else {
                     $return->delete();
                     $return->returnProducts()->delete();
                 }

                 return redirect()->back()->withSuccess(trans('front.cabinet.returnAll.deleteSuccess'));
            }
        }
    }
    /**
     *  post action
     *  Save return
     */
    public function saveReturn($id) {
        if($id != 0) {
            $return = Retur::find($id);
            $orderProduct = OrderProduct::find($return->returnProducts()->first()->orderProduct_id);
            $order = $orderProduct->order;
            $return->motive = request('motive');
            $return->payment = request('payment');
            $return->is_active = 1;
            $return->address_id = $order->address_id;
            $return->delivery = $order->delivery;
            $return->amount = $this->getAmount($return->returnProducts);
            $return->save();

            return redirect()->back()->withSuccess(trans('front.cabinet.success'));
        } else {
          return redirect()->back()->withErrors(trans('front.cabinet.returnAll.error'));
        }
    }


    /**
     *  private method
     *  Calculate amount of products
     */
    private function getAmount($cartProducts) {
        $amount = 0;
        foreach ($cartProducts as $key => $cartProduct):
          $amount +=  $cartProduct->product->actual_price * $cartProduct->qty;
        endforeach;
        return $amount;
    }

}
