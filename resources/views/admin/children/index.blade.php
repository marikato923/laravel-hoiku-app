@extends('layouts.admin')

@section('breadcrumbs')
    <nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">ホーム</a></li>
            <li class="breadcrumb-item active" aria-current="page">園児一覧</li>
        </ol>
    </nav>
@endsection

@section('content')
        <div class="container py-1 mb-5" style="max-width: 900px; margin: 0 auto;">
                {{-- 上部のタイトル --}}
                <div class="text-center mb-4">
                    <h1 class="mb-3" style="font-weight: normal;">園児一覧</h1>
                </div>
                {{-- フィルタリングと検索エリア --}}
                <div class="mb-4 d-flex flex-column align-items-center gap-3">
                    {{-- クラス選択・検索フォーム・チェックボックスのコンテナ --}}
                    <form method="GET" action="{{ route('admin.children.index') }}" class="w-100">
                        <div class="d-flex flex-column align-items-center gap-3"
                            style="width: 500px; margin: 0 auto; position: relative;">
                            <div class="w-100 d-flex justify-content-end mb-2">
                                <a href="{{ route('admin.children.create') }}" class="btn text-white register-btn">新規登録</a>
                            </div>
                            
                            {{-- クラス選択プルダウン --}}
                            <div class="w-100">
                                <label for="classroomSelect" class="form-label">クラス :</label>
                                <select id="classroomSelect" name="classroom_id" class="form-select w-100" onchange="this.form.submit()">
                                    <option value="" {{ ($classroomId === null || $classroomId === "")? 'selected' : '' }}>未分類</option>
                                    @foreach ($classrooms as $classroom)
                                        <option value="{{ $classroom->id }}" {{ $classroomId == $classroom->id ? 'selected' : '' }}>
                                            {{ $classroom->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 検索フォーム --}}
                            <div class="w-100">
                                <div class="input-group w-100">
                                    <input 
                                        type="text" 
                                        name="keyword" 
                                        class="form-control" 
                                        placeholder="氏名・フリガナで検索" 
                                        value="{{ $keyword }}">
                                    <button class="btn attendance-btn" type="submit">検索</button>
                                </div>
                            </div>

                            {{-- 未承認チェックボックス --}}
                            <div class="d-flex align-items-center w-100">
                                <input 
                                    type="checkbox" 
                                    id="pendingOnly" 
                                    name="pending_only" 
                                    class="form-check-input me-2" 
                                    value="1" 
                                    {{ request('pending_only') ? 'checked' : '' }} 
                                    onchange="this.form.submit()"
                                >
                                <label for="pendingOnly" class="form-check-label">未承認の園児のみ表示</label>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- 園児一覧 --}}
                <div class="row justify-content-start">
                    @forelse ($children as $child)
                        <div class="col-md-3 mb-4 d-flex">
                            <div class="card text-center card-children-index">
                                {{-- 画像 --}}
                                @php
                                    $themeColor = optional($child->classroom)->theme_color ?? '#ccc'; // クラスのテーマカラー（デフォルト：灰色）
                                @endphp
                                <a href="{{ route('admin.children.show', $child->id) }}" class="d-block text-start">
                                    <div class="child-img-wrapper" style="border-color: {{ $themeColor }};">
                                        <img 
                                            src="{{ $child->img ? asset('storage/children/' . $child->img) : asset('storage/children/default.png') }}" 
                                            alt="園児の画像" 
                                            class="child-img-index"
                                        >
                                    </div>
                                </a>
                                                    
                                {{-- 名前 --}}
                                <div class="card-body">
                                    <p class="card-text mb-0">{{ $child->last_name }} {{ $child->first_name }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center text-muted">該当する園児がいません。</p>
                        </div>
                    @endforelse
                </div>

                {{-- ページネーション --}}
                <div class="d-flex justify-content-center kodomolog-pagination mt-2 mb-5">
                    {{ $children->links() }}
                </div>
            </div>
        <div class="mb-4">  
    </div>
@endsection
