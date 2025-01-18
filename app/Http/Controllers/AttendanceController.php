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
    
        return response()->json([
            'message' => implode("\n", $messages),
        ]);
    }

    public function markDeparture(Request $request)
    {
        $request->validate([
            'children' => 'required|array',
            'children.*' => 'exists:children,id',
        ]);
    
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

            $messages[] = "{$child->first_name}さんの登園を記録しました。";
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

            $status[$childId] = $attendance;
        }

        return response()->json($status);
    }
}