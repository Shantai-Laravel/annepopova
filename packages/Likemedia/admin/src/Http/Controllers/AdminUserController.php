<?php

namespace Admin\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\User;


class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('admin::admin.user.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::find($id);

        return view('admin::admin.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        User::where('id', $id)->update([
            'email' => $request->get('email'),
            'login' => $request->get('username'),
            'name' => $request->get('username'),
        ]);

        if ($request->get('password')) {
            $validator = $this->validate($request, [
                'password' => 'required|min:4',
                'password-again' => 'required|same:password',
            ]);

            User::where('id', $id)->update([
                'password' => Hash::make($request->get('password')),

            ]);
        }
        return redirect()->back();
    }

    public function create()
    {
        return view('admin::admin.user.create');
    }

    public function store(Request $request)
    {
        $validator = $this->validate($request, [
            'username' => 'required|min:4',
            'password' => 'required|min:4',
            'password-again' => 'required|same:password',
        ]);

        User::create([
            'email' => $request->get('email'),
            'login' => $request->get('username'),
            'name' => $request->get('username'),
            'password' => Hash::make($request->get('password')),
        ]);

        return redirect('back/users');
    }

    public function destroy($id)
    {
        User::where('id', $id)->delete();

        return redirect()->back();
    }
}
