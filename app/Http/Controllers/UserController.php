<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Carbon\Carbon;

class UserController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('user.show', compact('user'));
    }

    public function store(Request $request)
    {
        // バリデーション
        $validatedData = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_kana_name' => 'required|string|max:255|regex:/\A[ァ-ヴー\s]+\z/u|max:255',
            'first_kana_name' => 'required|string|max:255|regex:/\A[ァ-ヴー\s]+\z/u|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        // ユーザー作成
        $user = User::create([
            'last_name' => $validatedData['last_name'],
            'first_name' => $validatedData['first_name'],
            'last_kana_name' => $validatedData['last_kana_name'],
            'first_kana_name' => $validatedData['first_kana_name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'] ?? 'parent',
            'phone_number' => $validatedData['phone_number'] ?? null,
            'postal_code' => $validatedData['postal_code'] ?? null,
            'address' => $validatedData['address'] ?? null,
            'child_count' => $validatedData['child_count'] ?? 0,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect('/');
    }

    public function edit()
    {
        $user = auth()->user();
        return view('user.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_kana_name' => 'required|string|max:255|regex:/\A[ァ-ヴー\s]+\z/u|max:255',
            'first_kana_name' => 'required|string|max:255|regex:/\A[ァ-ヴー\s]+\z/u|max:255',
            'phone_number' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'address' => 'nullable|string',
            'email' => [
                'required', 'email', Rule::unique('users', 'email')->ignore(auth()->user()->id),
            ],
        ]);

        $user = auth()->user();
        $user->update($request->all());

        return redirect()->route('user.show')->with('success', 'プロフィールを更新しました。');
    }

    public function editPassword()
    {
        return view('user.edit-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => '現在のパスワードが正しくありません。']);
        }

        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('user.edit-password')->with('success', 'パスワードが変更されました。');
    }

    /**
     * プッシュ通知を登録
     */
    public function subscribe(Request $request)
    {
        try {
            \Log::info('Push subscription request received', ['data' => $request->getContent()]);
    
            $subscription = json_decode($request->getContent(), true);
            $user = Auth::user();
    
            if (!$user) {
                \Log::error('Unauthorized user trying to subscribe');
                return response()->json(['error' => 'Unauthorized'], 401);
            }
    
            if (!$subscription || !isset($subscription['endpoint'])) {
                \Log::error('Invalid subscription data', ['request' => $request->getContent()]);
                return response()->json(['error' => 'Invalid subscription data'], 400);
            }
    
            // `push_subscription` をデータベースに保存
            $user->push_subscription = json_encode($subscription);
            $user->save();
    
            // 保存後のデータを確認
            $user = User::find($user->id); // 再取得して確認
            \Log::info('Push subscription saved', [
                'user_id' => $user->id, 
                'push_subscription' => $user->push_subscription
            ]);
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Failed to subscribe', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to subscribe', 'message' => $e->getMessage()], 500);
        }
    }
    
    
    
    /**
     * お迎え予定1時間前に通知を送信
     */
    public function sendPushNotifications()
    {
        \Log::info('sendPushNotifications() started');
    
        $now = \Carbon\Carbon::now();
        $users = \App\Models\User::whereNotNull('push_subscription')->get();
        \Log::info('Users found', ['count' => count($users)]);
    
        if ($users->isEmpty()) {
            \Log::warning('No users with push subscriptions found.');
            return;
        }
    
        $webPush = new \Minishlink\WebPush\WebPush([
            'VAPID' => [
                'subject' => 'https://hoikulog.com',
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ]);
    
        foreach ($users as $user) {
            $children = $user->children; // 子供の情報を取得
            \Log::info('Checking user', ['user_id' => $user->id, 'children_count' => count($children)]);
    
            foreach ($children as $child) {
                $attendance = \App\Models\Attendance::where('child_id', $child->id)
                    ->where('pickup_time', '<=', $now->addHour())
                    ->where('pickup_time', '>=', $now)
                    ->first();
    
                if (!$attendance) {
                    \Log::info('No attendance record found', ['child_id' => $child->id]);
                    continue;
                }
    
                $subscription = json_decode($user->push_subscription, true);
                if (!$subscription) {
                    \Log::warning('Invalid subscription data', ['user_id' => $user->id]);
                    continue;
                }
    
                $message = [
                    'title' => 'お迎えの時間が近づいています',
                    'body' => $child->name . ' のお迎え時間は ' . $attendance->pickup_time->format('H:i') . ' です。',
                ];
    
                \Log::info('Sending push notification', ['user_id' => $user->id, 'message' => $message]);
    
                $webPush->queueNotification(
                    \Minishlink\WebPush\Subscription::create($subscription),
                    json_encode($message)
                );
            }
        }
    
        $webPush->flush();
        \Log::info('sendPushNotifications() completed');
    }
    
}