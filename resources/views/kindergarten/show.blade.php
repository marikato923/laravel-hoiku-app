@extends('layouts.app')

@section('breadcrumbs')
<nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">ホーム</a></li>
        <li class="breadcrumb-item active" aria-current="page">園について</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container" style="max-width: 800px; margin: 0 auto;">
    <h2 class="text-center mb-4">園の基本情報</h2>
    <hr class="mb-4">

    @if ($kindergarten)
        <p class="mb-2"><strong>施設名:</strong> {{ $kindergarten->name }}</p>
        <p class="mb-2"><strong>電話番号:</strong> {{ substr($kindergarten->phone_number, 0, 2) . '-' . substr($kindergarten->phone_number, 2, 4) . '-' . substr($kindergarten->phone_number, 6, 4) }}</p>
        <p class="mb-2"><strong>所在地:</strong> {{ '〒' . substr($kindergarten->postal_code, 0, 3) . '-' . substr($kindergarten->postal_code, 3) . ' ' . $kindergarten->address }}</p>
        <p class="mb-2"><strong>代表者:</strong> {{ $kindergarten->principal }}</p>
        <p class="mb-2"><strong>創立:</strong> {{ $kindergarten->establishment_date }}</p>
        <p class="mb-2"><strong>職員数:</strong> {{ $kindergarten->number_of_employees }}</p>
    @else
        <div class="alert alert-info text-center">
            <p>現在、園の基本情報は登録されていません。</p>
        </div>
    @endif
</div>
@endsection
