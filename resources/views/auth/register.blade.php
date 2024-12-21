@extends('layouts.app')

@section('content')
<div class="container">
    <h2>会員登録</h2>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- 名前 -->
        <div class="form-group">
            <label for="name">名前</label>
            <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- カナ -->
        <div class="form-group">
            <label for="kana">カナ</label>
            <input type="text" id="kana" class="form-control @error('kana') is-invalid @enderror" name="kana" value="{{ old('kana') }}" required>
            @error('kana')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- メールアドレス -->
        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- パスワード -->
        <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- パスワード確認 -->
        <div class="form-group">
            <label for="password_confirmation">パスワード確認</label>
            <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" required>
        </div>

        <!-- 役割 -->
        <div class="form-group">
            <label for="role">役割</label>
            <select id="role" class="form-control" name="role">
                <option value="parent" {{ old('role', 'parent') == 'parent' ? 'selected' : '' }}>保護者</option>
                <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>スタッフ</option>
            </select>
        </div>

        <!-- 電話番号 -->
        <div class="form-group">
            <label for="phone_number">電話番号</label>
            <input type="text" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number') }}">
            @error('phone_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- 郵便番号 -->
        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" class="form-control @error('postal_code') is-invalid @enderror" name="postal_code" value="{{ old('postal_code') }}">
            @error('postal_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- 住所 -->
        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" id="address" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}">
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- 子供の人数 -->
        <div class="form-group">
            <label for="child_count">子供の人数</label>
            <input type="number" id="child_count" class="form-control @error('child_count') is-invalid @enderror" name="child_count" value="{{ old('child_count') }}" min="0">
            @error('child_count')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- 登録ボタン -->
        <button type="submit" class="btn btn-primary">登録</button>
    </form>
</div>
@endsection