<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChildController extends Controller
{
    // 子供情報一覧を表示（ユーザーが保有する子供情報のみ）
    public function show()
    {
        $userId = auth()->id();

        // ログインユーザーの子供情報を取得
        $siblings = Child::with(['classroom', 'user'])
            ->where('user_id', $userId)
            ->get();

        return view('children.show', compact('siblings'));
    }

    // 子供の新規登録
    public function create()
    {
        $this->authorize('create', Child::class); // ポリシーを適用

        return view('children.create');
    }

    // 新規登録処理
    public function store(Request $request)
    {
        $this->authorize('create', Child::class); // ポリシーを適用

        // バリデーション
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_kana_name' => 'required|string|max:255',
            'first_kana_name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'medical_history' => 'nullable|string',
            'has_allergy' => 'required|boolean',
            'allergy_type' => 'nullable|string',
            'img' => 'file|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:2048',
        ]);

        $child = new Child($validated);
        $child->user_id = auth()->id(); // ログインユーザーを紐付け

        // 画像処理
        if ($request->hasFile('img')) {
            $imagePath = $request->file('img')->store('public/children');
            $child->img = basename($imagePath);
        }

        $child->status = 'pending'; // 保留状態で登録
        $child->save();

        return redirect()->route('children.show')->with('success', 'お子様の情報を登録しました。管理者の承認をお待ちください。');
    }

    // 子供情報の編集フォームを表示
    public function edit(Child $child)
    {
        $this->authorize('update', $child);

        // 認可チェック
        if ($child->user_id !== auth()->id()) {
            abort(403, '不正なアクセスです。');
        }

        return view('children.edit', compact('child'));
    }

    // 編集リクエストの保存処理
    public function update(Request $request, Child $child)
    {
        $this->authorize('update', $child);

        // 認可チェック
        if ($child->user_id !== auth()->id()) {
            abort(403, '不正なアクセスです。');
        }

        // バリデーション
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_kana_name' => 'required|string|max:255',
            'first_kana_name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'medical_history' => 'nullable|string',
            'has_allergy' => 'required|boolean',
            'allergy_type' => 'nullable|string',
            'img' => 'file|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:2048',
        ]);

        // 更新処理
        $child->update([
            'last_name' => $validated['last_name'],
            'first_name' => $validated['first_name'],
            'last_kana_name' => $validated['last_kana_name'],
            'first_kana_name' => $validated['first_kana_name'],
            'birthdate' => $validated['birthdate'],
            'medical_history' => $validated['medical_history'],
            'has_allergy' => $validated['has_allergy'],
            'allergy_type' => $validated['allergy_type'],
            'status' => 'pending', // 保留状態に設定
        ]);

        // 画像の更新処理
        if ($request->hasFile('img')) {
            // 古い画像を削除
            if ($child->img && Storage::exists('public/children/' . $child->img)) {
                Storage::delete('public/children/' . $child->img);
            }

            // 新しい画像を保存
            $imagePath = $request->file('img')->store('public/children');
            $child->img = basename($imagePath);
            $child->save();
        }

        return redirect()->route('children.show')->with('success', '編集リクエストを送信しました。管理者の承認をお待ちください。');
    }
}
