@extends('layouts.app')

{{-- グローバルのフラッシュメッセージを無効化 --}}
@section('override-flash-messages', true)

@section('content')
<div class="container auth-page">
    <div style="width: 100%; max-width: 400px; margin: 40px auto;">
        <h1 class="text-center mb-4">パスワード再設定</h1>
        <hr class="mb-4">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="form-group mb-3">
                <label for="email" class="fw-bold">メールアドレス</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $request->email) }}" required autocomplete="email" autofocus>
            </div>

            <div class="form-group mb-3">
                <label for="password" class="fw-bold">新しいパスワード</label>
                <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password">
            </div>

            <div class="form-group mb-3">
                <label for="password-confirm" class="fw-bold">新しいパスワード（確認用）</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="form-group d-flex justify-content-center mb-4">
                <button type="submit" class="btn text-white shadow-sm w-100 kodomolog-btn">再設定</button>
            </div>
        </form>
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
            }, 3000); 
        });
    });
</script>
@endpush
