@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">園児の編集</h2>
    <form action="{{ route('admin.children.update', $child->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- 基本情報 --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="last_name" class="form-label">姓</label>
                <input type="text" name="last_name" id="last_name" class="form-control" value="{{ $child->last_name }}" required>
            </div>
            <div class="col-md-6">
                <label for="first_name" class="form-label">名</label>
                <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $child->first_name }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="last_kana_name" class="form-label">セイ</label>
                <input type="text" name="last_kana_name" id="last_kana_name" class="form-control" value="{{ $child->last_kana_name }}" required>
            </div>
            <div class="col-md-6">
                <label for="first_kana_name" class="form-label">メイ</label>
                <input type="text" name="first_kana_name" id="first_kana_name" class="form-control" value="{{ $child->first_kana_name }}" required>
            </div>
        </div>

        {{-- 保護者選択 --}}
        <div class="mb-3">
            <label for="user_id" class="form-label">保護者</label>
            <select name="user_id" id="user_id" class="form-control" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $user->id == $child->user_id ? 'selected' : '' }}>
                        {{ $user->last_name }} {{ $user->first_name }} (ID: {{ $user->id }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- クラス選択 --}}
        <div class="mb-3">
            <label for="classroom_id" class="form-label">クラス</label>
            <select name="classroom_id" id="classroom_id" class="form-control">
                @foreach ($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ $classroom->id == $child->classroom_id ? 'selected' : '' }}>
                        {{ $classroom->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- その他情報 --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="birthdate" class="form-label">生年月日</label>
                <input type="date" name="birthdate" id="birthdate" class="form-control" value="{{ $child->birthdate }}" required>
            </div>
            <div class="col-md-6">
                <label for="admission_date" class="form-label">入園日</label>
                <input type="date" name="admission_date" id="admission_date" class="form-control" value="{{ $child->admission_date }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="medical_history" class="form-label">既往歴</label>
            <textarea name="medical_history" id="medical_history" class="form-control">{{ $child->medical_history }}</textarea>
        </div>

        <div class="mb-3">
            <label for="has_allergy" class="form-label">アレルギーの有無</label>
            <select name="has_allergy" id="has_allergy" class="form-control" required>
                <option value="0" {{ $child->has_allergy == 0 ? 'selected' : '' }}>なし</option>
                <option value="1" {{ $child->has_allergy == 1 ? 'selected' : '' }}>あり</option>
            </select>
        </div>

        <div class="mb-3" id="allergy_type_div" style="{{ $child->has_allergy ? '' : 'display:none;' }}">
            <label for="allergy_type" class="form-label">アレルギーの種類</label>
            <input type="text" name="allergy_type" id="allergy_type" class="form-control" value="{{ $child->allergy_type }}">
        </div>

        <div class="mb-3">
            <label for="img" class="form-label">画像</label>
            @if ($child->img)
                <img src="{{ asset('storage/children/' . $child->img) }}" alt="画像" style="width: 100px; height: 100px;">
            @endif
            <input type="file" name="img" id="img" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">更新</button>
    </form>
</div>

<script>
    document.getElementById('has_allergy').addEventListener('change', function () {
        const allergyDiv = document.getElementById('allergy_type_div');
        allergyDiv.style.display = this.value == 1 ? 'block' : 'none';
    });
</script>
@endsection
