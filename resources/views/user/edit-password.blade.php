@extends('layouts.app')

@section('breadcrumbs')
<nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">ホーム</a></li>
        <li class="breadcrumb-item"><a href="{{ route('user.show') }}">会員情報</a></li>
        <li class="breadcrumb-item active" aria-current="page">パスワード変更</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4 kodomolog-app-container">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-7 col-md-8 col-sm-10">
            <h2 class="text-center mb-3">パスワード変更</h2>
            <hr class="mb-4">

            <form action="{{ route('user.update-password') }}" method="POST" style="max-width: 600px; width: 100%;">
                @csrf
                <div class="mb-3">
                    <label for="current_password" class="form-label">現在のパスワード</label>
                    <input type="password" name="current_password" id="current_password" 
                        class="form-control @error('current_password') is-invalid @enderror"
                        style="max-width: 400px;">
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">新しいパスワード</label>
                    <input type="password" name="password" id="password" 
                        class="form-control @error('password') is-invalid @enderror"
                        style="max-width: 400px;">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">新しいパスワード（確認）</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                        class="form-control" style="max-width: 400px;">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn register-btn shadow-none px-4 mt-3">更新</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection