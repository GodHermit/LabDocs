@inject('users', 'App\Http\Controllers\UserController')
@inject('auth', 'Illuminate\Support\Facades\Auth')
@extends('layouts.app')

@section('content')
    <div class="container mt-3 mb-3">
        <div class="row">
            <div class="col">
                <h1 class="headline">Головна</h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <ul class="list-group">
                    <li class="list-group-item">
                        <a href="/messages/inbox">Повідомлення</a>
                    </li>
                    <li class="list-group-item">
                        <a href="/docs/all">Документи</a>
                    </li>
                    @if ($users::get($auth::id())->role == 'admin')
                        <li class="list-group-item">
                            <a href="/users">Користувачі</a>
                        </li>
                        <li class="list-group-item">
                            <a href="/stats">Статистика</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endsection
