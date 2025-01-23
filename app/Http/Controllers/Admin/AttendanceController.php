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
        // デフォルトで当日を設定
        $date = $request->date ?? now()->toDateString();
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;
        $period = $request->period ?? 'daily';
    
        // 検索条件
        $classroomId = $request->classroom_id;
        $childLastName = $request->child_last_name;
        $childFirstName = $request->child_first_name;
        $childLastKanaName = $request->child_last_kana_name;
        $childFirstKanaName = $request->child_first_kana_name;
    
        $classrooms = Classroom::all();
    
        // 期間に応じた検索条件の適用
        $attendances = Attendance::with('child')->orderBy('created_at');
    
        if ($period === 'daily') {
            $attendances->whereDate('created_at', $date);
        } elseif ($period === 'weekly') {
            $startOfWeek = \Carbon\Carbon::parse($date)->startOfWeek();
            $endOfWeek = \Carbon\Carbon::parse($date)->endOfWeek();
            $attendances->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
        } elseif ($period === 'monthly') {
            $attendances->whereMonth('created_at', $month)->whereYear('created_at', $year);
        }
    
        // クラスで絞り込み
        if ($classroomId) {
            $attendances->whereHas('child', function ($query) use ($classroomId) {
                $query->where('classroom_id', $classroomId);
            });
        }
    
        // 園児名（姓名）で絞り込み（部分一致）
        if ($childName = request('child_name')) {
            $attendances->whereHas('child', function ($query) use ($childName) {
                $query->where(function ($q) use ($childName) {
                    $q->where('last_name', 'like', "%{$childName}%")
                      ->orWhere('first_name', 'like', "%{$childName}%")
                      ->orWhere('last_kana_name', 'like', "%{$childName}%")
                      ->orWhere('first_kana_name', 'like', "%{$childName}%")
                      ->orWhereRaw("CONCAT(last_name, first_name) LIKE ?", ["%{$childName}%"])
                      ->orWhereRaw("CONCAT(last_kana_name, first_kana_name) LIKE ?", ["%{$childName}%"])
                      ->orWhere('last_name', $childName)
                      ->orWhere('first_name', $childName)
                      ->orWhere('last_kana_name', $childName)
                      ->orWhere('first_kana_name', $childName)
                      ->orWhereRaw("CONCAT(last_name, first_name) = ?", [$childName])
                      ->orWhereRaw("CONCAT(last_kana_name, first_kana_name) = ?", [$childName]);
                });
            });
        }
    
        // 出席情報を取得し、日付ごとにグループ化
        $attendances = $attendances->get()->groupBy(function($attendance) {
            return $attendance->created_at->format('Y-m-d');
        });
    
        // 登園時間と降園時間をグループ化
        $groupedAttendances = [];
        foreach ($attendances as $date => $attendanceGroup) {
            $attendanceByChild = $attendanceGroup->groupBy('child_id');
            foreach ($attendanceByChild as $childAttendances) {
                $child = $childAttendances->first()->child;
                $arrival_time = $childAttendances->first()->arrival_time
                    ? \Carbon\Carbon::parse($childAttendances->first()->arrival_time)->format('H:i')
                    : '未登録';
            
                $departure_time = $childAttendances->last()->departure_time
                    ? \Carbon\Carbon::parse($childAttendances->last()->departure_time)->format('H:i')
                    : '未登録';
    
                $groupedAttendances[$date][] = [
                    'child' => $child,
                    'arrival_time' => $arrival_time,
                    'departure_time' => $departure_time,
                ];
            }
        }
    
        return view('admin.attendance.index', compact('groupedAttendances', 'year', 'month', 'date', 'period', 'classrooms'));
    }

    public function dailyAttendance()
    {
        $date = now()->format('Y-m-d');
        $attendances = Attendance::whereDate('created_at', $date)->get();

        return view('admin.attendance.daily', compact('attendances', 'date'));
    }
}
