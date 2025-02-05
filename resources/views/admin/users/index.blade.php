@extends('layouts.admin')

@section('breadcrumbs')
    <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">ホーム</a></li>
            <li class="breadcrumb-item active" aria-current="page">会員一覧</li>
        </ol>
    </nav>
@endsection

@section('content')
        <div class="admin-users-container">
            <h1 class="admin-users-title text-center mb-4">会員一覧</h1>
            {{--  検索フォーム --}}
            <form method="GET" action="{{ route('admin.users.index') }}" class="admin-users-search-form mt-3 mb-4">
                <div class="input-group">
                    <input type="text" class="form-control admin-users-search-input" name="keyword" placeholder="検索キーワード" value="{{ $keyword }}">
                    <button class="btn attendance-btn" type="submit">検索</button>
                </div>
            </form>
            {{--  会員一覧テーブル --}}
            <table class="table admin-users-table mt-3">
                <thead>
                    <tr>
                        <th>会員ID</th>
                        <th>名前</th>
                        <th>フリガナ</th>
                        <th>電話番号</th>
                        <th>住所</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="admin-users-name-link">
                                {{ $user->last_name }} {{ $user->first_name }}
                            </a>
                        </td>
                        <td>{{ $user->last_kana_name }} {{ $user->first_kana_name }}</td>
                        <td>{{ $user->phone_number }}</td>
                        <td>{{ $user->address }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{--  ページネーション --}}
            <div class="d-flex justify-content-center kodomolog-pagination mt-4">
                <div class="pagination-container">
                    {{ $users->links() }}
                </div>
            </div>
        </div>

<style>
    .admin-users-container {
        padding: 5px;
    }

    .admin-users-search-form {
    max-width: 400px; 
    margin: 0 auto; 
    }

    .admin-users-search-form .input-group {
        display: flex; 
        align-items: center; 
        gap: 0; 
    }

    .admin-users-search-form .input-group .form-control {
        flex: 1; 
        border-top-right-radius: 0; 
        border-bottom-right-radius: 0; 
        }

    .admin-users-table {
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
    }

    .admin-users-table th,
    .admin-users-table td {
        text-align: center;
        padding: 10px;
    }

    .admin-users-name-link {
        color: rgb(200, 100, 100);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .admin-users-name-link:hover {
        color: rgb(255, 175, 175);
    }
</style>
@endsection
