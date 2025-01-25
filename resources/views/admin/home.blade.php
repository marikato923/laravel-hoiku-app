@extends('layouts.admin')

@section('content')
<div class="container">
    {{-- 管理者ホームタイトル　--}}
    <h2 class="mb-4">管理者ホーム</h2>

    {{-- 保育園全体の概要 --}}
    <div class="row text-center mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">在籍園児数</h5>
                    <p class="card-text display-6">{{ $totalChildren }}人</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">今日の出席園児数</h5>
                    <p class="card-text display-6">{{ $totalAttendances }}人</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">今日の欠席園児数</h5>
                    <p class="card-text display-6">{{ $totalAbsences }}人</p>
                </div>
            </div>
        </div>
    </div>

    {{-- クラスごとの詳細 --}}
    <h4 class="mb-3">クラスごとの人数と出席状況</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>クラス名</th>
                    <th>在籍園児数</th>
                    <th>今日の出席園児数</th>
                    <th>今日の欠席園児数</th>
                </tr>
            </thead>
            <tbody>
                @foreach($classrooms as $classroom)
                    <tr>
                        <td>{{ $classroom->name }}</td>
                        <td>{{ $classroom->children_count }}人</td>
                        <td>{{ $classroom->attendances_count }}人</td>
                        <td>{{ $classroom->absences_count }}人</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection