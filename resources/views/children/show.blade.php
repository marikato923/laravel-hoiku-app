@extends('layouts.app')

@section('content')
<div class="container">
    {{-- 見出し --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">お子様の基本情報</h2>
    </div>

    {{-- タブ --}}
    <ul class="nav nav-tabs mb-4" id="siblingsTab" role="tablist">
        @foreach ($siblings as $index => $sibling)
            <li class="nav-item">
                <a class="nav-link {{ $index === 0 ? 'active' : '' }}" id="tab-{{ $sibling->id }}" 
                   data-bs-toggle="tab" href="#content-{{ $sibling->id }}" role="tab" 
                   aria-controls="content-{{ $sibling->id }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                    {{ $sibling->last_name }} {{ $sibling->first_name }}
                </a>
            </li>
        @endforeach
    </ul>

    {{-- タブの内容 --}}
    <div class="tab-content" id="siblingsTabContent">
        @foreach ($siblings as $index => $sibling)
            <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="content-{{ $sibling->id }}" 
                 role="tabpanel" aria-labelledby="tab-{{ $sibling->id }}">
                {{-- カード形式で基本情報を表示 --}}
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        {{ $sibling->last_name }} {{ $sibling->first_name }} ({{ $sibling->last_kana_name }} {{ $sibling->first_kana_name }})
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                {{-- 子供の画像 --}}
                                @if($sibling->img)
                                    <img src="{{ asset('storage/children/' . $sibling->img ?: 'default.png') }}" alt="お子様の写真" class="child-img">
                                @else
                                    <img src="{{ asset('images/default-child.png') }}" alt="デフォルト画像" class="child-img">
                                @endif
                            </div>
                            <div class="col-md-8">
                                {{-- 基本情報リスト --}}
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>誕生日:</strong> {{ \Carbon\Carbon::parse($sibling->birthdate)->format('Y年m月d日') }}</li>
                                    <li class="list-group-item"><strong>入園日:</strong> {{ \Carbon\Carbon::parse($sibling->admission_date)->format('Y年m月d日') }}</li>
                                    <li class="list-group-item">
                                        <strong>既往歴:</strong> 
                                        {{ $sibling->medical_history ? $sibling->medical_history : 'なし' }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>アレルギー:</strong> 
                                        {{ $sibling->has_allergy ? 'あり (' . $sibling->allergy_type . ')' : 'なし' }}
                                    </li>
                                    <li class="list-group-item">
                                        <strong>クラス:</strong> {{ optional($sibling->classroom)->name ?? '未登録' }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        {{-- 編集・削除ボタン --}}
                        <div class="mt-3">
                            <a href="{{ route('admin.children.edit', $sibling->id) }}" class="btn btn-primary">編集</a>
                            <form action="{{ route('admin.children.destroy', $sibling->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('本当に削除しますか？')">削除</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection