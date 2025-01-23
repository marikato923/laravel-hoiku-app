@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">園児の基本情報</h2>
    </div>

    <a href="{{ route('admin.children.index') }}" class="btn btn-secondary">戻る</a>

    <div class="card">
        <div class="card-header bg-primary text-white">
            {{ $child->last_name }} {{ $child->first_name }} ({{ $child->last_kana_name }} {{ $child->first_kana_name }})
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    {{-- 子供の画像 --}}
                    @if($child->img)
                    <img src="{{ asset('storage/children/' . $child->img) }}" 
                         alt="子供の写真" 
                         class="child-img">
                @else
                    <img src="{{ asset('storage/children/default.png') }}" 
                         alt="デフォルトの写真" 
                         class="child-img">
                @endif
                </div>
                <div class="col-md-8">
                    {{-- 基本情報リスト --}}
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>誕生日:</strong> {{ \Carbon\Carbon::parse($child->birthdate)->format('Y年m月d日') }}</li>
                        <li class="list-group-item"><strong>入園日:</strong> {{ \Carbon\Carbon::parse($child->admission_date)->format('Y年m月d日') }}</li>
                        <li class="list-group-item">
                            <strong>保護者:</strong> 
                            {{ $child->user ? $child->user->last_name . ' ' . $child->user->first_name : '保護者情報なし' }}
                        </li>
                        <li class="list-group-item">
                            <strong>既往歴:</strong> 
                            {{ $child->medical_history ? $child->medical_history : 'なし' }}
                        </li>
                        <li class="list-group-item">
                            <strong>アレルギー:</strong> 
                            {{ $child->has_allergy ? 'あり (' . $child->allergy_type . ')' : 'なし' }}
                        </li>
                        <li class="list-group-item">
                            <strong>クラス:</strong> {{ optional($child->classroom)->name ?? '未登録' }}
                        </li>
                    </ul>
                </div>
            </div>
            {{-- 編集・削除ボタン --}}
            <div class="mt-3">
                <a href="{{ route('admin.children.edit', $child->id) }}" class="btn btn-primary">編集</a>
                <form action="{{ route('admin.children.destroy', $child->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('本当に削除しますか？')">削除</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
