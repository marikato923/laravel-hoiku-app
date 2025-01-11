@extends('layouts.app')

@section('title', 'ホーム画面')

@section('content')
    <h1>会員ホーム</h1>

    <!-- 子供を選択 -->
    <div class="form-group">
        <label for="child_id">子供を選択</label>
        <select class="form-control" id="child_id" name="child_id">
            <option value="" disabled selected>選択してください</option>
            @foreach ($children as $child)
                <option value="{{ $child->id }}">{{ $child->last_name }} {{ $child->first_name }}</option>
            @endforeach
        </select>
    </div>

    <!-- タブナビゲーション -->
    @include('components.tab-navigation')

    <!-- タブコンテンツ -->
    <div class="tab-content" id="childTabContent">
        @include('components.attendance-form')
        @include('components.basic-info')
    </div>
@endsection