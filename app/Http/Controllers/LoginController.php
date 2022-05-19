<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Пошта або пароль неправильні',
        ])->onlyInput('email');
    }

    public function create(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', Password::min(8)],
        ]);

        $user_id = DB::table('users')->insertGetId([
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password']),
            'created_at' => new DateTime(),
        ]);

        $business_id = DB::table('businesses')->insertGetId([
            'title' => '',
            'author_id' => $user_id,
            'created_at' => new DateTime(),
        ]);

        DB::table('businesses_users')->insert([
            'user_id' => $user_id,
            'business_id' => $business_id,
            'role' => 'admin',
            'created_at' => new DateTime(),
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Пошта або пароль неправильні',
        ])->onlyInput('email');
    }

    public function exit()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function get()
    {
        UserController::get(Auth::id());
    }
}
