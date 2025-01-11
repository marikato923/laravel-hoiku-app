@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>園の基本情報</h1>

        @if ($kindergarten)
        <div class="container mb-4">
            <div class="row pb-2 mb-2 border-bottom">
                <div class="col-3">
                    <span class="fw-bold">施設名</span>
                </div>

                <div class="col">
                    <span>{{ $kindergarten->name }}</span>
                </div>
            </div>
            
                                
            <div class="row pb-2 mb-2 border-bottom">
                <div class="col-3">
                    <span class="fw-bold">電話番号</span>
                </div>

                <div class="col">
                    <span>{{ substr($kindergarten->phone_number, 0, 2) . '-' . substr($kindergarten->phone_number, 2, 4) . '-' . substr($kindergarten->phone_number, 6, 4) }}</span>
                </div>
            </div>

            <div class="row pb-2 mb-2 border-bottom">
                <div class="col-3">
                    <span class="fw-bold">所在地</span>
                </div>

                <div class="col">
                    <span>{{ '〒' . substr($kindergarten->postal_code, 0, 3) . '-' . substr($kindergarten->postal_code, 3) . ' ' . $kindergarten->address }}</span>
                </div>
            </div>

            <div class="row pb-2 mb-2 border-bottom">
                <div class="col-3">
                    <span class="fw-bold">代表者</span>
                </div>

                <div class="col">
                    <span>{{ $kindergarten->principal }}</span>
                </div>
            </div> 
            
            <div class="row pb-2 mb-2 border-bottom">
                <div class="col-3">
                    <span class="fw-bold">創立</span>
                </div>

                <div class="col">
                    <span>{{ $kindergarten->establishment_date }}</span>
                </div>
            </div>   

            <div class="row pb-2 mb-2 border-bottom">
                <div class="col-3">
                    <span class="fw-bold">職員数</span>
                </div>

                <div class="col">
                    <span>{{ $kindergarten->number_of_employees }}</span>
                </div>
            </div>                                       
        </div>                 
        @else
            <p>現在、園の基本情報は登録されていません。</p>
        @endif
    </div>
@endsection