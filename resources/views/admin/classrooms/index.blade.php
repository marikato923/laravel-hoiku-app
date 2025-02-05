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
                        <input type="text" class="form-control" name="name" placeholder="クラス名">
                        <select name="age_group" class="form-control mt-3">
                            <option value="0歳児クラス">0歳児クラス</option>
                            <option value="1歳児クラス">1歳児クラス</option>
                            <option value="2歳児クラス">2歳児クラス</option>
                            <option value="3歳児クラス">3歳児クラス</option>
                            <option value="4歳児クラス">4歳児クラス</option>
                            <option value="5歳児クラス">5歳児クラス</option>
                        </select>
                        <label class="mt-3" for="theme_color">テーマカラー</label>
                        <input type="color" class="form-control mt-2" name="theme_color" id="theme_color" value="#ffffff">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn register-btn shadow-sm">登録</button>
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
                        <select name="age_group" class="form-control mt-3">
                            <option value="0歳児クラス"> {{ isset($classroom) && $classroom->age_group  == '0歳児クラス' ? 'selected' : '' }}0歳児クラス</option>
                            <option value="1歳児クラス"> {{ isset($classroom) && $classroom->age_group  == '1歳児クラス' ? 'selected' : '' }}1歳児クラス</option>
                            <option value="2歳児クラス"> {{ isset($classroom) && $classroom->age_group  == '2歳児クラス' ? 'selected' : '' }}2歳児クラス</option>
                            <option value="3歳児クラス"> {{ isset($classroom) && $classroom->age_group  == '3歳児クラス' ? 'selected' : '' }}3歳児クラス</option>
                            <option value="4歳児クラス"> {{ isset($classroom) && $classroom->age_group  == '4歳児クラス' ? 'selected' : '' }}4歳児クラス</option>
                            <option value="5歳児クラス"> {{ isset($classroom) && $classroom->age_group  == '5歳児クラス' ? 'selected' : '' }}5歳児クラス</option>
                        </select>
                        <label class="mt-3" for="theme_color">テーマカラー</label>
                        <input type="color" class="form-control mt-2" name="theme_color" id="theme_color" value="#ffffff">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn register-btn shadow-sm">更新</button>
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
                        <button type="submit" class="btn delete-btn shadow-sm">削除</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@section('breadcrumbs')
    <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">ホーム</a></li>
            <li class="breadcrumb-item active" aria-current="page">クラス一覧</li>
        </ol>
    </nav>
@endsection

@section('content')

            <div class="container d-flex flex-column align-items-center justify-content-center py-2">
                <h1 class="mb-4">クラス一覧</h1>
                
                {{-- 検索フォーム --}}
                <form method="GET" action="{{ route('admin.classrooms.index') }}" class="mb-2 w-100 d-flex justify-content-center">
                    <div class="input-group w-50">
                        <input type="text" name="keyword" class="form-control" placeholder="クラス名で検索" value="{{ old('keyword', $keyword) }}">
                        <button class="btn attendance-btn" type="submit">検索</button>
                    </div>
                </form>

                {{-- クラス一覧 --}}
                <table class="table text-center mt-2">
                    <thead>
                        <tr>
                            <th>クラス名</th>
                            <th>年齢層</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classrooms as $classroom)
                            <tr style="height: 50px;"> 
                                <td class="text-start ps-3">
                                    <span 
                                        style="display: inline-block; width: 15px; height: 15px; background-color: {{ $classroom->theme_color ?? '#ffffff' }}; 
                                        margin-left: 20px; border: 1px solid #ccc; border-radius: 50%;">
                                    </span>
                                    {{ $classroom->name }}
                                </td>                                
                                <td style="padding: 12px 8px;">{{ $classroom->age_group }}</td>
                                <td style="padding: 12px 8px;">
                                    <a href="#" class="my-2 register-link mx-2" data-bs-toggle="modal" data-bs-target="#editClassroomModal"
                                    data-id="{{ $classroom->id }}"
                                    data-name="{{ $classroom->name }}"
                                    data-age-group="{{ $classroom->age_group }}"
                                    data-theme-color="{{ $classroom->theme_color ?? '#ffffff' }}">
                                    編集
                                    </a>
                                    <a href="#" class="my-2 delete-link mx-2" data-bs-toggle="modal" data-bs-target="#deleteClassroomModal" 
                                    data-id="{{ $classroom->id }}">
                                    削除
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                 
                {{-- 新規登録ボタン --}}
                <div class="w-100 d-flex justify-content-between mt-3 mb-2">
                    <div></div>
                    <a href="#" class="btn register-btn me-5" data-bs-toggle="modal" data-bs-target="#createClassroomModal">新規登録</a>
                </div>
        
                {{-- ページネーション --}}
                <div class="mt-3">
                    {{ $classrooms->links() }}
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
            const classroomAgeGroup = button.getAttribute('data-age-group'); // 年齢層
            const classroomThemeColor = button.getAttribute('data-theme-color'); // テーマカラー

            // モーダル内のフォームと入力フィールド
            const form = editModal.querySelector('form[name="editClassroomForm"]');
            const nameInput = form.querySelector('input[name="name"]');
            const ageGroupSelect = form.querySelector('select[name="age_group"]');
            const themeColorInput = form.querySelector('input[name="theme_color"]');

            // フォームのaction属性を動的に設定
            form.action = `/admin/classrooms/${classroomId}`;
            nameInput.value = classroomName;
            ageGroupSelect.value = classroomAgeGroup;
            themeColorInput.value = classroomThemeColor;
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
