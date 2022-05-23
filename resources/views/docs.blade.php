@inject('documents', 'App\Http\Controllers\DocumentsController')
@inject('users', 'App\Http\Controllers\UserController')
@inject('auth', 'Illuminate\Support\Facades\Auth')
@extends('layouts.app')

@section('content')
    <div class="container mt-3 mb-3">
        <div class="row">
            <div class="col">
                <h1 class="headline">
                    @if (Request::route('type') == 'all')
                        Усі документи
                    @elseif (Request::route('type') == 'projects')
                        Проєкти документів
                    @elseif (Request::route('type') == 'outbox')
                        Вихідні документи
                    @elseif (Request::route('type') == 'inbox')
                        Вхідні документи
                    @endif
                </h1>
            </div>
        </div>
        @if ($users::get($auth::id())->role !== 'user')
            <div class="row mt-3 mb-3">
                <div class="col d-flex justify-content-end">
                    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#createDocumentModal">Створити
                        документ</button>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col">
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
                @endif
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Заголовок</th>
                            @if (Request::route('type') == 'all')
                                <th>Статус</th>
                            @endif
                            <th>Дата</th>
                            <th>Дія</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (Request::route('type') == 'all')
                            @foreach ($documents::index() as $document)
                                <tr>
                                    <td>{{ $document->id }}</td>
                                    <td>{{ $document->title }}</td>
                                    <td>
                                        @if ($document->status == 'draft')
                                            Проєкт документа
                                        @else
                                            Готовий документ
                                        @endif
                                    </td>
                                    <td>{{ $document->created_at }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-link link-light document-open" data-bs-toggle="modal"
                                            data-bs-target="#documentModal"
                                            data-document="{{ json_encode($document) }}">Відкрити</button>
                                        @if ($users::get($auth::id())->role !== 'user' && $document->status == 'draft')
                                            <button class="btn btn-sm btn-link link-light document-edit"
                                                data-bs-toggle="modal" data-bs-target="#editDocumentModal"
                                                data-document="{{ json_encode($document) }}">Ред.</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @elseif (Request::route('type') == 'draft')
                            @foreach ($documents::drafts() as $document)
                                <tr>
                                    <td>{{ $document->id }}</td>
                                    <td>{{ $document->title }}</td>
                                    <td>{{ $document->created_at }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-link link-light document-open" data-bs-toggle="modal"
                                            data-bs-target="#documentModal"
                                            data-document="{{ json_encode($document) }}">Відкрити</button>
                                        @if ($users::get($auth::id())->role !== 'user' && $document->status == 'draft')
                                            <button class="btn btn-sm btn-link link-light document-edit"
                                                data-bs-toggle="modal" data-bs-target="#editDocumentModal"
                                                data-document="{{ json_encode($document) }}">Ред.</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @elseif (Request::route('type') == 'inbox')
                            @foreach ($documents::inbox() as $document)
                                <tr>
                                    <td>{{ $document->id }}</td>
                                    <td>{{ $document->title }}</td>
                                    <td>{{ $document->created_at }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-link link-light document-open" data-bs-toggle="modal"
                                            data-bs-target="#documentModal"
                                            data-document="{{ json_encode($document) }}">Відкрити</button>
                                        @if ($users::get($auth::id())->role !== 'user' && $document->status == 'draft')
                                            <button class="btn btn-sm btn-link link-light document-edit"
                                                data-bs-toggle="modal" data-bs-target="#editDocumentModal"
                                                data-document="{{ json_encode($document) }}">Ред.</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @elseif (Request::route('type') == 'outbox')
                            @foreach ($documents::outbox() as $document)
                                <tr>
                                    <td>{{ $document->id }}</td>
                                    <td>{{ $document->title }}</td>
                                    <td>{{ $document->created_at }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-link link-light document-open" data-bs-toggle="modal"
                                            data-bs-target="#documentModal"
                                            data-document="{{ json_encode($document) }}">Відкрити</button>
                                        @if ($users::get($auth::id())->role !== 'user' && $document->status == 'draft')
                                            <button class="btn btn-sm btn-link link-light document-edit"
                                                data-bs-toggle="modal" data-bs-target="#editDocumentModal"
                                                data-document="{{ json_encode($document) }}">Ред.</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentModalLabel"></h5>
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
    <div class="modal fade" id="createDocumentModal" tabindex="-1" aria-labelledby="createDocumentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/docs/create" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createDocumentModalLabel">Написати повідомлення</h5>
                        <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="text" class="form-label">Заголовок:</label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                        <div class="mb-3">
                            <label for="text" class="form-label">Вміст:</label>
                            <textarea name="content" id="content" rows="4" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="text" class="form-label">Отримувач:</label>
                            <select name="recipient-id[]" id="recipient-id" class="form-select" multiple>
                                @foreach ($users::index() as $user)
                                    @if ($user->id !== $auth::id())
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="text" class="form-label">Статус:</label>
                            <select name="status" id="status" class="form-select">
                                <option value="doc">Документ</option>
                                <option value="draft" selected>Проєкт документа</option>
                            </select>
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
    <div class="modal fade" id="editDocumentModal" tabindex="-1" aria-labelledby="editDocumentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/docs/update" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDocumentModalLabel">Написати повідомлення</h5>
                        <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="text" class="form-label">ID:</label>
                            <input type="text" class="form-control" id="edit-id" name="id" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="text" class="form-label">Заголовок:</label>
                            <input type="text" class="form-control" id="edit-title" name="title">
                        </div>
                        <div class="mb-3">
                            <label for="text" class="form-label">Вміст:</label>
                            <textarea name="content" id="edit-content" rows="4" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="text" class="form-label">Отримувач:</label>
                            <select name="recipient-id[]" id="edit-recipient-id" class="form-select" multiple>
                                @foreach ($users::index() as $user)
                                    @if ($user->id !== $auth::id())
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="text" class="form-label">Статус:</label>
                            <select name="status" id="edit-status" class="form-select">
                                <option value="doc">Документ</option>
                                <option value="draft" selected>Проєкт документа</option>
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
        document.querySelectorAll('.document-open').forEach(el => {
            el.addEventListener('click', () => {
                var document_data = JSON.parse(el.dataset.document);
                document.querySelector('#documentModal .modal-title').innerText = document_data.title;
                document.querySelector('#documentModal .modal-body').innerHTML = document_data.content;
                document.querySelector('#documentModal .modal-body').innerHTML += '<div><b>Створено: </b>' +
                    document_data.created_at + '</div>';
            });
        });
        document.querySelectorAll('.document-edit').forEach(el => {
            el.addEventListener('click', () => {
                var document_data = JSON.parse(el.dataset.document);
                document.querySelector('#edit-id').value = document_data.id;
                document.querySelector('#edit-title').value = document_data.title;
                document.querySelector('#edit-content').value = document_data.content;
                Array.prototype.forEach.call(document.querySelector('#edit-recipient-id').options,
                    option => {
                        if (document_data.recipients.includes(parseInt(option.value))) {
                            option.selected = true;
                        }
                    });
                document.querySelector('#edit-status').value = document_data.status;
            });
        });
    </script>
@endsection
