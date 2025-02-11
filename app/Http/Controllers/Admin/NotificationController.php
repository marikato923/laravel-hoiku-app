<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PickupReminderMail;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin'); // 管理者のみアクセス可
    }

    public function sendPickupReminders()
    {
        $attendances = Attendance::whereNotNull('pickup_time')->get();

        foreach ($attendances as $attendance) {
            if ($attendance->shouldSendReminder()) {
                $child = $attendance->child;
                $parent = $child->user;

                Mail::to($parent->email)->send(new PickupReminderMail($child, $attendance->pickup_time));
            }
        }
        
        return response()->json(['message' => '通知を送信しました'], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
