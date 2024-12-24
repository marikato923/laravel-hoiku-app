@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>会員ホーム</h1>

        <!-- ログアウトボタン -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">ログアウト</button>
        </form>
    </div>
@endsection