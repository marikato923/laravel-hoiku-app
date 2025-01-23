@extends('layouts.admin')

@section('content')

<div class="container">
    <h1>{{ $year }}年{{ $month }}月の出席状況</h1>

    {{-- 検索フォーム --}}
    <form method="GET" action="{{ route('admin.attendance.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="date">日付:</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ request('date', now()->toDateString()) }}">
            </div>

            <div class="col-md-3">
                <label for="period">表示範囲:</label>
                <select name="period" id="period" class="form-control">
                    <option value="daily" {{ request('period') == 'daily' ? 'selected' : '' }}>日別</option>
                    <option value="weekly" {{ request('period') == 'weekly' ? 'selected' : '' }}>週別</option>
                    <option value="monthly" {{ request('period') == 'monthly' ? 'selected' : '' }}>月別</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="classroom_id">クラス:</label>
                <select name="classroom_id" id="classroom_id" class="form-control">
                    <option value="">すべてのクラス</option>
                    @foreach ($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="child_name">園児名:</label>
                <input type="text" name="child_name" id="child_name" class="form-control" placeholder="氏名またはフリガナを入力" value="{{ request('child_name') }}">
            </div>
        </div>

        <div class="text-right mt-3">
            <button type="submit" class="btn btn-primary">検索</button>
            <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">リセット</a>
        </div>
    </form>

    {{-- 出席状況テーブル --}}
    @foreach ($groupedAttendances as $date => $attendanceGroup)
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h2 class="mb-0">{{ $date }}</h2>
            </div>

            <div class="card-body">
                @php
                    $groupedByClassroom = collect($attendanceGroup)->groupBy(fn($a) => $a['child']->classroom ? $a['child']->classroom->name : '未登録');
                @endphp

                @foreach ($groupedByClassroom as $classroomName => $attendances)
                    <h4 class="mt-3 font-weight-bold">{{ $classroomName }}</h4>
                    <table class="table table-striped table-bordered text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 50%;">園児名</th>
                                <th style="width: 50%;">登園状況</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (collect($attendances)->sortBy('child.last_name', SORT_LOCALE_STRING) as $attendance)
                                <tr>
                                    <td>{{ $attendance['child']->last_name }} {{ $attendance['child']->first_name }}</td>
                                    <td>
                                        {{ $attendance['arrival_time'] ?? '未登園' }} - {{ $attendance['departure_time'] ?? '未退園' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

@endsection