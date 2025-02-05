@extends('layouts.app')

@section('breadcrumbs')
<nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">ホーム</a></li>
        <li class="breadcrumb-item"><a href="{{ route('children.show') }}">お子様の基本情報</a></li>
        <li class="breadcrumb-item active" aria-current="page">お子様の基本情報 編集</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4 kodomolog-app-container">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-7 col-md-8 col-sm-8 mx-auto">
            <div class="wrapper w-100" style="max-width: 80%; margin: 0 auto;">
                
                <h2 class="text-center mb-4">園児の編集</h2>
                <hr class="mb-4">
                
                <form action="{{ route('children.update', $child->id) }}" method="POST" enctype="multipart/form-data" style="max-width: 600px; margin: 0 auto;">
                    @csrf
                    @method('PUT')
                    
                    {{-- 基本情報 --}}
                    <div class="form-group mb-3">
                        <label for="last_name">姓</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" value="{{ $child->last_name }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="first_name">名</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $child->first_name }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="last_kana_name">セイ</label>
                        <input type="text" name="last_kana_name" id="last_kana_name" class="form-control" value="{{ $child->last_kana_name }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="first_kana_name">メイ</label>
                        <input type="text" name="first_kana_name" id="first_kana_name" class="form-control" value="{{ $child->first_kana_name }}" required>
                    </div>
                    
                    {{-- 保護者選択 --}}
                    <div class="form-group mb-3">
                        <label for="user_id">保護者</label>
                        <select name="user_id" id="user_id" class="form-control" required>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ $user->id == $child->user_id ? 'selected' : '' }}>
                                    {{ $user->last_name }} {{ $user->first_name }} (ID: {{ $user->id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- クラス選択 --}}
                    <div class="form-group mb-3">
                        <label for="classroom_id">クラス</label>
                        <select name="classroom_id" id="classroom_id" class="form-control">
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" {{ $classroom->id == $child->classroom_id ? 'selected' : '' }}>
                                    {{ $classroom->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- 画像 --}}
                    <div class="form-group mb-3 text-center">
                        <label for="img" class="form-label">画像</label>
                        <div class="img-wrapper-edit d-flex justify-content-center">
                            @if ($child->img)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/children/' . $child->img) }}" alt="お子様の画像" class="child-img-edit">
                                </div>
                            @endif
                        </div>
                        <input type="file" name="img" id="img" class="form-control" style="max-width: 600px; margin: 0 auto;">
                    </div>
                    
                    {{-- その他情報 --}}
                    <div class="form-group mb-3">
                        <label for="birthdate">生年月日</label>
                        <input type="date" name="birthdate" id="birthdate" class="form-control" value="{{ $child->birthdate }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="admission_date">入園日</label>
                        <input type="date" name="admission_date" id="admission_date" class="form-control" value="{{ $child->admission_date }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="medical_history">既往歴</label>
                        <textarea name="medical_history" id="medical_history" class="form-control">{{ $child->medical_history }}</textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="has_allergy">アレルギーの有無</label>
                        <select name="has_allergy" id="has_allergy" class="form-control" required>
                            <option value="0" {{ $child->has_allergy == 0 ? 'selected' : '' }}>なし</option>
                            <option value="1" {{ $child->has_allergy == 1 ? 'selected' : '' }}>あり</option>
                        </select>
                    </div>
                    <div class="form-group mb-3" id="allergy_type_div" style="{{ $child->has_allergy ? '' : 'display:none;' }}">
                        <label for="allergy_type">アレルギーの種類</label>
                        <input type="text" name="allergy_type" id="allergy_type" class="form-control" value="{{ $child->allergy_type }}">
                    </div>
                    
                    <div class="text-center mb-5">
                        <button type="submit" class="btn register-btn shadow-none px-4 mt-3">更新</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('has_allergy').addEventListener('change', function () {
        const allergyDiv = document.getElementById('allergy_type_div');
        allergyDiv.style.display = this.value == 1 ? 'block' : 'none';
    });
</script>
@endsection
