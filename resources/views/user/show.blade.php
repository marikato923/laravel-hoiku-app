@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 800px; margin: 0 auto;">
    <h2 class="text-center mb-4">会員情報</h2>
    <hr class="mb-4">

    <p><strong>氏名:</strong> {{ $user->last_name }} {{ $user->first_name }}</p>
    <p><strong>カナ:</strong> {{ $user->last_kana_name }} {{ $user->first_kana_name }}</p>
    <p><strong>メールアドレス:</strong> {{ $user->email }}</p>
    <p><strong>電話番号:</strong> {{ $user->phone_number }}</p>
    <p><strong>郵便番号:</strong> {{ $user->postal_code }}</p>
    <p><strong>住所:</strong> {{ $user->address }}</p>

    <div class="text-end mt-4">
        <a href="{{ route('user.edit') }}" class="btn btn-primary me-2">編集</a>
        <a href="{{ route('user.edit-password') }}" class="btn btn-warning">パスワード変更</a>
    </div>
</div>
@endsection
