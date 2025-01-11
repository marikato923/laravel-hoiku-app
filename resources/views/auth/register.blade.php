@extends('layouts.app')

@section('content')
<div class="container">
    <h2>会員登録</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name">姓</label>
            <input type="text" id="last_name" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required>
            @error('last_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="first_name">名</label>
            <input type="text" id="first_name" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required>
            @error('first_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="last_kana_name">セイ</label>
            <input type="text" id="last_kana_name" class="form-control @error('last_kana_name') is-invalid @enderror" name="last_kana_name" value="{{ old('last_kana_name') }}" required>
            @error('last_kana_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="first_kana_name">メイ</label>
            <input type="text" id="first_kana_name" class="form-control @error('first_kana_name') is-invalid @enderror" name="first_kana_name" value="{{ old('first_kana_name') }}" required>
            @error('first_kana_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">パスワード確認</label>
            <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" required>
        </div>

        <div class="form-group">
            <label for="phone_number">電話番号</label>
            <input type="text" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}">
            @error('phone_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" class="form-control @error('postal_code') is-invalid @enderror" name="postal_code" value="{{ old('postal_code') }}">
            @error('postal_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" id="address" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}">
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn register-btn">登録</button>
    </form>
</div>
@endsection