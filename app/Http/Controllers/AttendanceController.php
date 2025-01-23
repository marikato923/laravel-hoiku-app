<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Child;

class AttendanceController extends Controller
{
    public function markArrival(Request $request)
    {
        $request->validate([
            'children' => 'required|array',
            'children.*' => 'exists:children,id',
            'pickup_name' => 'string|max:255',
            'pickup_time' => 'date_format:H:i',
        ]);
    
        $messages = [];

        try {
            foreach ($request->children as $childId) {
                $child = Child::find($childId);
                Attendance::create([
                    'child_id' => $child->id,
                    'arrival_time' => now(),
                    'pickup_name' => $request->pickup_name,
                    'pickup_time' => $request->pickup_time,
                ]);

                $messages[] = "{$child->first_name}さんの登園を記録しました。";
            } 
        } catch (\Exception $e) {
            return response()->json([
                'meesage' => "登園の記録に失敗しました: {$e->getMessage()}"
            ], 500);
        }
    
        return response()->json([
            'message' => implode('<br>', $messages),
        ]);
    }

    public function markDeparture(Request $request)
    {
        $request->validate([
            'children' => 'required|array',
            'children.*' => 'exists:children,id',
        ]);

        $messages = [];
    
        foreach ($request->children as $childId) {
            $child = Child::find($childId);

            if (!$child) {
                return response()->json([
                    'message' => '無効な子供が選択されました。',
                ], 400);
            }

            Attendance::create([
                'child_id' => $child->id,
                'departure_time' => now(),
            ]);

            $messages[] = "{$child->first_name}さんの降園を記録しました。";
        }

        return response()->json([
            'messages' => $messages,
        ]);
    }


    public function checkAttendanceStatus(Request $request)
    {
        $request->validate([
            'children' => 'required|array',
            'children.*' => 'exists:children,id',
        ]);

        $status = [];

        foreach ($request->children as $childId) {
            $attendance = Attendance::where('child_id', $childId)
                ->whereDate('arrival_time', today())
                ->exists();

            $status[$childId] = $attendance ? true : false;
        }

        return response()->json($status);
    }

    public function show(Request $request)
    {
        // 年と月の取得（デフォルトで現在年、月）
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;
    
        // ログインユーザー情報（ユーザーは親だと仮定）
        $user = auth()->user();
    
        // 子供のIDまたは情報を取得
        $children = $user->children;  // 仮に親（ユーザー）に関連付けられた子供の情報を取得する
    
        // 出席情報を取得し、年と月で絞り込み
        $attendances = Attendance::with('child') // 出席情報に子供の情報を一緒に取得
            ->whereIn('child_id', $children->pluck('id'))  // ユーザーの子供のみの出席記録
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at');
    
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
                
                // 複数の降園時間がある場合、最後の降園時間を取得
                $departure_time = $childAttendances->last()->departure_time
                    ? \Carbon\Carbon::parse($childAttendances->last()->departure_time)->format('H:i')
                    : '未登録';
        
                // 登園と降園時間を1行にまとめる
                $time_range = ($arrival_time != '未登録' && $departure_time != '未登録') 
                    ? $arrival_time . '〜' . $departure_time
                    : $arrival_time . '〜' . $departure_time;
                
                // 日付と園児ごとに整理
                $groupedAttendances[$date][] = [
                    'child' => $child,
                    'time_range' => $time_range,
                ];
            }
        }
    
        // ビューに渡すデータを修正
        return view('attendance.show', compact('groupedAttendances', 'year', 'month'));
    }
}