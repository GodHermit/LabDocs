@inject('users', 'App\Http\Controllers\UserController')
@inject('auth', 'Illuminate\Support\Facades\Auth')
@extends('layouts.app')

@php
$user = $users::get($auth::id());
@endphp

@section('content')
    <div class="container mt-3 mb-3">
        <div class="row">
            <div class="col">
                <h1 class="headline">Профіль</h1>
            </div>
        </div>
        <form action="/users/update" method="POST">
            <div class="row">
                <div class="col">
                    @csrf
                    <div class="col mb-3">
                        <label for="id" class="form-label">ID:</label>
                        <input type="text" class="form-control" id="edit-id" name="id" value="{{ $user->id }}"
                            readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label for="name" class="form-label">ПІБ:</label>
                    <input type="text" class="form-control" id="edit-name" name="name" placeholder="" autocomplete="name"
                        value="{{ $user->name }}">
                </div>
                <div class="col mb-3">
                    <label for="email" class="form-label">Електронна адреса:</label>
                    <input type="email" class="form-control" id="edit-email" name="email"
                        placeholder="example@example.com" autocomplete="email" value="{{ $user->email }}">
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label for="password" class="form-label">Пароль:</label>
                    <input type="password" class="form-control" id="edit-password" name="password" placeholder="••••••••">
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label for="role" class="form-label">Роль:</label>
                    <select name="role" id="edit-role" class="form-select">
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : null }}>Адміністратор</option>
                        <option value="manager" {{ $user->role == 'manager' ? 'selected' : null }}>Менеджер</option>
                        <option value="clerk" {{ $user->role == 'clerk' ? 'selected' : null }}>Діловод</option>
                        <option value="user" {{ $user->role == 'user' ? 'selected' : null }}>Користувач</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col d-flex">
                    <button class="btn btn-dark ms-auto">Зберегти</button>
                </div>
            </div>
        </form>
    </div>
@endsection
