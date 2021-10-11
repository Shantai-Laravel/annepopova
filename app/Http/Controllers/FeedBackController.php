<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\FeedBack;
use App\Models\PromocodeType;
use App\Models\Promocode;
use Session;


class FeedBackController extends Controller
{
    public function index()
    {
        return view('front.pages.thanks');
    }

    public function generatePromocode(Request $request)
    {
        if ($request->get('name') && $request->get('email')) {

            $promoType = PromocodeType::where('name', 'new-user')->first();

            if (!is_null($promoType)) {
                $promocode = Promocode::create([
                  'name' => 'new-user'.str_random(5),
                  'type_id' => $promoType->id,
                  'discount' => $promoType->discount,
                  'valid_from' => date('Y-m-d'),
                  'valid_to' => date('Y-m-d', strtotime(' + '.$promoType->period.' days')),
                  'period' => $promoType->period,
                  'treshold' => $promoType->treshold,
                  'to_use' => 0,
                  'times' => $promoType->times,
                  'status' => 'valid',
                  'user_id' => 0
                ]);

                $feedback = new FeedBack();
                $feedback->first_name = $request->get('name');
                $feedback->email = $request->get('email');
                $feedback->subject = 'Generate new promocode.';
                $feedback->status = 'new';
                $feedback->message = $promocode->name;
                $feedback->save();

                $data['feedback'] = $feedback;
                $data['promocode'] = $promocode;

                $status = Mail::send('mails.generatePromocode', $data, function($message) use ($request){
                    $message->to($request->get('email'), 'Contact User')->from('julia.allert.fashion@gmail.com')->subject('promocode for you!');
                });

                $user = null;
                $order = null;

                return view('front.pages.thanks', compact('user', 'promocode', 'products', 'order'));
            }
        }
        return redirect()->back();
    }


    public function contactFeedBack(Request $request)
    {
        $data['name'] = $request->get('name');
        $data['email'] = $request->get('email');
        $data['contact_message'] = $request->get('message');

        $to = trans('vars.Contacts.email');
        // $to = getContactInfo('emailfront')->translationByLanguage($this->lang->id)->first()->value;
        $to = 'iovitatudor@gmail.com';

        $feedback = new FeedBack();
        $feedback->first_name = request('name');
        $feedback->email = request('email');
        $feedback->subject = 'Contact Form.';
        $feedback->message = request('message');
        $feedback->status = 'new';

        $feedback->save();

        Mail::send('mails.contactForm.admin', $data, function($message) use ($to){
            $message->to($to, 'Contactează-ne')->from('julia.allert.fashion@gmail.com')->subject('Contact Us.');
        });

        $email = $request->get('email');

        Mail::send('mails.contactForm.user', $data, function($message) use ($email){
            $message->to($email, 'You left a message on the online shop juliaallert.com')->from('julia.allert.fashion@gmail.com')->subject('You left a message on the online shop juliaallert.com');
        });

        Session::flash('message', 'Va multumim, in scrut timp managerii nostri va vor contacta.');
        return redirect()->back();
    }
}
