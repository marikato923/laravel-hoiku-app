@extends('layouts.app')

@section('breadcrumbs')
<nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">ホーム</a></li>
        <li class="breadcrumb-item"><a href="{{ route('children.show') }}">お子様の基本情報</a></li>
        <li class="breadcrumb-item active" aria-current="page">お子様の新規登録</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4 kodomolog-app-container">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-7 col-md-8 col-sm-8 mx-auto">
            <div class="wrapper w-100" style="max-width: 80%; margin: 0 auto;">
                <h2 class="text-center mb-4 title-with-underline">お子様の新規登録</h2>
                <hr class="mb-4">

                <form action="{{ route('children.store') }}" method="POST" enctype="multipart/form-data" style="max-width: 600px; margin: 0 auto;">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="last_name">姓</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="例: 山田" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="first_name">名</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="例: 太郎" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="last_kana_name">セイ（カタカナ）</label>
                        <input type="text" name="last_kana_name" id="last_kana_name" class="form-control" placeholder="例: ヤマダ" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="first_kana_name">メイ（カタカナ）</label>
                        <input type="text" name="first_kana_name" id="first_kana_name" class="form-control" placeholder="例: タロウ" required>
                    </div>

                    <div class="form-group mb-3 text-center">
                        <label for="img" class="form-label">画像</label>
                        <input type="file" name="img" id="img" class="form-control" style="max-width: 600px; margin: 0 auto;">
                    </div>

                    <div class="form-group mb-3">
                        <label for="birthdate">生年月日</label>
                        <input type="date" name="birthdate" id="birthdate" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="medical_history">既往歴</label>
                        <textarea name="medical_history" id="medical_history" class="form-control" placeholder="既往歴があれば記入してください"></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="has_allergy">アレルギーの有無</label>
                        <select name="has_allergy" id="has_allergy" class="form-control" required>
                            <option value="0" selected>なし</option>
                            <option value="1">あり</option>
                        </select>
                    </div>

                    <div class="form-group mb-3" id="allergy_type_div" style="display: none;">
                        <label for="allergy_type">アレルギーの種類</label>
                        <input type="text" name="allergy_type" id="allergy_type" class="form-control" placeholder="例: ピーナッツ">
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn register-btn shadow-none px-4 mt-3">登録</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // アレルギーの有無によるフォームの表示切り替え
    document.getElementById('has_allergy').addEventListener('change', function() {
        const allergyDiv = document.getElementById('allergy_type_div');
        allergyDiv.style.display = this.value == 1 ? 'block' : 'none';
    });
</script>
@endsection
