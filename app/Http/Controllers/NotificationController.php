<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class NotificationController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Received OneSignal ID request:', $request->all());

        // 認証チェック
        $user = Auth::user();
        if (!$user) {
            Log::error('Unauthorized request: User not authenticated.');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // `player_id` がリクエストに含まれているかチェック
        if (!$request->has('player_id') || empty($request->player_id)) {
            Log::error('Missing OneSignal Player ID in request.');
            return response()->json(['error' => 'Missing player_id'], 400);
        }

        try {
            // **エラーデバッグ用**
            Log::info('Saving OneSignal ID:', ['player_id' => $request->player_id]);

            // OneSignal ID を保存（カラム名を `onesignal_id` に変更）
            $user->onesignal_id = $request->player_id;
            $user->save();

            Log::info('OneSignal ID saved successfully for user ID ' . $user->id);
            return response()->json(['message' => 'OneSignal ID saved successfully']);
        } catch (\Exception $e) {
            Log::error('Error saving OneSignal ID:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
