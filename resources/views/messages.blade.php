@inject('messages', 'App\Http\Controllers\MessagesController')
@inject('users', 'App\Http\Controllers\UserController')
@inject('auth', 'Illuminate\Support\Facades\Auth')
@extends('layouts.app')

@section('content')
    <div class="container mt-3 mb-3">
        <div class="row">
            <div class="col">
                @if (Request::route('type') == 'inbox')
                    <h1 class="headline">Вхідні повідомлення</h1>
                @elseif (Request::route('type') == 'outbox')
                    <h1 class="headline">Вихідні повідомлення</h1>
                @endif
            </div>
        </div>
        <div class="row mt-3 mb-3">
            <div class="col d-flex justify-content-end">
                <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#createMessageModal">Написати
                    повідомлення</button>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            @if (Request::route('type') == 'inbox')
                                <th>Від</th>
                            @elseif (Request::route('type') == 'outbox')
                                <th>До</th>
                            @elseif (Request::route('type') == 'deleted')
                                <th>Від</th>
                                <th>До</th>
                            @endif
                            <th>Заголовок</th>
                            <th>Дата</th>
                            <th>Дія</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (Request::route('type') == 'inbox')
                            @foreach ($messages::inbox() as $message)
                                <tr>
                                    <td>{{ $message->id }}</td>
                                    <td>{{ $users::get($message->author_id)->name }}</td>
                                    <td>{{ $message->title }}</td>
                                    <td>{{ $message->created_at }}</td>
                                    <td><button class="btn btn-sm btn-link link-light message-open" data-bs-toggle="modal"
                                            data-bs-target="#messageModal"
                                            data-message="{{ json_encode($message) }}">Відкрити</button></td>
                                </tr>
                            @endforeach
                        @elseif (Request::route('type') == 'outbox')
                            @foreach ($messages::outbox() as $message)
                                <tr>
                                    <td>{{ $message->id }}</td>
                                    <td>{{ $users::get($message->recipient_id)->name }}</td>
                                    <td>{{ $message->title }}</td>
                                    <td>{{ $message->created_at }}</td>
                                    <td><button class="btn btn-sm btn-link link-light message-open" data-bs-toggle="modal"
                                            data-bs-target="#messageModal"
                                            data-message="{{ json_encode($message) }}">Відкрити</button></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="createMessageModal" tabindex="-1" aria-labelledby="createMessageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/messages/send" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createMessageModalLabel">Написати повідомлення</h5>
                        <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="text" class="form-label">Заголовок:</label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                        <div class="mb-3">
                            <label for="text" class="form-label">Отримувач:</label>
                            <select name="recipient-id" id="recipient-id" class="form-select">
                                @foreach ($users::index() as $user)
                                    @if ($user->id !== $auth::id())
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="text" class="form-label">Вміст:</label>
                            <textarea name="content" id="content" rows="4" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
                        <button type="submit" class="btn btn-dark">Відправити</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.querySelectorAll('.message-open').forEach(el => {
            el.addEventListener('click', () => {
                var message_data = JSON.parse(el.dataset.message);
                document.querySelector('#messageModal .modal-title').innerText = message_data.title;
                document.querySelector('#messageModal .modal-body').innerHTML = message_data.content;
            });
        });
    </script>
@endsection
