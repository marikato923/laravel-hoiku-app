@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>利用規約</h1>

        @if ($terms)
            <div class="container mb-4">
                {!! $terms->content !!}
            </div>
        @else
            <p>現在、園の基本情報は登録されていません。</p>
        @endif
    </div>
@endsection