<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PickupReminderMail;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function __construct()
    {
        // CLI（コマンドライン実行時）は認証不要にする
        if (!app()->runningInConsole()) {
            $this->middleware('auth:admin'); // Web経由では管理者のみアクセス可能
        }
    }

    public function sendPickupReminders()
    {
        Log::info('sendPickupReminders メソッドが実行されました。');

        $attendances = Attendance::whereNotNull('pickup_time')
            ->where('pickup_time', '>=', now())
            ->where('pickup_time', '<=', now()->addHour())
            ->get();

        foreach ($attendances as $attendance) {
            if ($attendance->shouldSendReminder()) {
                $child = $attendance->child;
                $parent = $child->user;

                Mail::to($parent->email)->send(new PickupReminderMail($child, $attendance->pickup_time));

                Log::info("メールを送信しました: {$parent->email}");
            }
        }
        
        return response()->json(['message' => '通知を送信しました'], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
