@extends('layouts.app')

@section('content')
<div class="container">
    {{-- 見出しとプルダウンメニュー --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">出席履歴</h2>
        
        {{-- プルダウンメニュー --}}
        <form action="" method="get" class="d-flex align-items-center">
            <select name="year" class="form-control mr-2 custom-select">
                @for ($y = now()->year - 5; $y <= now()->year + 5; $y++)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}年</option>
                @endfor
            </select>
            <select name="month" class="form-control mr-2 custom-select">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ $m }}月</option>
                @endfor
            </select>
            <button type="submit" class="btn btn-month mr-2">表示</button>
            <a href="?year={{ now()->year }}&month={{ now()->month }}" class="btn btn-month">現在に戻る</a>
        </form>
    </div>

    {{-- タブ --}}
    <ul class="nav nav-tabs mb-4" id="siblingsTab" role="tablist">
        @foreach ($siblings as $index => $sibling)
            <li class="nav-item">
                <a class="nav-link {{ $index === 0 ? 'active' : '' }}" id="tab-{{ $sibling->id }}" 
                   data-bs-toggle="tab" href="#content-{{ $sibling->id }}" role="tab" 
                   aria-controls="content-{{ $sibling->id }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                    {{ $sibling->last_name }} {{ $sibling->first_name }}
                </a>
            </li>
        @endforeach
    </ul>

    {{-- タブの内容 --}}
    <div class="tab-content" id="siblingsTabContent">
        @foreach ($siblings as $index => $sibling)
            <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="content-{{ $sibling->id }}" 
                 role="tabpanel" aria-labelledby="tab-{{ $sibling->id }}">
                @php
                    $currentDate = \Carbon\Carbon::create($year, $month, 1);
                    $prevMonth = $currentDate->copy()->subMonth();
                    $nextMonth = $currentDate->copy()->addMonth();
                @endphp

                {{-- 前月・次月ボタン --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="?year={{ $prevMonth->year }}&month={{ $prevMonth->month }}" class="btn btn-month">前月</a>
                    <h4 class="mb-0">{{ $year }}年{{ $month }}月</h4>
                    <a href="?year={{ $nextMonth->year }}&month={{ $nextMonth->month }}" class="btn btn-month">次月</a>
                </div>

                {{-- カレンダー --}}
                <div class="calendar">
                    <div class="calendar-header d-flex">
                        @foreach (["日", "月", "火", "水", "木", "金", "土"] as $day)
                            <div class="calendar-cell font-weight-bold text-center">{{ $day }}</div>
                        @endforeach
                    </div>

                    <div class="calendar-body d-flex flex-wrap">
                        @php
                            $startOfMonth = \Carbon\Carbon::create($year, $month, 1);
                            $endOfMonth = $startOfMonth->copy()->endOfMonth();
                            $startDayOfWeek = $startOfMonth->dayOfWeek;
                            $daysInMonth = $endOfMonth->day;
                            $totalCells = $startDayOfWeek + $daysInMonth;
                        @endphp

                        {{-- 空白セル --}}
                        @for ($i = 0; $i < $startDayOfWeek; $i++)
                            <div class="calendar-cell empty"></div>
                        @endfor

                        {{-- 日付セル --}}
                        @for ($day = 1; $day <= $daysInMonth; $day++)
                            @php
                                $currentDate = $startOfMonth->copy()->addDays($day - 1)->format('Y-m-d');
                            @endphp
                            <div class="calendar-cell">
                                <div class="date">{{ $day }}</div>
                                @if (isset($groupedAttendances[$sibling->id][$currentDate]))
                                    @foreach ($groupedAttendances[$sibling->id][$currentDate] as $attendance)
                                        <div class="attendance-record">
                                            <span>{{ $attendance['child']->last_name }} {{ $attendance['child']->first_name }}</span><br>
                                            <small>{{ $attendance['time_range'] }}</small>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endfor

                        {{-- 残りの空白セル --}}
                        @for ($i = 0; $i < (7 - $totalCells % 7) % 7; $i++)
                            <div class="calendar-cell empty"></div>
                        @endfor
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
