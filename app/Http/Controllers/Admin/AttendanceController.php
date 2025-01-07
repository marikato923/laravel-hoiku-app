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
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;

        // クラスIDと園児名を個別にリクエストから取得
        $classroomId = $request->class_id;
        $childLastName = $request->child_last_name;
        $childFirstName = $request->child_first_name;
        $childLastKanaName = $request->child_last_kana_name;
        $childFirstKanaName = $request->child_first_kana_name;

        $classrooms = Classroom::all();

        // 出席情報を取得し、年と月で絞り込み
        $attendances = Attendance::with('child')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at');
        
        // クラスで絞り込み
        if ($classroomId) {
            $attendances->whereHas('child', function ($query) use ($classroomId) {
                $query->where('classroom_id', $classroomId);
            });
        }

        // 園児名（姓名）で絞り込み（部分一致）
        if ($childLastName || $childFirstName || $childLastKanaName || $childFirstKanaName) {
            $attendances->whereHas('child', function ($query) use (
                $childLastName, 
                $childFirstName, 
                $childLastKanaName, 
                $childFirstKanaName
            ) {
                $query->where(function ($query) use (
                    $childLastName, 
                    $childFirstName, 
                    $childLastKanaName, 
                    $childFirstKanaName
                ) {
                    if ($childLastName) {
                        $query->where('last_name', 'like', "%{$childLastName}%");
                    }
                    if ($childFirstName) {
                        $query->where('first_name', 'like', "%{$childFirstName}%");
                    }
                    if ($childLastKanaName) {
                        $query->where('last_kana_name', 'like', "%{$childLastKanaName}%");
                    }
                    if ($childFirstKanaName) {
                        $query->where('first_kana_name', 'like', "%{$childFirstKanaName}%");
                    }
                });
            });
        }

        // 出席情報を取得
        $attendances = $attendances->get()
            ->groupBy(function($attendance) {
                return $attendance->created_at->format('Y-m-d');
            });

        // 同じ日に登園と降園がある場合にまとめるために、日付ごとにさらにグループ化
        $groupedAttendances = [];
        foreach ($attendances as $date => $attendanceGroup) {
            $attendanceByChild = $attendanceGroup->groupBy('child_id');
            foreach ($attendanceByChild as $childAttendances) {
                // 登園時間と退園時間を取得
                $child = $childAttendances->first()->child;
                $arrival_time = $childAttendances->first()->arrival_time
                    ? \Carbon\Carbon::parse($childAttendances->first()->arrival_time)->format('H:i')
                    : '未登録';
            
                $departure_time = $childAttendances->last()->departure_time
                    ? \Carbon\Carbon::parse($childAttendances->last()->departure_time)->format('H:i')
                    : '未登録';
    
                // 日付と園児ごとに整理
                $groupedAttendances[$date][] = [
                    'child' => $child,
                    'arrival_time' => $arrival_time,
                    'departure_time' => $departure_time,
                ];
            }
        }

        // ビューに渡すデータを修正
        return view('admin.attendance.index', compact('groupedAttendances', 'year', 'month', 'classrooms'));
    }

    public function dailyAttendance()
    {
        $date = now()->format('Y-m-d');
        $attendances = Attendance::whereDate('created_at', $date)->get();

        return view('admin.attendance.daily', compact('attendances', 'date'));
    }
}
