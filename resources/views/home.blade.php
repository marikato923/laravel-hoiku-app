@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>会員ホーム</h1>

        @if (session('flash_message'))
            <div class="container my-3">
                <div class="alert alert-info" role="alert">
                    <p class="mb-0">{{ 'flash_message' }}</p>
                </div>
            </div>
        @endif
@endsection