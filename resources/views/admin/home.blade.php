@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>管理者ホーム</h1>

    <div class="row">
        <div class="col-md-12">
            <h4>管理者メニュー</h4>
            <div class="list-group">
                <div>
                <a href="{{ route('admin.users.index') }}" class="list-group-item">会員一覧</a>
                </div>
                <div class="list-group-item">登園状況一覧</div>
                <div>
                <a href="{{ route('admin.children.index') }}" class="list-group-item">園児一覧<a>
                </div>
                <div class="list-group-item">緊急連絡先一覧</div>
                <div>
                <a href="{{ route('admin.classrooms.index') }}" class="list-group-item">クラス一覧
                </div>
                <div class="list-group-item">保育園概要</div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">

        </div>
    </div>
</div>
@endsection
