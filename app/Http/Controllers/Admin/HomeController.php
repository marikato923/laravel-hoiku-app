<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\Attendance;
use App\Models\Classroom;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {

        $today = Carbon::today();

        $totalChildren = Child::count();

        // 今日の出席園児数
        $totalAttendances = Attendance::whereDate('created_at', $today)
            ->distinct('child_id')
            ->whereNotNull('arrival_time')
            ->count();

        // 今日の欠席園児数
        $totalAbsences = Attendance::whereDate('created_at', $today)
            ->distinct('child_id')
            ->whereNull('arrival_time')
            ->count();

        // クラスごと
        $classrooms = Classroom::withCount('children')
            ->withCount(['attendances' => function($query) use ($today) {
                $query->whereDate('attendances.created_at', $today)
                    ->whereNotNull('arrival_time');
            }])
            ->withCount(['absences' => function($query) use ($today) {
                $query->whereDate('attendances.created_at', $today)
                    ->whereNull('arrival_time');
            }])
            ->get();

        return view('admin.home', compact('totalChildren', 'totalAttendances', 'totalAbsences', 'classrooms'));
    }
}
