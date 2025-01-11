<!-- resources/views/children/show.blade.php -->
@extends('layouts.admin')

{{-- @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs]) --}}

@section('content')
    <div class="container">
        <h1>園児詳細</h1>

        <p><strong>名前:</strong> {{ $child->last_name }} {{ $child->first_name }} </p>
        <td><img src="{{ asset('storage/children/' . $child->image) }}" width="50" height="50" alt="child image"></td>
        <p><strong>フリガナ:</strong> {{ $child->last_kana_name }} {{ $child->first_kana_name }} </p>
        <p><strong>保護者氏名:</strong> 
            @if ($child->user)
                {{ $child->user->last_name }} {{ $child->user->first_name }}
            @else
                保護者情報なし
            @endif
        </p>
        <p><strong>生年月日:</strong> {{ $child->birthdate }}</p>
        <p><strong>入園日:</strong> {{ $child->admission_date }}</p>
        <p><strong>アレルギー:</strong> {{ $child->has_allergy ? $child->allergy_type : 'なし' }}</p>
        <p><strong>既往歴:</strong> {{ $child->medical_history }}</p>
        <p><strong>クラス</strong>
            @if ($child->classroom)
                {{ $child->classroom->name }}
            @else
                クラス未設定
            @endif
        </p>
        <a href="{{ route('admin.children.edit', $child->id) }}" class="btn btn-primary">編集</a>
        <a href="{{ route('admin.children.index') }}" class="btn btn-secondary">戻る</a>
    </div>
@endsection