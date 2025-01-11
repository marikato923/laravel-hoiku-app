@extends('layouts.app')

@section('content')
<div class="container">
    <h2>パスワード変更</h2>
    <form action="{{ route('user.update-password') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="current_password" class="form-label">現在のパスワード</label>
            <input type="password" name="current_password" id="current_password" class="form-control">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">新しいパスワード</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">新しいパスワード（確認）</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">パスワードを変更</button>
    </form>
</div>
@endsection