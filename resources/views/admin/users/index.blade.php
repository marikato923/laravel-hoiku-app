@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>会員一覧</h1>

    <!-- 検索フォーム -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" class="form-control" name="keyword" placeholder="検索キーワード" value="{{ $keyword }}">
            <button class="btn btn-primary" type="submit">検索</button>
        </div>
    </form>

    <!-- 会員一覧テーブル -->
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>名前</th>
                <th>かな</th>
                <th>電話番号</th>
                <th>住所</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->last_name }} {{ $user->first_name }} </td>
                <td> {{ $user->last_kana_name }} {{ $user->first_kana_name }} </td>
                <td>{{ $user->phone_number }}</td>
                <td>{{ $user->address }}</td>
                <td>
                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info">詳細</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- ページネーション -->
    <div class="d-flex justify-content-between">
        <span>全会員数: {{ $total }}</span>
        <div>
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection