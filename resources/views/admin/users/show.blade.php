@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>会員詳細</h1>

    <!-- 会員詳細情報 -->
    <div class="card">
        <div class="card-header">
            <h5>{{ $user->name }}</h5>
        </div>
        <div class="card-body">
            <p><strong>かな:</strong> {{ $user->kana }}</p>
            <p><strong>メールアドレス:</strong> {{ $user->email }}</p>
            <p><strong>電話番号:</strong> {{ $user->phone_number }}</p>
            <p><strong>郵便番号:</strong> {{ $user->postal_code }}</p>
            <p><strong>住所:</strong> {{ $user->address }}</p>

            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-3">戻る</a>
        </div>
    </div>
</div>
@endsection