<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Cookie;

class CustomAuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('post'))
            return $this->checkLogin();

        if (Auth::user()) {
            return redirect()->route('back');
        }

        return view('admin::auth.login', get_defined_vars());
    }

    public function checkLogin(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'login' => 'required|min:3',
            'password' => 'required|min:4',
        ]);

        if (Auth::attempt(array('login' => $request->get('login'), 'password' => $request->get('password')))){
            return redirect()->route('back');
        }
        return redirect()->back();
    }

    public function register() { }

    public function checkRegister() { }

    public function logout()
    {
        Auth::logout();
        return redirect('/'.$this->lang.'/homewar');
    }
}
