<!-- resources/views/children/create.blade.php -->
@extends('layouts.admin')

{{-- @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs]) --}}

@section('content')
    <div class="container">
        <h1>新規作成</h1>

        <form action="{{ route('admin.children.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">姓</label>
                <input type="text" name="last_name" id="last_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="name">名</label>
                <input type="text" name="first_name" id="first_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="last_kana_name">セイ</label>
                <input type="text" name="last_kana_name" id="last_kana_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="first_kana_name">メイ</label>
                <input type="text" name="first_kana_name" id="first_kana_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="img">画像</label>
                <input type="file" name="img" id="img" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="user_id">保護者氏名</label>
                <select name="user_id" id="user_id" class='form_control' required>
                    <option value="">選択してください</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->last_name }} {{ $user->first_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="birthdate">生年月日</label>
                <input type="date" name="birthdate" id="birthdate" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="admission_date">入園日</label>
                <input type="date" name="admission_date" id="admission_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="medical_history">既往歴</label>
                <textarea name="medical_history" id="medical_history" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label for="has_allergy">アレルギーの有無</label>
                <select name="has_allergy" id="has_allergy" class="form-control" required>
                    <option value="0">なし</option>
                    <option value="1">あり</option>
                </select>
            </div>

            <div class="form-group" id="allergy_type_div" style="display:none;">
                <label for="allergy_type">アレルギーの種類</label>
                <input type="text" name="allergy_type" id="allergy_type" class="form-control">
            </div>

            <div>
                <label for="classroom_id">クラス</label>
                    <select name="classroom_id" required>
                    <option value="">クラスを選択してください</option>
                    @foreach ($classrooms as $classroom)
                        <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">登録</button>
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