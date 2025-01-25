@extends('layouts.app')

@section('content')
<div class="container py-4 kodomolog-app-container">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-7 col-md-8 col-sm-8 mx-auto">
            <div class="wrapper w-100" style="max-width: 80%;">

                <h2 class="text-center mb-3">会員情報編集</h2>
                <hr class="mb-4">

                <form action="{{ route('user.update') }}" method="POST" class="mx-auto" style="max-width: 600px;">
                    @csrf
                    @method('PUT')
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="last_name">姓</label>
                                <input type="text" id="last_name" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', auth()->user()->last_name) }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="first_name">名</label>
                                <input type="text" id="first_name" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', auth()->user()->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="last_kana_name">セイ</label>
                                <input type="text" id="last_kana_name" class="form-control @error('last_kana_name') is-invalid @enderror" name="last_kana_name" value="{{ old('last_kana_name', auth()->user()->last_kana_name) }}" required>
                                @error('last_kana_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="first_kana_name">メイ</label>
                                <input type="text" id="first_kana_name" class="form-control @error('first_kana_name') is-invalid @enderror" name="first_kana_name" value="{{ old('first_kana_name', auth()->user()->first_kana_name) }}" required>
                                @error('first_kana_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="email">メールアドレス</label>
                                <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="phone_number">電話番号</label>
                                <input type="text" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="{{ old('phone_number', auth()->user()->phone_number) }}">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="postal_code">郵便番号</label>
                                <input type="text" id="postal_code" class="form-control @error('postal_code') is-invalid @enderror" name="postal_code" value="{{ old('postal_code', auth()->user()->postal_code) }}">
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="address">住所</label>
                                <input type="text" id="address" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address', auth()->user()->address) }}">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
</div>
@endsection
