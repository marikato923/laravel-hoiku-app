@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 800px; margin: 0 auto;">
    <h2 class="text-center mb-4">お子様の基本情報</h2>
    <hr class="mb-4">

    <div class="text-end mb-4">
        <a href="{{ route('children.create') }}" class="btn btn-success">新規登録</a>
    </div>

    @if($siblings->isEmpty())
        <div class="alert alert-info text-center">
            <p>まだお子様の情報が登録されていません。</p>
            <p>「新規登録」ボタンを押してお子様の情報を登録してください。</p>
        </div>
    @else
        {{-- タブ --}}
        <ul class="nav nav-tabs mb-4" id="siblingsTab" role="tablist" style="font-size: 1.2rem;">
            @foreach ($siblings as $index => $sibling)
                @php
                    $themeColor = optional($sibling->classroom)->theme_color ?? '#e0e0e0'; // クラスのテーマカラー（デフォルト: 淡い色）
                @endphp
                <li class="nav-item" style="margin-right: 20px;">
                    <a class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                       id="tab-{{ $sibling->id }}" 
                       data-bs-toggle="tab" 
                       href="#content-{{ $sibling->id }}" 
                       role="tab" 
                       aria-controls="content-{{ $sibling->id }}" 
                       aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                       style="background-color: {{ $themeColor }}; color: #333333; border-radius: 15px 15px 0 0; padding: 10px 30px; transition: all 0.3s ease;">
                        {{ $sibling->first_name }}
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- タブの内容 --}}
        <div class="tab-content" id="siblingsTabContent">
            @foreach ($siblings as $index => $sibling)
                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="content-{{ $sibling->id }}" role="tabpanel" aria-labelledby="tab-{{ $sibling->id }}">
                    {{-- 基本情報 --}}
                    <div class="px-3 py-4">
                        <div class="text-center mb-4">
                            {{-- 子供の画像 --}}
                            @php
                                $themeColor = optional($sibling->classroom)->theme_color ?? '#ccc'; // デフォルト色
                            @endphp
                            <div class="child-show-img-wrapper" style="border-color:{{ $themeColor }};">
                                @if($sibling->img)
                                    <img src="{{ asset('storage/children/' . $sibling->img) }}" alt="子供の写真" class="child-img img-fluid">
                                @else
                                    <img src="{{ asset('storage/children/default.png') }}" alt="デフォルトの写真" class="child-img img-fluid">
                                @endif
                            </div>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>名前（姓名）:</strong> {{ $sibling->last_name }} {{ $sibling->first_name }}</li>
                            <li class="list-group-item"><strong>フリガナ:</strong> {{ $sibling->last_kana_name }} {{ $sibling->first_kana_name }}</li>
                            <li class="list-group-item"><strong>誕生日:</strong> {{ \Carbon\Carbon::parse($sibling->birthdate)->format('Y年m月d日') }}</li>
                            <li class="list-group-item"><strong>入園日:</strong> {{ \Carbon\Carbon::parse($sibling->admission_date)->format('Y年m月d日') }}</li>
                            <li class="list-group-item"><strong>既往歴:</strong> {{ $sibling->medical_history ? $sibling->medical_history : 'なし' }}</li>
                            <li class="list-group-item"><strong>アレルギー:</strong> {{ $sibling->has_allergy ? 'あり (' . $sibling->allergy_type . ')' : 'なし' }}</li>
                            <li class="list-group-item"><strong>クラス:</strong> {{ optional($sibling->classroom)->name ?? '未登録' }}</li>
                        </ul>
                        <div class="text-end mt-4">
                            <a href="{{ route('children.edit', $sibling->id) }}" class="btn btn-primary">編集リクエスト</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
