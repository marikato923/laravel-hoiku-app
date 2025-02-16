@extends('layouts.app')

{{-- グローバルのフラッシュメッセージを無効化 --}}
@section('override-flash-messages', true)

@section('content')
<div class="container auth-page">
    <div style="width: 100%; max-width: 400px; margin: 40px auto;">
        <h2 class="text-center mb-3">新規会員登録</h2>
        <hr class="mb-4">

            {{-- フラッシュメッセージ --}}
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

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group mb-3">
                <label for="last_name">姓</label>
                <input type="text" id="last_name" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required placeholder="例: 山田">
                @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="first_name">名</label>
                <input type="text" id="first_name" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required placeholder="例: 太郎">
                @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="last_kana_name">セイ</label>
                <input type="text" id="last_kana_name" class="form-control @error('last_kana_name') is-invalid @enderror" name="last_kana_name" value="{{ old('last_kana_name') }}" required placeholder="例: ヤマダ">
                @error('last_kana_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="first_kana_name">メイ</label>
                <input type="text" id="first_kana_name" class="form-control @error('first_kana_name') is-invalid @enderror" name="first_kana_name" value="{{ old('first_kana_name') }}" required placeholder="例: タロウ">
                @error('first_kana_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="例: example@example.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password">パスワード</label>
                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="英数字および記号(半角)">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password_confirmation">パスワード確認</label>
                <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" required placeholder="確認用パスワード">
            </div>

            <div class="form-group mb-3">
                <label for="phone_number">電話番号</label>
                <input type="text" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}" placeholder="例: 0000000">
                @error('phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="postal_code">郵便番号</label>
                <input type="text" id="postal_code" class="form-control @error('postal_code') is-invalid @enderror" name="postal_code" value="{{ old('postal_code') }}" placeholder="例: 00000000000">
                @error('postal_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="address">住所</label>
                <input type="text" id="address" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" placeholder="住所を入力">
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group d-flex justify-content-center mb-4">
                <button type="submit" class="btn text-white shadow-sm w-100 kodomolog-btn">登録</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const flashMessageContainer = document.querySelector(".flash-message-container");

    if (flashMessageContainer && flashMessageContainer.innerText.trim() === "") {
        flashMessageContainer.style.minHeight = "0";
        flashMessageContainer.style.marginBottom = "0";
    } else {
        setTimeout(function () {
            flashMessageContainer.classList.add("fade-out");

            setTimeout(function () {
                flashMessageContainer.remove(); 
            }, 1000);
        }, 5000);
    }
});
</script>
@endpush
