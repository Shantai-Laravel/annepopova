<?php

namespace Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\FrontUser;
use App\Models\SubProduct;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Set;
use App\Models\SetTranslation;
use App\Models\Cart;
use App\Models\Promocode;
use App\Models\Currency;
use App\Models\Country;
use App\Models\Delivery;
use App\Models\Payment;
use App\Models\CountryPayment;
use App\Models\CRMOrders;
use App\Models\FrontUserAddress;
use App\Models\CRMOrderItem;
use App\Models\Returns;


class ReturnsController extends Controller
{
    public function index()
    {
        $returns = Returns::get();

        return view('admin::admin.returns.index', compact('returns'));
    }

    public function selectOrderToReturn()
    {
        $userOrders = CRMOrders::where('guest_user_id', 0)->where('main_status', 'completed')->where('change_status_at', '>=', date('Y-m-d', strtotime('-14 days')))->get();
        $guestOrders = CRMOrders::where('user_id', 0)->where('main_status', 'completed')->where('change_status_at', '>=', date('Y-m-d', strtotime('-14 days')))->get();

        return view('admin::admin.returns.selectOrder', compact('userOrders', 'guestOrders'));
    }

    public function returnOrder($id)
    {
        $order = CRMOrders::find($id);

        return view('admin::admin.returns.return', compact('order'));
    }

    public function store(Request $request)
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

        return redirect('/back/returns/'. $return->id .'/show');
    }

    public function show($id)
    {
        $retur = Returns::find($id);

        return view('admin::admin.returns.show', compact('retur'));
    }
}
