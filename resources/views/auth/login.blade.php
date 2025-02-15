@extends('layouts.app')

{{-- グローバルのフラッシュメッセージを無効化 --}}
@section('override-flash-messages', true)

@section('content')
<div class="container auth-page">
    <div style="width: 100%; max-width: 400px; margin: 40px auto;">
        <h1 class="text-center mb-4">ログイン</h1>
        <hr class="mb-4">

        {{-- ログイン画面専用のフラッシュメッセージ --}}
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group mb-3">
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="メールアドレス" autofocus>
            </div>

            <div class="form-group mb-3">
                <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password" placeholder="パスワード">
            </div>

            <div class="form-group mb-3 text-center">
                <div class="form-check d-inline-block">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label ms-2" for="remember">
                        次回から自動的にログインする
                    </label>
                </div>
            </div>

            <div class="form-group mb-4">
                <button type="submit" class="btn text-white shadow-sm kodomolog-btn" style="width: 100%;">ログイン</button>
            </div>
        </form>

        <hr class="my-4">

        <div class="text-center mb-3">
            <a href="{{ route('password.request') }}">パスワードをお忘れの方はこちら</a>
        </div>

        <div class="text-center">
            <a href="{{ route('register') }}">新規会員登録はこちら</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const flashMessages = document.querySelectorAll(".alert");
        
        flashMessages.forEach(function (message) {
            setTimeout(function () {
                message.style.transition = "opacity 1s ease-out, transform 1s ease-out, margin-bottom 0.5s ease-out";
                message.style.opacity = "0";
                message.style.transform = "translateY(-10px)";
                message.style.marginBottom = "0px"; 

                setTimeout(function () {
                    message.style.display = "none"; 
                }, 1000); 
            }, 1000); 
        });
    });
</script>
@endpush