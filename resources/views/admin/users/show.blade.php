@extends('layouts.admin')

@section('breadcrumbs')
    <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">ホーム</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">会員一覧</a></li>
            <li class="breadcrumb-item active" aria-current="page">会員情報</li>
        </ol>
    </nav>
@endsection

@section('content')
        <div class="container pt-4">
            <div class="text-center mb-4">
                <h1 class="mb-3" style="font-weight: normal;"><span style="font-size: 0.8em;">会員情報 :</span> {{ $user->last_name }} {{ $user->first_name }}</h1>
            </div>

            <ul class="list-unstyled mx-auto" style="max-width: 500px;">
                <li class="mb-3"><strong>ユーザーID:</strong> {{ $user->id }}</li>
                <li class="mb-3"><strong>かな:</strong> {{ $user->last_kana_name }} {{ $user->first_kana_name }}</li>
                <li class="mb-3"><strong>メールアドレス:</strong> {{ $user->email }}</li>
                <li class="mb-3"><strong>電話番号:</strong> {{ $user->phone_number }}</li>
                <li class="mb-3"><strong>郵便番号:</strong> {{ $user->postal_code }}</li>
                <li class="mb-3"><strong>住所:</strong> {{ $user->address }}</li>
                <li class="mb-3"><strong>登録日:</strong> {{ $user->created_at->format('Y年m月d日') }}</li>
                <li class="mb-3"><strong>子供の情報:</strong> 
                    @php
                        $childrenNames = $user->children->map(fn($child) => $child->last_name . ' ' . $child->first_name)->implode(', ');
                    @endphp
                    {{ $childrenNames ?: '未登録' }}
                </li>
            </ul>
        </div>
@endsection
