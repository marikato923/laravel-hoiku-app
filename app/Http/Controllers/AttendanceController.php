<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Child;

class AttendanceController extends Controller
{
    public function markPresent(Request $request)
    {
        $child = Child::find($request->child_id);

        if (!$child) {
            return redirect()->route('home')->with('flash_message', '選択した園児が見つかりません');
         }

        Attendance::create([
        'child_id' => $child->id,
        'arrival_time' => now(),
        'pickup_name' => $request->pickup_name,
        'pickup_time' => $request->pickup_time,
        ]);

        return redirect()->route('home')->with('flash_message', $child->first_name . 'さんの登園を記録しました。');
    }

    public function markAbsent(Request $request)
    {
        $child = Child::find($request->child_id);

        if (!$child) {
            return redirect()->route('home')->with('flash_message', '選択した園児が見つかりません');
        }

        Attendance::create([
            'child_id' => $child->id,
            'departure_time' => now(),
        ]);

        return redirect()->route('home')->with('flash_message', $child->first_name . 'さんの降園を記録しました。');
    }
}