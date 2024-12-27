<!-- resources/views/children/create.blade.php -->
@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>新規作成</h1>

        <form action="{{ route('admin.children.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">名前</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="kana">フリガナ</label>
                <input type="text" name="kana" id="kana" class="form-control" required>
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

            <button type="submit" class="btn btn-primary">保存</button>
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