@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 800px; margin: 0 auto;">
    <h2 class="text-center mb-4">利用規約</h2>
    <hr class="mb-4">

    @if ($terms)
        {!! $terms->content !!}
    @else
        <div class="alert alert-info text-center">
            <p>現在、利用規約は登録されていません。</p>
        </div>
    @endif
</div>
@endsection
