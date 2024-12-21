<nav class="bg-blue-500 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <span class="text-lg font-semibold">保育アプリ</span>
        <div class="flex items-center space-x-4">
            @if (Auth::check())
                <!-- ログインしている場合 -->
                <span class="text-white">{{ Auth::user()->name }}</span>
                <!-- ログアウトフォーム -->
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-white">ログアウト</button>
                </form>
            @else
                <!-- ログインしていない場合 -->
                <a href="{{ route('login') }}" class="text-white">ログイン</a>
                <a href="{{ route('register') }}" class="text-white">新規会員登録</a>
            @endif
        </div>
    </div>
</nav>