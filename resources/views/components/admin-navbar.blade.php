<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <!-- ロゴとタイトル -->
        <div class="d-flex align-items-center">
            <a class="logo navbar-brand me-3" href="{{ route('admin.home') }}">こどもログ</a>
            <span class="admin-page-title mt-2">for admins</span>
        </div>

        @auth('admin') <!-- 管理者がログイン中の場合のみ表示 -->
        <!-- ハンバーガーメニュー -->
        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        @endauth

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @auth('admin') <!-- 管理者がログイン中の場合のみ表示 -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">会員一覧</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.attendance.index') }}">出席状況一覧</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.children.index') }}">園児一覧</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.classrooms.index') }}">クラス一覧</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.kindergarten.index') }}">保育園概要</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.terms.index') }}">利用規約</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link" style="color: #000; text-decoration: none;">ログアウト</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
