<?php

use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthenticateAdmin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('main');
    })->name('main');

    Route::get('/messages', function () {
        return redirect('/messages/inbox');
    });

    Route::post('/messages/send', [MessagesController::class, 'create']);

    Route::get('/messages/{type}', function ($type) {
        return view('messages', ['type' => $type]);
    });

    Route::get('/docs', function () {
        return redirect('/docs/all');
    });

    Route::post('/docs/create', [DocumentsController::class, 'create']);

    Route::post('/docs/update', [DocumentsController::class, 'update']);

    Route::get('/docs/{type}', function ($type) {
        return view('docs', ['type' => $type]);
    });

    Route::get('/users', function () {
        return view('users');
    })->middleware(AuthenticateAdmin::class);

    Route::post('/users', [UserController::class, 'create'])->middleware(AuthenticateAdmin::class);

    Route::post('/users/update', [UserController::class, 'update'])->middleware(AuthenticateAdmin::class);

    Route::get('/stats', function () {
        return view('stats');
    })->middleware(AuthenticateAdmin::class);

    Route::get('/profile', function () {
        return view('profile');
    });

    Route::get('/sign-out', [LoginController::class, 'exit']);
});

Route::middleware(['guest'])->group(function () {

    Route::get('/login', function () {
        return view('login');
    })->name('login');

    Route::post('/login', [LoginController::class, 'authenticate']);

    Route::get('/sign-up', function () {
        return view('signup');
    });

    Route::post('/sign-up', [LoginController::class, 'create']);
});
