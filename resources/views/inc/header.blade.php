<header>
    <nav class="navbar navbar-dark navbar-expand-lg bg-dark light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">LabDocs</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navBarMessage" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Повідомлення</a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-describedby="navBarMessage">
                            <li><a href="/messages/inbox" class="dropdown-item">Вхідні</a></li>
                            <li><a href="/messages/outbox" class="dropdown-item">Вихідні</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navBarDocs" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Документи</a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-describedby="navBarDocs">
                            <li><a href="/docs/all" class="dropdown-item">Усі</a></li>
                            <li>
                                <hr class="dropdown-divider" role="separator">
                            </li>
                            <li><a href="/docs/draft" class="dropdown-item">Проєкти документів</a></li>
                            <li><a href="/docs/outbox" class="dropdown-item">Вихідні</a></li>
                            <li><a href="/docs/inbox" class="dropdown-item">Вхідні</a></li>
                        </ul>
                    </li>
                    @if ($users::get($auth::id())->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="/users">Користувачі</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/stats">Статистика</a>
                        </li>
                    @endif
                </ul>
                <div class="dropdown d-flex ms-auto">
                    <a class="link-light dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $users::get($auth::id())->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-dark"
                        aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="/profile">Профіль</a>
                        <a class="dropdown-item" href="/sign-out">Вийти</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
