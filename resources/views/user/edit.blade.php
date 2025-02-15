@extends('layouts.app')

@section('breadcrumbs')
<nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">ホーム</a></li>
        <li class="breadcrumb-item"><a href="{{ route('user.show') }}">会員情報</a></li>
        <li class="breadcrumb-item active" aria-current="page">会員情報 編集</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4 kodomolog-app-container">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-7 col-md-8 col-sm-10">
            <h2 class="text-center mb-3">会員情報編集</h2>
            <hr class="mb-4">

            <form action="{{ route('user.update') }}" method="POST" style="max-width: 600px; width: 100%;">
                @csrf
                @method('PUT')
                <div class="card border-0">
                    <div class="card-body">
                        @php
                            $fields = [
                                'last_name' => '姓',
                                'first_name' => '名',
                                'last_kana_name' => 'セイ',
                                'first_kana_name' => 'メイ',
                                'email' => 'メールアドレス',
                                'phone_number' => '電話番号',
                                'postal_code' => '郵便番号',
                                'address' => '住所'
                            ];
                        @endphp

                        @foreach ($fields as $field => $label)
                            <div class="mb-3 row">
                                <label for="{{ $field }}" class="col-sm-4 col-form-label">{{ $label }}</label>
                                <div class="col-sm-8">
                                    <input type="text" id="{{ $field }}" 
                                           class="form-control @error($field) is-invalid @enderror"
                                           name="{{ $field }}" 
                                           value="{{ old($field, auth()->user()->$field) }}"
                                           style="max-width: 400px;">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach

                        <div class="text-center mt-3">
                            <button type="submit" class="btn register-btn shadow-none px-4 mt-3">更新</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection