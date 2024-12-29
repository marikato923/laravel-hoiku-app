@extends('layouts.admin')

{{-- @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs]) --}}

 @section('content')
    <div class="col container">
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8 col-md-9">
                <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.kindergarten.index') }}">保育園概要</a></li>
                        <li class="breadcrumb-item active" aria-current="page">保育園概要編集</li>
                    </ol>
                </nav>

                <h1 class="mb-4 text-center">保育園概要編集</h1>

                <hr class="mb-4">

                <form method="POST" action="{{ route('admin.kindergarten.update', $kindergarten) }}">
                    @csrf
                    @method('patch')
                    <div class="form-group row mb-3">
                        <label for="name" class="col-md-5 col-form-label text-md-left fw-bold">施設名</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $kindergarten->name) }}">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="capital" class="col-md-5 col-form-label text-md-left fw-bold">電話番号</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $kindergarten->phone_number) }}">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="postal_code" class="col-md-5 col-form-label text-md-left fw-bold">郵便番号</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code', $kindergarten->postal_code) }}">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="address" class="col-md-5 col-form-label text-md-left fw-bold">所在地</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $kindergarten->address) }}">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="representative" class="col-md-5 col-form-label text-md-left fw-bold">代表者</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" id="principal" name="principal" value="{{ old('principal', $kindergarten->principal) }}">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="establishment_date" class="col-md-5 col-form-label text-md-left fw-bold">創立</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" id="establishment_date" name="establishment_date" value="{{ old('establishment_date', $kindergarten->establishment_date) }}">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="number_of_employees" class="col-md-5 col-form-label text-md-left fw-bold">職員数</label>

                        <div class="col-md-7">
                            <input type="text" class="form-control" id="number_of_employees" name="number_of_employees" value="{{ old('number_of_employees', $kindergarten->number_of_employees) }}">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="form-group d-flex justify-content-center mb-4">
                        <button type="submit" class="btn shadow-sm w-50">更新</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection