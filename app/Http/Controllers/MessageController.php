<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Models\Admin;

class MessageController extends Controller
{
    // メッセージ一覧取得
    public function index()
    {
        try {
            // ユーザーまたは管理者に関連するメッセージを取得
            $messages = Message::where(function ($query) {
                // ユーザーの場合
                if (auth('web')->check()) {
                    $query->where('receiver_id', auth('web')->id())
                        ->orWhere('sender_id', auth('web')->id());
                }
                // 管理者の場合
                elseif (auth('admin')->check()) {
                    $query->where('receiver_id', auth('admin')->id())
                        ->orWhere('sender_id', auth('admin')->id());
                }
            })->orderBy('created_at', 'desc')->get();

            // 管理者リストをAdminモデルから取得
            $admins = Admin::all();  // Adminモデルを使用

            // ビューにメッセージと管理者リストを渡す
            return view('messages.index', compact('messages', 'admins'));

        } catch (\Exception $e) {
            return response()->json(['error' => 'メッセージ一覧の取得に失敗しました。'], 500);
        }
    }

    // 管理者用メッセージ取得
    public function fetchMessagesForAdmin($userId)
    {
        try {
            $messages = Message::where(function ($query) use ($userId) {
                $query->where('sender_id', auth('admin')->id())
                      ->where('receiver_id', $userId);
            })->orWhere(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', auth('admin')->id());
            })->orderBy('created_at', 'asc')->get();

            // ビューにメッセージを渡す
            return view('messages.index', compact('messages'));

        } catch (\Exception $e) {
            return response()->json(['error' => 'メッセージの取得に失敗しました。'], 500);
        }
    }

    // ユーザー用メッセージ取得
    public function fetchMessagesForUser($adminId)
    {
        try {
            $messages = Message::where(function ($query) use ($adminId) {
                $query->where('sender_id', auth('web')->id())
                      ->where('receiver_id', $adminId);
            })->orWhere(function ($query) use ($adminId) {
                $query->where('sender_id', $adminId)
                      ->where('receiver_id', auth('web')->id());
            })->orderBy('created_at', 'asc')->get();

            // ビューにメッセージを渡す
            return view('messages.index', compact('messages'));

        } catch (\Exception $e) {
            return response()->json(['error' => 'メッセージの取得に失敗しました。'], 500);
        }
    }

    // メッセージ送信
    public function store(Request $request)
    {
        // 認証されていない場合はエラーレスポンス
        if (!auth()->check()) {
            return response()->json(['error' => '認証されていません。'], 401);
        }

        // 現在のガード（ユーザーまたは管理者）を判別
        $guard = auth()->guard('admin')->check() ? 'admin' : 'web';

        // ユーザーの場合、受信者は管理者のみ
        if (auth('web')->check()) {
            // 管理者IDのリストを取得
            $adminIds = Admin::pluck('id');  // Adminモデルを使用

            // 管理者IDのみが受信者
            $validated = $request->validate([
                'receiver_id' => 'required|in:' . implode(',', $adminIds->toArray()),
                'content' => 'required|string|max:500',
            ]);
        } else {
            // 管理者の場合、任意のユーザーに送信可能
            $validated = $request->validate([
                'receiver_id' => 'required|exists:users,id',
                'content' => 'required|string|max:500',
            ]);
        }

        // メッセージをデータベースに保存
        $message = Message::create([
            'sender_id' => auth($guard)->id(),
            'receiver_id' => $validated['receiver_id'],
            'content' => $validated['content'],
            'is_read' => false,
        ]);

        // メッセージ送信後にイベントをブロードキャスト
        broadcast(new MessageSent(
            auth($guard)->user()->name,
            $validated['content'],
            auth($guard)->id(),
            $validated['receiver_id'],
            $message->id
        ));

        // メッセージ送信成功のレスポンス
        return response()->json([
            'message' => 'メッセージを送信しました。',
            'saved_message' => $message
        ], 200);
    }
}