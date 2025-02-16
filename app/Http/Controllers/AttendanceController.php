<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Child;
use Carbon\Carbon;


class AttendanceController extends Controller
{
    public function markArrival(Request $request)
    {
        $request->validate([
            'children' => 'required|array',
            'children.*' => 'exists:children,id',
            'pickup_time' => 'nullable|date_format:H:i',
        ]);
    
        $messages = [];
        $arrivalTime = Carbon::now()->format('H:i'); // 打刻時刻の取得

            foreach ($request->children as $childId) {
                $child = Child::find($childId);

                // すでに登園済みかチェック
                $existingAttendance = Attendance::where('child_id', $child->id)
                ->whereNotNull('arrival_time')
                ->whereNull('departure_time')
                ->first();

                if ($existingAttendance) {
                    $messages[] = "{$child->first_name}さんはすでに打刻済みです。";
                    continue;
                }

                // 登園処理
                Attendance::create([
                    'child_id' => $child->id,
                    'arrival_time' => now(),
                    'pickup_time' => $request->pickup_time ? Carbon::parse(today()->format('Y-m-d') . ' ' . $request->pickup_time) : null,
                ]);

                $messages[] = "{$arrivalTime} {$child->first_name}さんの登園を記録しました。";
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
        $departureTime = Carbon::now()->format('H:i');
    
        foreach ($request->children as $childId) {
            $child = Child::find($childId);

            $existingDeparture = Attendance::where('child_id', $child->id)
            ->whereNotNull('departure_time')
            ->latest()
            ->first();

            if ($existingDeparture) {
                $messages[] = "{$child->first_name}さんは既に降園済みです。";
                continue;
            }

            Attendance::create([
                'child_id' => $child->id,
                'departure_time' => now(),
            ]);

            $messages[] = "{$departureTime} {$child->first_name}さんの降園を記録しました。";
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
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;

        $user = auth()->user();
    
        // 子供の情報を取得（兄弟データ含む）
        $children = $user->children;
    
        // 兄弟リストを取得
        $siblings = Child::where('user_id', $user->id)
            ->orderBy('birthdate')
            ->get();
    
        $attendances = Attendance::with('child') // 出席情報に子供の情報を一緒に取得
            ->whereIn('child_id', $children->pluck('id'))  // ユーザーの子供のみの出席記録
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at')
            ->get();
    
        // 出席情報を子供IDと日付でグループ化
        $groupedAttendances = $attendances->groupBy(function ($attendance) {
            return $attendance->child_id; // 子供ごとにグループ化
        })->map(function ($childAttendances) {
            return $childAttendances->groupBy(function ($attendance) {
                return $attendance->created_at->format('Y-m-d'); // 日付ごとにグループ化
            });
        });
    
        // 同じ日に登園と降園がある場合にまとめる
        $formattedAttendances = [];
        foreach ($groupedAttendances as $childId => $dates) {
            foreach ($dates as $date => $attendancesOnDate) {
                $arrival_time = $attendancesOnDate->first()->arrival_time
                    ? \Carbon\Carbon::parse($attendancesOnDate->first()->arrival_time)->format('H:i')
                    : '未登録';
                $departure_time = $attendancesOnDate->last()->departure_time
                    ? \Carbon\Carbon::parse($attendancesOnDate->last()->departure_time)->format('H:i')
                    : '未登録';
    
                $time_range = ($arrival_time != '未登録' && $departure_time != '未登録') 
                    ? $arrival_time . '〜' . $departure_time
                    : $arrival_time . '〜' . $departure_time;
    
                $formattedAttendances[$childId][$date][] = [
                    'child' => $attendancesOnDate->first()->child,
                    'time_range' => $time_range,
                ];
            }
        }
    
        // ビューに渡すデータ
        return view('attendance.show', [
            'groupedAttendances' => $formattedAttendances,
            'year' => $year,
            'month' => $month,
            'siblings' => $siblings,
        ]);
    }       
}