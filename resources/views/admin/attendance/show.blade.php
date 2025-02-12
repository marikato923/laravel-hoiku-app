@extends('layouts.admin')

@section('breadcrumbs')
    <nav class="mb-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">ホーム</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.attendance.index') }}">出席状況一覧</a></li>
            <li class="breadcrumb-item active" aria-current="page">園児の出席状況</li>
        </ol>
    </nav>
@endsection

@section('content')
        <div class="container py-3 mb-5" style="max-width: 900px; margin: 0 auto;">
            {{-- ページタイトル --}}
            <h1 class="mb-5 text-center" style="text-align: center; width: 100%;"><span style="font-size: 0.8em;">出席状況 :</span> {{ $child->last_name }} {{ $child->first_name }}</h1>

            <div class="attendance-show-wrapper text-center" style="margin-left: auto; margin-right: auto; display: flex; justify-content: center; align-items: center; flex-direction: column; width: 100%; max-width: 600px;" style="display: flex; justify-content: center; align-items: center; flex-direction: column; width: 100%; max-width: 600px; margin: 0 auto; text-align: center;">
                {{-- 月切り替えと年月選択フォーム --}}
                <form method="GET" action="{{ route('admin.attendance.show', ['childId' => $child->id]) }}" class="attendance-switch-form d-flex justify-content-center align-items-center gap-4">
                    {{-- 前月ボタン --}}
                    @php
                        $currentMonth = \Carbon\Carbon::parse($month . '-01');
                        $previousMonth = $currentMonth->copy()->subMonth();
                        $nextMonth = $currentMonth->copy()->addMonth();
                    @endphp
                    <a href="{{ route('admin.attendance.show', ['childId' => $child->id, 'month' => $previousMonth->format('Y-m')]) }}" class="material-icons"
                        style="text-decoration: none;">chevron_left
                    </a>
                    
                    {{-- 年と月のプルダウン --}}
                    <div class="attendance-switch-select d-flex align-items-center gap-3">
                        <select name="month" id="yearMonth" class="attendance-switch-control" onchange="this.form.submit()">
                            @for ($y = now()->year - 5; $y <= now()->year + 5; $y++)
                                @for ($m = 1; $m <= 12; $m++)
                                    @php
                                        $formattedMonth = sprintf('%04d-%02d', $y, $m);
                                    @endphp
                                    <option value="{{ $formattedMonth }}" {{ $formattedMonth === $month ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($formattedMonth . '-01')->isoFormat('YYYY年M月') }}
                                    </option>
                                @endfor
                            @endfor
                        </select>
            <a href="{{ route('admin.attendance.show', ['childId' => $child->id, 'month' => now()->format('Y-m')]) }}" class="edit-link">今月</a>
                    </div>
                    
                    {{-- 翌月ボタン --}}
                    <a href="{{ route('admin.attendance.show', ['childId' => $child->id, 'month' => $nextMonth->format('Y-m')]) }}" class="material-icons"
                        style="text-decoration: none;">chevron_right
                    </a>
                </form>
            </div>

            {{-- 月別データ --}}
            <table class="table table-bordered text-center mt-4 mb-5" style="background-color: #f9f9f9;">
                <thead class="thead-dark">
                    <tr>
                        <th>日付</th>
                        <th>登園時間</th>
                        <th>降園時間</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $holidays = ['01-01', '01-13', '02-11', '02-23', '02-24', '03-20', '04-29',
                            '05-03', '05-04', '05-05', '05-06', '07-21', '08-11', '09-15',
                            '09-23', '10-13', '11-03', '11-23', '11-24'];
                        $startOfMonth = \Carbon\Carbon::parse($month . '-01')->startOfMonth();
                        $endOfMonth = \Carbon\Carbon::parse($month . '-01')->endOfMonth();
                        $attendanceList = collect($groupedMonthlyAttendances[$month] ?? []);
                    @endphp

                    @foreach (\Carbon\CarbonPeriod::create($startOfMonth, $endOfMonth) as $date)
                        @php
                            $formattedDate = $date->format('m-d');
                            $isHoliday = in_array($formattedDate, $holidays);
                            $isSunday = $date->isSunday();
                            $attendance = $attendanceList->firstWhere('date', $date->format('Y-m-d'));
                            $arrival_time = !$isHoliday && !$isSunday && $attendance ? $attendance['arrival_time'] : ($isHoliday || $isSunday ? '' : '未登録');
                            $departure_time = !$isHoliday && !$isSunday && $attendance ? $attendance['departure_time'] : ($isHoliday || $isSunday ? '' : '未登録');
                            $rowClass = empty($arrival_time) || empty($departure_time) ? ' class="table-secondary"' : '';
                        @endphp
                        <tr{!! $rowClass !!}>
                            <td>{{ $date->isoFormat('M月D日(ddd)') }}</td>
                            <td>{{ $arrival_time }}</td>
                            <td>{{ $departure_time }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div></div>
@endsection
