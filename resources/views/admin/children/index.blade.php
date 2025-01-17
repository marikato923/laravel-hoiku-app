@extends('layouts.admin')

{{-- @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs]) --}}

@section('content')
    <div class="container">
        <h1>園児一覧</h1>
        
        <!-- 検索フォーム -->
        <form method="GET" action="{{ route('admin.children.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="keyword" class="form-control" placeholder="氏名・フリガナで検索" value="{{ $keyword }}">
                <button class="btn btn-outline-secondary" type="submit">検索</button>
            </div>
        </form>

        <!-- 新規作成ボタン -->
        <a href="{{ route('admin.children.create') }}" class="btn btn-primary mb-3">新規作成</a>

        <!-- 園児一覧 -->
        <table class="table">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>フリガナ</th>
                    <th>生年月日</th>
                    <th>画像</th>
                    <th>クラス</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($children as $child)
                    <tr>
                        <td>
                            <a href="{{ route('admin.children.show', $child->id) }}">
                                {{ $child->last_name }} {{ $child->first_name }}
                            </td>
                        <td>{{ $child->last_kana_name }} {{ $child->first_kana_name }}</td>
                        <td>{{ $child->birthdate }}</td>
                        <td><img src="{{ asset('storage/children/' . $child->image) }}" width="50" height="50" alt="child image"></td>
                        <td>@if ($child->classroom)
                            {{ $child->classroom->name }}
                            @else
                                クラス未設定
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.children.edit', $child->id) }}" class="btn btn-primary">編集</a>
                            <form action="{{ route('admin.children.destroy', $child->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- ページネーション -->
        <div class="d-flex justify-content-center">
            {{ $children->links() }}
        </div>
    </div>
@endsection

