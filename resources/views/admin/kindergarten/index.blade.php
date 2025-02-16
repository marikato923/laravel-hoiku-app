@extends('layouts.admin')

@section('breadcrumbs')
    <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">ホーム</a></li>
            <li class="breadcrumb-item active" aria-current="page">園の概要</li>
        </ol>
    </nav>
@endsection

@section('content')
        <div class="col container pt-2">
            <div class="row justify-content-center">
                <div class="col-xl-7 col-lg-8 col-md-9">

                    <h1 class="mb-4 text-center">保育園概要</h1>    
                    
                    <div class="d-flex justify-content-end align-items-end mb-3">                    
                        <div>
                            <a href="{{ route('admin.kindergarten.edit', $kindergarten) }}" class="btn register-btn me-2" style="white-space: nowrap;">編集</a>                        
                        </div>
                    </div>                 

                    <div class="container mb-4">
                        <div class="row pb-2 mb-2">
                            <div class="col-3">
                                <span class="fw-bold">施設名</span>
                            </div>

                            <div class="col">
                                <span>{{ $kindergarten->name }}</span>
                            </div>
                        </div>
                        
                                            
                        <div class="row pb-2 mb-2">
                            <div class="col-3">
                                <span class="fw-bold">電話番号</span>
                            </div>

                            <div class="col">
                                <span>{{ substr($kindergarten->phone_number, 0, 2) . '-' . substr($kindergarten->phone_number, 2, 4) . '-' . substr($kindergarten->phone_number, 6, 4) }}</span>
                            </div>
                        </div>

                        <div class="row pb-2 mb-2">
                            <div class="col-3">
                                <span class="fw-bold">所在地</span>
                            </div>

                            <div class="col">
                                <span>{{ '〒' . substr($kindergarten->postal_code, 0, 3) . '-' . substr($kindergarten->postal_code, 3) . ' ' . $kindergarten->address }}</span>
                            </div>
                        </div>

                        <div class="row pb-2 mb-2">
                            <div class="col-3">
                                <span class="fw-bold">代表者</span>
                            </div>

                            <div class="col">
                                <span>{{ $kindergarten->principal }}</span>
                            </div>
                        </div> 
                        
                        <div class="row pb-2 mb-2">
                            <div class="col-3">
                                <span class="fw-bold">創立</span>
                            </div>

                            <div class="col">
                                <span>{{ $kindergarten->establishment_date }}</span>
                            </div>
                        </div>   

                        <div class="row pb-2 mb-2">
                            <div class="col-3">
                                <span class="fw-bold">職員数</span>
                            </div>

                            <div class="col">
                                <span>{{ $kindergarten->number_of_employees }}</span>
                            </div>
                        </div>                                       
                    </div>                                               
                </div>                          
            </div>
        </div>       
@endsection
