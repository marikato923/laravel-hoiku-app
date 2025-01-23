<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    public function show(Child $child)
    {
        // ポリシーで認可チェック
        $this->authorize('view', $child);

        $userId = auth()->id();

        if ($child->user_id !== $userId) {
            abort(403, '不正なアクセスです。');
        }

        // 同じuser_idを持つ兄弟の情報を取得
        $siblings = Child::with(['classroom', 'user'])->where('user_id', $userId)->get();

        return view('children.show', compact('siblings'));
    }
}
