<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Session;

class ChildController extends Controller
{
    // 子供情報一覧を表示（ユーザーが保有する子供情報のみ）
    public function show()
    {
        $userId = auth()->id();

        // ログインユーザーの子供情報を取得
        $siblings = Child::with(['classroom', 'user'])
            ->where('user_id', $userId)
            ->orderBy('birthdate')
            ->get();

        return view('children.show', compact('siblings'));
    }

    // 子供の新規登録
    public function create()
    {
        $this->authorize('create', Child::class); // ポリシーを適用

        return view('children.create');
    }

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
            'admission_date' => 'nullable|date',
            'medical_history' => 'nullable|string',
            'has_allergy' => 'required|boolean',
            'allergy_type' => 'nullable|string',
            'img' => 'file|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:2048',
        ]);
    
        try {
            $child = new Child($validated);
            $child->user_id = auth()->id();
    
            // 画像処理
            if ($request->hasFile('img')) {
                try {
                    $uploadedFile = Cloudinary::upload($request->file('img')->getRealPath());
                    $uploadedFileUrl = $uploadedFile->getSecurePath();
    
                    if ($uploadedFileUrl) {
                        $child->img = $uploadedFileUrl;
                    }
                } catch (\Exception $e) {
                    session()->flash('error', '画像のアップロードに失敗しました: ' . $e->getMessage());
                }
            }
    
            $child->status = 'pending';
            $child->save();
    
            return redirect()->route('children.show')->with('success', 'お子様の情報を登録しました。管理者の承認をお待ちください。');
    
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '登録中にエラーが発生しました: ' . $e->getMessage());
        }
    }
    
    // 子供情報の編集フォームを表示
    public function edit(Child $child)
    {
        $this->authorize('update', $child);

        // 認可チェック
        if ($child->user_id !== auth()->id()) {
            abort(403, '不正なアクセスです。');
        }

        $users = User::all();

        $classrooms = Classroom::all();

        return view('children.edit', compact('child', 'users', 'classrooms'));
    }

    // 編集リクエストの保存処理
    public function update(Request $request, Child $child)
    {
        $this->authorize('update', $child);
    
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
            'admission_date' => 'nullable|date',
            'medical_history' => 'nullable|string',
            'has_allergy' => 'required|boolean',
            'allergy_type' => 'nullable|string',
            'img' => 'file|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:2048',
        ]);
    
        try {
            // 園児情報の更新
            $child->update([
                'last_name' => $validated['last_name'],
                'first_name' => $validated['first_name'],
                'last_kana_name' => $validated['last_kana_name'],
                'first_kana_name' => $validated['first_kana_name'],
                'birthdate' => $validated['birthdate'],
                'admission_date' => $validated['admission_date'],
                'medical_history' => $validated['medical_history'],
                'has_allergy' => $validated['has_allergy'],
                'allergy_type' => $validated['allergy_type'],
                'status' => 'pending',
            ]);
    
            // 画像の更新処理
            if ($request->hasFile('img')) {
                try {
                    // 既存の画像がある場合、Cloudinaryから削除
                    if (!empty($child->img)) {
                        Cloudinary::destroy(basename(parse_url($child->img, PHP_URL_PATH)));
                    }
    
                    // 新しい画像をアップロード
                    $uploadedFile = Cloudinary::upload($request->file('img')->getRealPath());
                    $uploadedFileUrl = $uploadedFile->getSecurePath();
    
                    if ($uploadedFileUrl) {
                        $child->update(['img' => $uploadedFileUrl]);
                    } else {
                        session()->flash('error', '新しい画像のアップロードに失敗しました。');
                    }
                } catch (\Exception $e) {
                    session()->flash('error', '画像のアップロード中にエラーが発生しました: ' . $e->getMessage());
                }
            }
    
            session()->flash('success', '編集リクエストを送信しました。管理者の承認をお待ちください。');
    
        } catch (\Exception $e) {
            session()->flash('error', '更新中にエラーが発生しました: ' . $e->getMessage());
        }
    
        return redirect()->route('children.show');
    } 
}
