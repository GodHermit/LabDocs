@inject('users', 'App\Http\Controllers\UserController')
@inject('documents', 'App\Http\Controllers\DocumentsController')
@extends('layouts.app')

@section('content')
    <div class="container mt-3 mb-3">
        <div class="row">
            <div class="col">
                <h1 class="headline">Статистика</h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card text-bg-dark">
                    <div class="card-header">
                        <h5 class="card-title">Користувачів</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Всього: {{count($users::index())}}</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-bg-dark">
                    <div class="card-header">
                        <h5 class="card-title">Документів</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">Всього: {{count($documents::index())}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
