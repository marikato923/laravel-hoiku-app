@extends('layouts.admin')

{{-- グローバルのフラッシュメッセージを無効化 --}}
@section('override-flash-messages', true)

@section('content')
<div class="container auth-page">
    <div style="width: 100%; max-width: 400px; margin: 40px auto;">
        <div class="auth-wrapper">
            <h1 class="mb-4 text-center">ログイン</h1>

            <hr class="mb-4">

            {{-- ログイン画面専用のフラッシュメッセージ --}}
            <div class="flash-message-container">
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

            {{-- メッセージがない時も高さを維持するための空タグ --}}
            @if (!session('success') && !$errors->any())
                <span class="d-block">&nbsp;</span>
            @endif
            </div>

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                <div class="form-group mb-3">
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="メールアドレス" autofocus>
                </div>

                <div class="form-group mb-3">
                    <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password" placeholder="パスワード">
                </div>

                <div class="form-group mb-3 text-center">
                    <div class="form-check d-inline-block">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label ms-2" for="remember">
                            次回から自動的にログインする
                        </label>
                    </div>
                </div>

                <div class="form-group d-flex justify-content-center mb-4">
                    <button type="submit" class="btn text-white shadow-sm w-100 kodomolog-btn">ログイン</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const flashMessageContainer = document.querySelector(".flash-message-container");

        if (flashMessageContainer) {
            setTimeout(function () {
                flashMessageContainer.classList.add("fade-out");

                setTimeout(function () {
                    flashMessageContainer.remove(); 
                }, 1000);
            }, 2000);
        }
    });
</script>
@endpush
