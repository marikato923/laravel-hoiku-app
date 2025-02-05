@extends('layouts.admin')

{{-- @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs]) --}}

@section('breadcrumbs')
    <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">ホーム</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.terms.index') }}">利用規約</a></li>
            <li class="breadcrumb-item active" aria-current="page">編集</li>
        </ol>
    </nav>
@endsection

@section('content')
        <div class="col container">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8 col-md-9">
                    <h1 class="mb-4 text-center">利用規約</h1>

                    <hr class="mb-4">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.terms.update', $term) }}">
                        @csrf
                        @method('patch')
                        <div class="form-group mb-3">
                            <textarea class="form-control" name="content" cols="30" rows="24">{{ old('content', $term->content) }}</textarea>
                        </div>

                        <hr class="my-4">

                        <div class="form-group d-flex justify-content-center mb-4">
                            <button type="submit" class="btn register-btn shadow-sm">更新</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
