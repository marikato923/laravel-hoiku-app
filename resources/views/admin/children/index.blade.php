@extends('layouts.admin')

@section('content')
    <div class="container">
        <h1>園児一覧</h1>
        
        {{-- クラス選択と検索フォーム --}}
        <form method="GET" action="{{ route('admin.children.index') }}" class="mb-4">
            <div class="row">
                {{-- クラス選択プルダウン --}}
                <div class="col-md-4">
                    <select name="classroom_id" class="form-select" onchange="this.form.submit()">
                        <option value="" {{ $classroomId === null ? 'selected' : '' }}>未分類</option>
                        @foreach ($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ $classroomId == $classroom->id ? 'selected' : '' }}>
                                {{ $classroom->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- 検索フォーム --}}
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" name="keyword" class="form-control" placeholder="氏名・フリガナで検索" value="{{ $keyword }}">
                        <button class="btn btn-outline-secondary" type="submit">検索</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- 園児一覧 --}}
        <div class="row">
            @foreach ($children as $child)
                <div class="col-md-3 mb-4">
                    <div class="card text-center">
                        {{-- 画像 --}}
                        <a href="{{ route('admin.children.show', $child->id) }}" class="d-block">
                            <img 
                                src="{{ $child->img ? asset('storage/children/' . $child->img) : asset('storage/children/default.png') }}" 
                                alt="園児の画像" 
                                class="child-img-index"
                            >
                        </a>                                               
                        {{-- 名前 --}}
                        <div class="card-body">
                            <p class="card-text mb-0">{{ $child->last_name }} {{ $child->first_name }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ページネーション --}}
        <div class="d-flex justify-content-center">
            {{ $children->links() }}
        </div>
    </div>
@endsection
