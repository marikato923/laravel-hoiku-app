@extends('layouts.app')

@section('content')
<div class="container py-4 kodomolog-app-container">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-7 col-md-8 col-sm-8 mx-auto">
            <div class="wrapper w-100" style="max-width: 80%; margin: 0 auto;">

                <h2 class="text-center mb-4">お子様の情報を編集</h2>
                <hr class="mb-4">

                <form action="{{ route('children.update', $child->id) }}" method="POST" enctype="multipart/form-data" style="max-width: 600px; margin: 0 auto;">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="last_name">姓</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" value="{{ $child->last_name }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="first_name">名</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" value="{{ $child->first_name }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="last_kana_name">セイ（カタカナ）</label>
                        <input type="text" name="last_kana_name" id="last_kana_name" class="form-control" value="{{ $child->last_kana_name }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="first_kana_name">メイ（カタカナ）</label>
                        <input type="text" name="first_kana_name" id="first_kana_name" class="form-control" value="{{ $child->first_kana_name }}" required>
                    </div>

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
                    

                    <div class="form-group mb-3">
                        <label for="birthdate">生年月日</label>
                        <input type="date" name="birthdate" id="birthdate" class="form-control" value="{{ $child->birthdate }}" required>
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

                    <div class="text-center">
                        <button type="submit" class="btn register-btn shadow-none px-4 mt-3">送信</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // アレルギー情報の種類を動的に表示/非表示
    document.getElementById('has_allergy').addEventListener('change', function() {
        const allergyDiv = document.getElementById('allergy_type_div');
        allergyDiv.style.display = this.value == 1 ? 'block' : 'none';
    });
</script>
@endsection
