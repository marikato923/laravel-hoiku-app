@extends('layouts.app')

@section('content')
<div class="container">
    {{-- 見出しと年度・月の選択フォーム --}}
    <div class="mb-4">
        <h2 class="mb-3 text-center">出席履歴</h2> <!-- タイトル中央寄せと下部余白調整 -->
        <form action="" method="get" class="d-flex justify-content-end gap-2 align-items-center"> <!-- フォームを右寄せ＆要素間隔調整 -->
            <select name="year" class="form-select w-auto">
                @for ($y = now()->year - 5; $y <= now()->year + 5; $y++)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}年</option>
                @endfor
            </select>
            <select name="month" class="form-select w-auto">
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ $m }}月</option>
                @endfor
            </select>
            <button type="submit" class="btn btn-primary">表示</button>
        </form>
    </div>

    {{-- タブ --}}
    <ul class="nav nav-tabs mb-4" id="siblingsTab" role="tablist" style="font-size: 1.2rem;">
        @foreach ($siblings as $index => $sibling)
            @php
                $themeColor = optional($sibling->classroom)->theme_color ?? '#e0e0e0'; // クラスのテーマカラー（デフォルト: 淡い色）
            @endphp
            <li class="nav-item me-3">
                <a class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                   id="tab-{{ $sibling->id }}" 
                   data-bs-toggle="tab" 
                   href="#content-{{ $sibling->id }}" 
                   role="tab" 
                   aria-controls="content-{{ $sibling->id }}" 
                   aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                   style="background-color: {{ $themeColor }}; color: #333333; border-radius: 15px 15px 0 0; padding: 10px 30px; transition: all 0.3s ease;">
                    {{ $sibling->first_name }}
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

                    // 祝日の指定
                    $holidays = [
                        '01-01', // 元日
                        '01-13', // 成人の日
                        '02-11', // 建国記念の日
                        '02-23', // 天皇誕生日
                        '02-24', // 振替休日
                        '03-20', // 春分の日
                        '04-29', // 昭和の日
                        '05-03', // 憲法記念日
                        '05-04', // みどりの日
                        '05-05', // こどもの日
                        '05-06', // 振替休日
                        '07-21', // 海の日
                        '08-11', // 山の日
                        '09-15', // 敬老の日
                        '09-23', // 秋分の日
                        '10-13', // スポーツの日
                        '11-03', // 文化の日
                        '11-23', // 勤労感謝の日
                        '11-24', // 振替休日
                    ];
                @endphp

                {{-- 前月・次月ボタン --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="?year={{ $prevMonth->year }}&month={{ $prevMonth->month }}" class="btn btn-secondary">前月</a>
                    <h4 class="mb-0">{{ $year }}年{{ $month }}月</h4>
                    <a href="?year={{ $nextMonth->year }}&month={{ $nextMonth->month }}" class="btn btn-secondary">次月</a>
                </div>

                {{-- カレンダー --}}
                <div class="calendar">
                    <div class="calendar-header">
                        @foreach (["日", "月", "火", "水", "木", "金", "土"] as $day)
                            <div class="calendar-cell font-weight-bold text-center">{{ $day }}</div>
                        @endforeach
                    </div>

                    <div class="calendar-body">
                        @php
                            $startOfMonth = \Carbon\Carbon::create($year, $month, 1);
                            $endOfMonth = $startOfMonth->copy()->endOfMonth();
                            $startDayOfWeek = $startOfMonth->dayOfWeek;
                            $daysInMonth = $endOfMonth->day;
                            $totalCells = $startDayOfWeek + $daysInMonth;
                            $weekdays = ["日", "月", "火", "水", "木", "金", "土"];
                        @endphp
                    
                        {{-- 空白セル --}}
                        @for ($i = 0; $i < $startDayOfWeek; $i++)
                            <div class="calendar-cell empty">
                                <div class="date">&nbsp;</div>
                            </div>
                        @endfor
                    
                        {{-- 日付セル --}}
                        @for ($day = 1; $day <= $daysInMonth; $day++)
                            @php
                                $currentDate = $startOfMonth->copy()->addDays($day - 1);
                                $weekdayIndex = $currentDate->dayOfWeek;
                                $isHoliday = in_array($currentDate->format('m-d'), $holidays);
                                $isSunday = $currentDate->dayOfWeek === 0;
                            @endphp
                            <div class="calendar-cell">
                                <div class="date-container">
                                    <div class="weekday {{ $isHoliday || $isSunday ? 'holiday' : '' }}">
                                        {{ $weekdays[$weekdayIndex] }}
                                    </div>
                                    <div class="date {{ $isHoliday || $isSunday ? 'holiday' : '' }}">
                                        {{ $day }}
                                    </div>
                                </div>
                                @if (isset($groupedAttendances[$sibling->id][$currentDate->format('Y-m-d')]))
                                    @foreach ($groupedAttendances[$sibling->id][$currentDate->format('Y-m-d')] as $attendance)
                                        <div class="attendance-record">
                                            <small class="time-range">
                                                {{ str_replace('〜', "\n〜\n", $attendance['time_range']) }}
                                            </small>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        @endfor
                    
                        {{-- 残りの空白セル --}}
                        @for ($i = 0; $i < (7 - $totalCells % 7) % 7; $i++)
                            <div class="calendar-cell empty">
                                <div class="date">&nbsp;</div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
