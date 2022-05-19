<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class MessagesController extends Controller
{
    public static function inbox()
    {
        return DB::table('messages')->where('recipient_id', Auth::id())->get();
    }

    public static function outbox()
    {
        return DB::table('messages')->where('author_id', Auth::id())->get();
    }

    public function create(Request $request)
    {
        DB::table('messages')->insert([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'author_id' => Auth::id(),
            'recipient_id' => $request->input('recipient-id'),
            'status' => 'sended',
            'created_at' => new DateTime(),
        ]);

        return Redirect::back()->withInput();
    }
}
