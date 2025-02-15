<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSettingsController extends Controller
{
    public function updateNotificationPreference(Request $request)
    {
        $user = Auth::user();

        $user->notification_preference = $request->has('notification_preference');
        $user->save();

        return redirect()->back()->with('success', '通知設定を更新しました！');
    } 
}
