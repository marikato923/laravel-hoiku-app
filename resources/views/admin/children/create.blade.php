@extends('layouts.admin')

@section('breadcrumbs')
    <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">ホーム</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.children.index') }}">園児一覧</a></li>
            <li class="breadcrumb-item active" aria-current="page">新規作成</li>
        </ol>
    </nav>
@endsection

@section('content')
        <div class="container py-4 kodomolog-app-container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-7 col-md-8 col-sm-8 mx-auto">
                    <div class="wrapper w-100" style="max-width: 80%; margin: 0 auto;">

                        <h2 class="text-center mb-4">園児の新規登録</h2>
                        <hr class="mb-4">

                        <form action="{{ route('admin.children.store') }}" method="POST" enctype="multipart/form-data" style="max-width: 600px; margin: 0 auto;">
                            @csrf

                            {{-- 基本情報 --}}
                            <div class="form-group mb-3">
                                <label for="last_name">姓</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="first_name">名</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="last_kana_name">セイ</label>
                                <input type="text" name="last_kana_name" id="last_kana_name" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="first_kana_name">メイ</label>
                                <input type="text" name="first_kana_name" id="first_kana_name" class="form-control" required>
                            </div>

                            {{-- 保護者選択 --}}
                            <div class="form-group mb-3">
                                <label for="user_id">保護者</label>
                                <select name="user_id" id="user_id" class="form-control" required>
                                    <option value="">保護者を選択してください</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->last_name }} {{ $user->first_name }} (ID: {{ $user->id }})</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- クラス選択 --}}
                            <div class="form-group mb-3">
                                <label for="classroom_id">クラス</label>
                                <select name="classroom_id" id="classroom_id" class="form-control">
                                    <option value="">クラスを選択してください</option>
                                    @foreach ($classrooms as $classroom)
                                        <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 画像 --}}
                            <div class="form-group mb-3 text-center">
                                <label for="img" class="form-label">画像</label>
                                <input type="file" name="img" id="img" class="form-control" style="max-width: 600px; margin: 0 auto;">
                            </div>

                            {{-- その他情報 --}}
                            <div class="form-group mb-3">
                                <label for="birthdate">生年月日</label>
                                <input type="date" name="birthdate" id="birthdate" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="admission_date">入園日</label>
                                <input type="date" name="admission_date" id="admission_date" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="medical_history">既往歴</label>
                                <textarea name="medical_history" id="medical_history" class="form-control"></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="has_allergy">アレルギーの有無</label>
                                <select name="has_allergy" id="has_allergy" class="form-control" required>
                                    <option value="0">なし</option>
                                    <option value="1">あり</option>
                                </select>
                            </div>

                            <div class="form-group mb-3" id="allergy_type_div" style="display: none;">
                                <label for="allergy_type">アレルギーの種類</label>
                                <input type="text" name="allergy_type" id="allergy_type" class="form-control">
                            </div>

                            <div class="text-center mb-5">
                                <button type="submit" class="btn register-btn shadow-none px-4 mt-3 mb-2">登録</button>
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
