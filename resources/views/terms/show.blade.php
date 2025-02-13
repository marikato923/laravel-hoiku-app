@extends('layouts.app')

@section('breadcrumbs')
<nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">ホーム</a></li>
        <li class="breadcrumb-item active" aria-current="page">利用規約</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container" style="max-width: 800px; margin: 0 auto;">
    <h2 class="text-center mb-4">利用規約</h2>
    <hr class="mb-4">

    @if ($terms)
        {!! nl2br(e($terms->content)) !!}
    @else
        <div class="alert alert-info text-center">
            <p>現在、利用規約は登録されていません。</p>
        </div>
    @endif
</div>
@endsection
