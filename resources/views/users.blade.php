@inject('users', 'App\Http\Controllers\UserController')
@inject('auth', 'Illuminate\Support\Facades\Auth')

@extends('layouts.app')

@section('content')
    <div class="container mt-3 mb-3">
        <div class="row">
            <div class="col">
                <h1 class="headline">Користувачі</h1>
            </div>
        </div>
        <div class="row mt-3 mb-3">
            <div class="col d-flex justify-content-end">
                <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#createUser">Додати
                    користувача</button>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ПІБ</th>
                            <th>Пошта</th>
                            <th>Роль</th>
                            <th>Дія</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users::index() as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td><a href="mailto:{{ $user->email }}" class="link-light" target="_blank">{{ $user->email }}</a></td>
                                <td>
                                    @if ($user->role == 'admin')
                                        Адміністратор
                                    @elseif ($user->role == 'manager')
                                        Менеджер
                                    @elseif ($user->role == 'clerk')
                                        Діловод
                                    @elseif ($user->role == 'user')
                                        Користувач       
                                    @endif
                                </td>
                                <td>
                                    @if ($users::get($auth::id())->role == 'admin')
                                        <button class="btn btn-sm btn-link link-light user-edit" data-bs-toggle="modal"
                                            data-bs-target="#editUser" data-user="{{json_encode($user)}}">Ред.</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="createUser" tabindex="-1" aria-labelledby="createUserLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/users" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createUserLabel">Створити користувача</h5>
                        <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">ПІБ:</label>
                            <input type="name" class="form-control" id="name" name="name" placeholder=""
                                autocomplete="name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Електронна адреса:</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="example@example.com" autocomplete="email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Пароль:</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="••••••••">
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Роль:</label>
                            <select name="role" id="role" class="form-select">
                                <option value="admin">Адміністратор</option>
                                <option value="manager">Менеджер</option>
                                <option value="clerk">Діловод</option>
                                <option value="user" selected>Користувач</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
                        <button type="submit" class="btn btn-dark">Зберегти</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editUser" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/users/update" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserLabel">Редагувати користувача</h5>
                        <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="id" class="form-label">ID:</label>
                            <input type="text" class="form-control" id="edit-id" name="id" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">ПІБ:</label>
                            <input type="text" class="form-control" id="edit-name" name="name" placeholder=""
                                autocomplete="name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Електронна адреса:</label>
                            <input type="email" class="form-control" id="edit-email" name="email"
                                placeholder="example@example.com" autocomplete="email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Пароль:</label>
                            <input type="password" class="form-control" id="edit-password" name="password"
                                placeholder="••••••••">
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Роль:</label>
                            <select name="role" id="edit-role" class="form-select">
                                <option value="admin">Адміністратор</option>
                                <option value="manager">Менеджер</option>
                                <option value="clerk">Діловод</option>
                                <option value="user" selected>Користувач</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
                        <button type="submit" class="btn btn-dark">Зберегти</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.querySelectorAll('.user-edit').forEach(el => {
            el.addEventListener('click', () => {
                var user_data = JSON.parse(el.dataset.user);
                document.querySelector('#edit-id').value = user_data.id;
                document.querySelector('#edit-name').value = user_data.name;
                document.querySelector('#edit-email').value = user_data.email;
                document.querySelector('#edit-role').value = user_data.role;
            });
        });
    </script>
@endsection
