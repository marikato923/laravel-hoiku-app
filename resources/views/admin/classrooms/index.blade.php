@extends('layouts.admin')

{{-- @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs]) --}}

@section('content')
    {{-- クラスの登録用モーダル --}}
    <div class="modal fade" id="createClassroomModal" tabindex="-1" aria-labelledby="createClassroomModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createClassroomModalLabel">クラスの登録</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                </div>
                <form action="{{ route('admin.classrooms.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn shadow-sm">登録</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- クラスの編集用モーダル --}}
    <div class="modal fade" id="editClassroomModal" tabindex="-1" aria-labelledby="editClassroomModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClassroomModalLabel">クラスの編集</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                </div>
                <form action="#" method="post" name="editClassroomForm">
                    @csrf
                    @method('patch')
                    <div class="modal-body">
                        <input type="text" class="form-control" name="name" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn shadow-sm">更新</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- クラスの削除用モーダル --}}
    <div class="modal fade" id="deleteClassroomModal" tabindex="-1" aria-labelledby="deleteClassroomModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteClassroomModalLabel">クラスの削除</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                </div>
                <div class="modal-footer">
                    <form action="#" method="post" name="deleteClassroomForm">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn shadow-sm">削除</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <h1>クラス一覧</h1>
        
        {{-- 検索フォーム --}}
        <form method="GET" action="{{ route('admin.classrooms.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="keyword" class="form-control" placeholder="クラス名で検索" value="{{ $keyword }}">
                <button class="btn btn-outline-secondary" type="submit">検索</button>
            </div>
        </form>

        {{-- 新規登録ボタン --}}
        <a href="#" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createClassroomModal">新規登録</a> 

        {{-- クラス一覧 --}}
        <table class="table">
            <thead>
                <tr>
                    <th>クラス名</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($classrooms as $classroom)
                    <tr>
                        <td>{{ $classroom->name }}</td>
                        <td>
                            {{-- 編集ボタン --}}
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editClassroomModal" data-id="{{ $classroom->id }}" data-name="{{ $classroom->name }}">編集</a>
                            {{-- 削除ボタン --}}
                            <a href="#" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteClassroomModal" data-id="{{ $classroom->id }}" data-name="{{ $classroom->name }}">削除</a>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- ページネーション --}}
        <div class="d-flex justify-content-between">
            <div>
                {{ $classrooms->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 編集モーダルの設定
            const editModal = document.getElementById('editClassroomModal');
            editModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; // モーダルを開いたボタン
                const classroomId = button.getAttribute('data-id'); // クラスID
                const classroomName = button.getAttribute('data-name'); // クラス名
    
                // モーダル内のフォームと入力フィールド
                const form = editModal.querySelector('form[name="editClassroomForm"]');
                const input = form.querySelector('input[name="name"]');
    
                // フォームのaction属性を動的に設定
                form.action = `/admin/classrooms/${classroomId}`;
                input.value = classroomName;
            });
    
            // 削除モーダルの設定
            const deleteModal = document.getElementById('deleteClassroomModal');
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; // モーダルを開いたボタン
                const classroomId = button.getAttribute('data-id'); // クラスID
    
                // モーダル内のフォーム
                const form = deleteModal.querySelector('form[name="deleteClassroomForm"]');
    
                // フォームのaction属性を動的に設定
                form.action = `/admin/classrooms/${classroomId}`;
            });
        });
    </script>

@endsection
