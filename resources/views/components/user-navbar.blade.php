<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="logo navbar-brand" href="/">こどもログ</a>

        @if (Auth::check())
            <span class="navbar-text me-0">
                {{ Auth::user()->last_name }} {{ Auth::user()->first_name }} さん
            </span>
        @endif

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                @if (Auth::check())
                    {{-- ログインしている場合 --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.show', ['user' => Auth::user()->id]) }}">会員情報</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('attendance.show') }}">出席履歴</a>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">ログアウト</button>
                        </form>
                    </li>
                @else
                    {{-- ログインしていない場合 --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">ログイン</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">新規会員登録</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
