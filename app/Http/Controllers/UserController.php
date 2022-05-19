<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public static function index()
    {
        $users = DB::table('businesses_users')->where('business_id', UserController::get(Auth::id())->business_id)->join('users', 'users.id', '=', 'businesses_users.user_id')->select('users.*', 'businesses_users.role', 'businesses_users.business_id')->get();
        return $users;
    }

    public static function get($id)
    {
        $user = DB::table('users')->where('users.id', $id)->join('businesses_users', 'users.id', '=', 'businesses_users.user_id')->select('users.*', 'businesses_users.role', 'businesses_users.business_id')->get()[0];
        return $user;
    }

    public function create(Request $request)
    {
        $user_id = DB::table('users')->insertGetId([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'created_at' => new DateTime(),
        ]);

        DB::table('businesses_users')->insert([
            'user_id' => $user_id,
            'business_id' => UserController::get(Auth::id())->business_id,
            'role' => $request->input('role'),
            'created_at' => new DateTime(),
        ]);

        return Redirect::back()->withInput();
    }

    public function update(Request $request)
    {
        if ($request->input('password') !== null) {
            DB::table('users')->where('id', $request->input('id'))->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'updated_at' => new DateTime(),
            ]);
        } else {
            DB::table('users')->where('id', $request->input('id'))->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'updated_at' => new DateTime(),
            ]);
        }

        if ($request->input('role') !== null) {
            DB::table('businesses_users')->where('user_id', $request->input('id'))->update([
                'role' => $request->input('role'),
                'updated_at' => new DateTime(),
            ]);
        }

        return Redirect::back()->withInput();
    }
}
