@extends('layouts.admin')

@section('content')
<div class="container pt-3">
    {{-- 管理者ホームタイトル　--}}
    <div class="mb-4 text-center">
        @php
            use Carbon\Carbon;
            $formattedDate = Carbon::parse($today)->isoFormat('YYYY年M月D日(ddd)');
        @endphp

        <h1 class="mb-5 text-center" style="text-align: center; width: 100%;">
            <span style="font-size: 0.8em;">出席状況 :</span> {{ $formattedDate }}
        </h1>
    </div>

    {{-- 保育園全体の概要 --}}
    <div class="row text-center mb-4 pt-1">
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
                    <h5 class="card-title">今日の出席数</h5>
                    <p class="card-text display-6">{{ $totalAttendances }}人</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">今日の欠席数</h5>
                    <p class="card-text display-6">{{ $totalAbsences }}人</p>
                </div>
            </div>
        </div>
    </div>

    {{-- クラスごとの詳細 --}}
    <h5 class="mb-3 pt-4">クラス別の出席状況</h5>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                <tr>
                    <th>クラス名</th>
                    <th>在籍園児数</th>
                    <th>出席数</th>
                    <th>欠席数</th>
                </tr>
            </thead>
            <tbody>
                @foreach($classrooms as $classroom)
                    <tr>
                        <td>
                            <a href="{{ route('admin.attendance.index', ['classroom_id' => $classroom->id, 'date' => now()->toDateString()]) }}"
                                class="admin-home-link">
                                {{ $classroom->name }}
                            </a>
                        </td>
                        <td>{{ $classroom->children_count }}人</td>
                        <td>{{ $classroom->attendances_count }}人</td>
                        <td>{{ $classroom->absences_count }}人</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
.admin-home-link {
    color: rgb(200, 100, 100);
    text-decoration: none;
    transition: color 0.3s ease;
}

.admin-home-link:hover {
    color: rgb(255, 175, 175);
}
</style>

@endsection
