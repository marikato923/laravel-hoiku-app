<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Child;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?? now()->toDateString();
        $classroomId = $request->classroom_id ?? null; // クラスIDを取得、未分類（null）の場合を考慮
        $classrooms = Classroom::all();
        
        // 検索キーワードを取得
        $keyword = $request->keyword ?? ''; 
    
        // 園児の取得クエリ
        $childrenQuery = Child::query()
            ->when(!empty($classroomId), function ($query) use ($classroomId) {
                return $query->where('classroom_id', $classroomId);
            })
            ->when($classroomId === "", function ($query) { // クラス未分類（NULL）の園児を取得
                return $query->whereNull('classroom_id');
            })
            ->orderBy('last_name', 'asc');
    
        // 氏名・フリガナで検索
        if (!empty($keyword)) {
            $childrenQuery->where(function ($query) use ($keyword) {
                $query->where('last_name', 'like', "%$keyword%")
                      ->orWhere('first_name', 'like', "%$keyword%")
                      ->orWhere('kana_last_name', 'like', "%$keyword%")
                      ->orWhere('kana_first_name', 'like', "%$keyword%");
            });
        }
    
        $children = $childrenQuery->get();
    
        // 出席記録を取得（クラス未分類も含む）
        $attendances = Attendance::with('child')
            ->whereDate('arrival_time', $date)
            ->whereHas('child', function ($query) use ($classroomId) {
                if (!empty($classroomId)) {
                    $query->where('classroom_id', $classroomId);
                } elseif ($classroomId === "") {
                    $query->whereNull('classroom_id');
                }
            })
            ->get()
            ->groupBy('child_id');
    
        // 園児ごとに登園時間と降園時間をセット
        $groupedAttendances = [];
        foreach ($children as $child) {
            $childAttendances = $attendances->get($child->id, collect());
    
            $arrival_time = $childAttendances->first()->arrival_time ?? '未登録';
            $departure_time = $childAttendances->last()->departure_time ?? '未登録';
    
            $groupedAttendances[] = [
                'child' => $child,
                'arrival_time' => $arrival_time,
                'departure_time' => $departure_time,
            ];
        }
    
        return view('admin.attendance.index', compact('groupedAttendances', 'classrooms', 'classroomId', 'date', 'keyword'));
    }
    
    public function show($childId, Request $request)
    {
        $child = Child::findOrFail($childId);
    
        // 現在の月を取得（リクエストされた月があればそれを使用）
        $month = $request->input('month', now()->format('Y-m'));
    
        // 出席情報を月ごとに取得し、登園時間と降園時間をセットでグループ化
        $monthlyAttendances = Attendance::where('child_id', $childId)
            ->whereBetween('arrival_time', [
                \Carbon\Carbon::parse($month . '-01')->startOfMonth(),
                \Carbon\Carbon::parse($month . '-01')->endOfMonth(),
            ])
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($attendance) {
                return $attendance->created_at->format('Y-m-d'); // 日ごとにグループ化
            });
    
        // 月の日付リストを生成
        $startOfMonth = \Carbon\Carbon::parse($month . '-01')->startOfMonth();
        $endOfMonth = \Carbon\Carbon::parse($month . '-01')->endOfMonth();
        $dates = [];
        for ($date = $startOfMonth; $date <= $endOfMonth; $date->addDay()) {
            $dates[] = $date->copy();
        }
    
        // 登園時間と降園時間をセットにしたデータを準備
        $groupedMonthlyAttendances = [];
        $holidays = [
            '01-01', '01-13', '02-11', '02-23', '02-24', '03-20', '04-29',
            '05-03', '05-04', '05-05', '05-06', '07-21', '08-11', '09-15',
            '09-23', '10-13', '11-03', '11-23', '11-24',
        ];
    
        foreach ($dates as $date) {
            $formattedDate = $date->format('m-d');
            $isHoliday = in_array($formattedDate, $holidays);
            $isSunday = $date->isSunday();
    
            // 該当日付の出席データを取得（コレクションを保証）
            $attendance = $monthlyAttendances->get($date->format('Y-m-d'), collect());
    
            // 登園時間と降園時間を設定
            $arrival_time = (!$isHoliday && !$isSunday && $attendance->isNotEmpty())
                ? optional($attendance->first())->arrival_time ? \Carbon\Carbon::parse($attendance->first()->arrival_time)->format('H:i') : ''
                : ($isHoliday || $isSunday ? '' : '未登録');
            $departure_time = (!$isHoliday && !$isSunday && $attendance->isNotEmpty())
                ? optional($attendance->last())->departure_time ? \Carbon\Carbon::parse($attendance->last()->departure_time)->format('H:i') : ''
                : ($isHoliday || $isSunday ? '' : '未登録');
    
            $groupedMonthlyAttendances[$month][] = [
                'date' => $date->format('Y-m-d'),
                'arrival_time' => $arrival_time,
                'departure_time' => $departure_time,
            ];
        }
    
        return view('admin.attendance.show', compact('child', 'groupedMonthlyAttendances', 'month'));
    }
       
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->back()->with('success', '登園記録を削除しました。');
    }
}
