<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\FrontUser;


class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest');
    }

    public function sendEmailCode(Request $request)
    {
        $email = $request->get('email');

        $user = FrontUser::where('email', $request->get('email'))->first();

        if (!is_null($user)) {
            session()->put(['code' => str_random(10), 'user_id' => $user->id]);

            $ret['code'] = session('code');

            $status = Mail::send('mails.auth.forgetPassword', $ret, function($message) use ($email) {
                $message->to($email);
                $message->from('info@annepopova.com');
                $message->subject('Reset Password');
            });
            $data['status'] = "true";
        }else{
            $data['status'] = "false";
            $data['error'] = trans('front.forgotPass.error');
        }

        return $data;
    }

    public function confirmEmailCode(Request $request)
    {
        $validator = validator($request->all(), [
          'code' => 'required|in:'.session('code')
        ]);

        if ($validator->fails()) {
            $data['status'] = 'false';
            $data['error'] = $validator->errors()->all();
            return $data;
        }

        $data['status'] = 'true';
        return $data;
    }

    public function changePassword(Request $request)
    {
        $validator = validator($request->all(), [
            'password' => 'required|min:3',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->errors()->all()], 400);
        }

        $user = FrontUser::find(session('user_id'));

        if(count($user) > 0){
            $user->password = bcrypt($request->get('password'));
            $user->remember_token = $request->get('_token');
            $user->save();

            session()->forget('code');
            session()->forget('user_id');

            $data['status'] = "true";
        }else{
            $data['status'] = "false";
        }

        return $data;
    }
}
