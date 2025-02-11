@extends('layouts.app')

@section('breadcrumbs')
<nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">ホーム</a></li>
        <li class="breadcrumb-item active" aria-current="page">会員情報</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container" style="max-width: 800px; margin: 0 auto;">
    <h2 class="text-center mb-4">会員情報</h2>
    <hr class="mb-4">

    <p class="mb-2"><strong>氏名:</strong> {{ $user->last_name }} {{ $user->first_name }}</p>
    <p class="mb-2"><strong>カナ:</strong> {{ $user->last_kana_name }} {{ $user->first_kana_name }}</p>
    <p class="mb-2"><strong>メールアドレス:</strong> {{ $user->email }}</p>
    <p class="mb-2"><strong>電話番号:</strong> {{ $user->phone_number }}</p>
    <p class="mb-2"><strong>郵便番号:</strong> {{ $user->postal_code }}</p>
    <p class="mb-2"><strong>住所:</strong> {{ $user->address }}</p>

    <div class="text-end mt-4">
        <a href="{{ route('user.edit') }}" class="btn register-btn me-2 mb-3">編集</a> <!-- mb-3 を追加 -->
        <p class="text-secondary">パスワードの変更は
            <span>
                <a href="{{ route('user.edit-password') }}" class="register-link">
                    こちら
                </a>
            </span>
        </p>
    </div>    
</div>
@endsection
