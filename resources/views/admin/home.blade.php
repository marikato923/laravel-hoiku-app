@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>管理者ホーム</h1>

    <div class="row">
        {{-- 保育園全体の在籍園児数 --}}
        <div class="col-md-4">
            <h4>保育園全体の在籍園児数</h4>
            <p>{{ $totalChildren }}人</p>
        </div>

        {{-- 今日の総出席園児数 --}}
        <div class="col-md-4">
            <h4>今日の総出席園児数</h4>
            <p>{{ $totalAttendances }}人</p>
        </div>

        {{-- 今日の総欠席園児数 --}}
        <div class="col-md-4">
            <h4>今日の総欠席園児数</h4>
            <p>{{ $totalAbsences }}人</p>
        </div>
    </div>

    <h4>クラスごとの人数と今日の出席人数・欠席人数</h4>
    <table class="table">
        <thead>
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
@endsection