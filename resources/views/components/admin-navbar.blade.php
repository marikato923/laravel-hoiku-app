
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ route('admin.home') }}">管理者ページ</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.users.index') }}">会員一覧</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">登園状況一覧</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.children.index') }}">園児一覧</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">緊急連絡先一覧</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.classrooms.index') }}">クラス一覧</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">保育園概要</a>

                <!-- ログイン中の管理者 -->
                {{-- <li class="nav-item"> --}}
                    {{-- <p>{{ $admin->role}}: {{ $admin->last_name }} {{ $admin->first_name }}</p> --}}
                <!-- ログアウトボタン -->
                <li class="nav-item">
                    <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link" style="color: #000; text-decoration: none;">ログアウト</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>