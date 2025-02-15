@extends('layouts.app')

@section('breadcrumbs')
<nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">ホーム</a></li>
        <li class="breadcrumb-item"><a href="{{ route('user.show') }}">会員情報</a></li>
        <li class="breadcrumb-item active" aria-current="page">会員情報 編集</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4 kodomolog-app-container">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-7 col-md-8 col-sm-8 mx-auto">
            <h2 class="text-center mb-3">会員情報編集</h2>
            <hr class="mb-4">

            <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data" style="max-width: 600px; margin: 0 auto;">
                @csrf
                @method('PUT')

                <div class="card border-0">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="last_name">姓</label>
                            <input type="text" id="last_name" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', auth()->user()->last_name) }}" required style="max-width: 400px;">
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="first_name">名</label>
                            <input type="text" id="first_name" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', auth()->user()->first_name) }}" required style="max-width: 400px;">
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="last_kana_name">セイ</label>
                            <input type="text" id="last_kana_name" class="form-control @error('last_kana_name') is-invalid @enderror" name="last_kana_name" value="{{ old('last_kana_name', auth()->user()->last_kana_name) }}" required style="max-width: 400px;">
                            @error('last_kana_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="first_kana_name">メイ</label>
                            <input type="text" id="first_kana_name" class="form-control @error('first_kana_name') is-invalid @enderror" name="first_kana_name" value="{{ old('first_kana_name', auth()->user()->first_kana_name) }}" required style="max-width: 400px;">
                            @error('first_kana_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">メールアドレス</label>
                            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', auth()->user()->email) }}" required style="max-width: 400px;">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone_number">電話番号</label>
                            <input type="text" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number', auth()->user()->phone_number) }}" style="max-width: 400px;">
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="address">住所</label>
                            <input type="text" id="address" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address', auth()->user()->address) }}" style="max-width: 400px;">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 通知の受信設定 -->
                        <div class="form-group mb-3">
                            <label for="notification_preference">通知</label>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="notification_preference" name="notification_preference"
                                    {{ auth()->user()->notification_preference ? 'checked' : '' }}>
                                <label class="form-check-label" for="notification_preference">
                                    お迎え通知メールを受け取る
                                </label>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn register-btn shadow-none px-4 mt-3">更新</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
