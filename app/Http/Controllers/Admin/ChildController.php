<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ChildController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword', '');
        $classroomId = $request->input('classroom_id', null);
        $pendingOnly = $request->input('pending_only', false);
    
        $childrenQuery = Child::query()->leftJoin('classrooms', 'children.classroom_id', '=', 'classrooms.id')
            ->select('children.*', 'classrooms.age_group');
    
        // 名前検索（全体検索）
        if (!empty($keyword)) {
            $childrenQuery->where(function ($query) use ($keyword) {
                $query->where('last_name', 'like', "%{$keyword}%")
                    ->orWhere('first_name', 'like', "%{$keyword}%")
                    ->orWhere('last_kana_name', 'like', "%{$keyword}%")
                    ->orWhere('first_kana_name', 'like', "%{$keyword}%");
            });
        }
    
        // クラスフィルタ（検索とは独立）
        if ($classroomId === 'all') {
            // 全園児
        } elseif ($classroomId !== null && $classroomId !== '') {
            // クラスごと
            $childrenQuery->where('classroom_id', $classroomId);
        } else {
            // 未分類
            $childrenQuery->whereNull('classroom_id');
        }
    
        // 未承認フィルタ
        if ($pendingOnly) {
            $childrenQuery->where('status', 'pending');
        }
    
        // 並び順の適用
        if ($classroomId === 'all') {
            // 全園児の時は「年齢グループ順（昇順）→ 五十音順」
            $childrenQuery->orderBy('classrooms.age_group', 'asc')
                          ->orderBy('last_kana_name', 'asc')
                          ->orderBy('first_kana_name', 'asc');
        } else {
            // クラス指定 or 未分類のときは五十音順
            $childrenQuery->orderBy('last_kana_name', 'asc')
                          ->orderBy('first_kana_name', 'asc');
        }
    
        // ページネーション
        $children = $childrenQuery->paginate(8)->appends($request->query());
    
        $classrooms = Classroom::all();
    
        return view('admin.children.index', compact('children', 'classrooms', 'classroomId', 'keyword'));
    }     
                    
    public function show(Child $child)
    {
        $classrooms = Classroom::all();
        return view('admin.children.show', compact('child', 'classrooms'));
    }

    public function create()
    {
        $classrooms = Classroom::all();
        $users = User::all();
    
        return view('admin.children.create', compact('classrooms', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_kana_name' => 'required|string|max:255',
            'first_kana_name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'admission_date' => 'required|date',
            'medical_history' => 'nullable|string',
            'has_allergy' => 'required|boolean',
            'allergy_type' => 'nullable|string',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'user_id' => 'required|exists:users,id',
            'img' => 'file|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:2048',
        ]);

        try {
            $child = Child::create($validated);

            if ($request->hasFile('img')) {
                $uploadedFile = Cloudinary::upload($request->file('img')->getRealPath());
                $uploadedFileUrl = $uploadedFile->getSecurePath(); 

                if ($uploadedFileUrl) {
                    $child->img = $uploadedFileUrl;
                    $child->save();
                } else {
                    session()->flash('error', '画像のアップロードに失敗しました。');
                }
            }

            session()->flash('success', '新しい園児の情報を登録しました。');
        } catch (\Exception $e) {
            session()->flash('error', '登録中にエラーが発生しました: ' . $e->getMessage());
        }

        return redirect()->route('admin.children.index');
    }

    // 保護者からの編集リクエストを承認
    public function approve($id)
    {
        $child = Child::findOrFail($id);
        
        // 承認状態に更新
        $child->update(['status' => 'approved']);
        
        session()->flash('success', '園児のリクエストを承認しました。');
        return redirect()->route('admin.children.index');
    }
    
    // 保護者からの編集リクエストを却下
    public function reject(Request $request, $id)
    {
        $child = Child::findOrFail($id);
    
        // 却下状態に更新
        $child->update([
            'status' => 'rejected',
            'rejection_reason' => $request->input('rejection_reason') // 却下理由を保存
        ]);
    
        session()->flash('success', 'リクエストを却下しました。');
        return redirect()->route('admin.children.index');
    }
    
    // 削除機能（管理者のみ）
    public function destroy(Child $child)
    {
        if ($child->img) {
            Storage::delete('public/children/' . $child->img);
        }
    
        $child->delete();
    
        session()->flash('success', '園児の情報を削除しました。');
        return redirect()->route('admin.children.index');
    }
    
    public function edit($id)
    {
        $child = Child::findOrFail($id);
        $classrooms = Classroom::all();
        $users = User::all();
        
        return view('admin.children.edit', compact('child', 'users', 'classrooms'));
    }
               
    public function update(Request $request, $id)
    { 
        $child = Child::findOrFail($id);
        
        // バリデーション
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_kana_name' => 'required|string|max:255',
            'first_kana_name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'admission_date' => 'required|date',
            'medical_history' => 'nullable|string',
            'has_allergy' => 'required|boolean',
            'allergy_type' => 'nullable|string',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'user_id' => 'required|exists:users,id',
            'img' => 'file|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:2048',
        ]);

        try {
            // 園児情報の更新
            $child->update($validated);

            // 画像の更新処理
            if ($request->hasFile('img')) {
                // 既存の画像があれば削除
                if (!empty($child->img)) {
                    try {
                        Cloudinary::destroy(basename(parse_url($child->img, PHP_URL_PATH)));
                    } catch (\Exception $e) {
                        session()->flash('warning', '古い画像の削除に失敗しました: ' . $e->getMessage());
                    }
                }

                // 新しい画像をアップロード
                $uploadedFile = Cloudinary::upload($request->file('img')->getRealPath());
                $uploadedFileUrl = $uploadedFile->getSecurePath();

                if ($uploadedFileUrl) {
                    $child->update(['img' => $uploadedFileUrl]);
                } else {
                    session()->flash('error', '新しい画像のアップロードに失敗しました。');
                }
            }

            session()->flash('success', '園児の情報を更新しました。');
        } catch (\Exception $e) {
            session()->flash('error', '更新中にエラーが発生しました: ' . $e->getMessage());
        }

        return redirect()->route('admin.children.show', $child->id);
    }
}

