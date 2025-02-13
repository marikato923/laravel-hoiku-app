@extends('layouts.admin')

{{-- @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs]) --}}

@section('breadcrumbs')
    <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.kindergarten.index') }}">利用規約</a></li>
            <li class="breadcrumb-item active" aria-current="page">編集</li>
        </ol>
    </nav>
@endsection

@section('content')
        <div class="col container mb-5">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8 col-md-9">

                    <h1 class="mb-4 text-center">利用規約</h1>

                    <div class="d-flex justify-content-end align-items-end mb-3">
                        <div>
                            <a href="{{ route('admin.terms.edit', $term) }}" class="btn register-btn me-2">編集</a>
                        </div>
                    </div>

                    <div class="container mb-4">
                        <pre>{{ $term->content }}</pre>
                    </div>
                </div>
            </div>
        </div>
@endsection