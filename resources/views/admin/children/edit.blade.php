<!-- resources/views/children/edit.blade.php -->
@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>園児の編集</h1>

        <a href="{{ route('admin.children.show', $child->id) }}" class="btn btn-secondary">戻る</a>

        <form action="{{ route('admin.children.update', $child->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <div class="form-group">
                    <label for="name">姓</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" value="{{ $child->last_name }}" required>
                </div>
    
                <div class="form-group">
                    <label for="name">名</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $child->first_name }}" required>
                </div>
    
                <div class="form-group">
                    <label for="last_kana_name">セイ</label>
                    <input type="text" name="last_kana_name" id="last_kana_name" class="form-control" value="{{ $child->last_kana_name }}" required>
                </div>
    
                <div class="form-group">
                    <label for="first_kana_name">メイ</label>
                    <input type="text" name="first_kana_name" id="first_kana_name" class="form-control" value="{{ $child->first_kana_name }}" required>
                </div>

            <div class="form-group">
                <label for="birthdate">生年月日</label>
                <input type="date" name="birthdate" id="birthdate" class="form-control" value="{{ $child->birthdate }}" required>
            </div>

            <div class="form-group">
                <label for="admission_date">入園日</label>
                <input type="date" name="admission_date" id="admission_date" class="form-control" value="{{ $child->admission_date }}" required>
            </div>

            <div class="form-group">
                <label for="medical_history">既往歴</label>
                <textarea name="medical_history" id="medical_history" class="form-control">{{ $child->medical_history }}</textarea>
            </div>

            <div class="form-group">
                <label for="has_allergy">アレルギーの有無</label>
                <select name="has_allergy" id="has_allergy" class="form-control" required>
                    <option value="0" {{ $child->has_allergy == 0 ? 'selected' : '' }}>なし</option>
                    <option value="1" {{ $child->has_allergy == 1 ? 'selected' : '' }}>あり</option>
                </select>
            </div>

            <div class="form-group" id="allergy_type_div" style="{{ $child->has_allergy ? '' : 'display:none;' }}">
                <label for="allergy_type">アレルギーの種類</label>
                <input type="text" name="allergy_type" id="allergy_type" class="form-control" value="{{ $child->allergy_type }}">
            </div>

            <button type="submit" class="btn btn-primary">更新</button>
        </form>
    </div>

    <script>
        document.getElementById('has_allergy').addEventListener('change', function() {
            var allergyDiv = document.getElementById('allergy_type_div');
            if (this.value == 1) {
                allergyDiv.style.display = 'block';
            } else {
                allergyDiv.style.display = 'none';
            }
        });
    </script>
@endsection