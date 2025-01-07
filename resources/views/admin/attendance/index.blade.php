@extends('layouts.admin')

@section('content')

<div class="container">

    <h1>{{ $year }}年{{ $month }}月の出席状況</h1>

    {{--  検索フォーム --}}
    <form method="GET" action="{{ route('admin.attendance.index') }}">
        <div class="form-group">
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

        <div class="form-group">
            <label for="child_name">園児名:</label>
            <input type="text" name="child_name" id="child_name" class="form-control" value="{{ request('child_name') }}" placeholder="園児名で検索">
        </div>

        <button type="submit" class="btn btn-primary">検索</button>
    </form>

    {{-- 出席状況テーブル --}}
    @foreach ($groupedAttendances as $date => $attendanceGroup)
        <h2>{{ $date }}</h2>

        @foreach ($attendanceGroup as $attendance)
            <h3>{{ $attendance['child']->classroom->name }}</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>園児名</th>
                        <th>登園状況</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $attendance['child']->last_name }} {{ $attendance['child']->first_name }}</td>
                        <td>{{ $attendance['arrival_time'] }} - {{ $attendance['departure_time'] }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    @endforeach

</div>

@endsection