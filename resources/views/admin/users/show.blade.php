@extends('layouts.admin')

{{-- @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs]) --}}

@section('content')
<div class="container">
    <h1>会員詳細</h1>

    <div class="card">
        <div class="card-header">
            <h5>{{ $user->last_name }} {{ $user->first_name }} </h5>
        </div>
        <div class="card-body">
            <p><strong>かな:</strong> {{ $user->last_kana_name }} {{ $user->first_kana_name }} </p>
            <p><strong>メールアドレス:</strong> {{ $user->email }}</p>
            <p><strong>電話番号:</strong> {{ $user->phone_number }}</p>
            <p><strong>郵便番号:</strong> {{ $user->postal_code }}</p>
            <p><strong>住所:</strong> {{ $user->address }}</p>

            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-3">戻る</a>
        </div>
    </div>
</div>
@endsection