@extends('layouts.app')

@section('content')
<div class="container py-4 kodomolog-app-container" style="min-height: 100vh;">
    <div class="col-xl-6 col-lg-7 col-md-8 col-sm-10 mx-auto">
      <div class="wrapper w-100" style="max-width: 80%;">

            <h2 class="text-center mb-3">パスワード変更</h2>
            <hr class="mb-4">

            <form action="{{ route('user.update-password') }}" method="POST" style="max-width: 600px; width: 100%;">
                @csrf
                <div class="mb-3">
                    <label for="current_password" class="form-label">現在のパスワード</label>
                    <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror">
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">新しいパスワード</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">新しいパスワード（確認）</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn register-btn shadow-none px-4 mt-3">更新</button>
                </div>
            </form>

      </div>
    </div>
  </div>
</div>
@endsection
