<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; 

class ChildController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword', '');
        $classroomId = $request->input('classroom_id', null);
        $pendingOnly = $request->input('pending_only', false);
    
        $children = Child::query()
            ->when(!empty($classroomId), function ($query) use ($classroomId) {
                return $query->where('classroom_id', $classroomId);
            })
            ->when(empty($classroomId), function ($query) {
                return $query->whereNull('classroom_id');
            })
            ->when($keyword, function ($query, $keyword) {
                return $query->where(function ($query) use ($keyword) {
                    $query->where('last_name', 'like', "%{$keyword}%")
                        ->orWhere('first_name', 'like', "%{$keyword}%")
                        ->orWhere('last_kana_name', 'like', "%{$keyword}%")
                        ->orWhere('first_kana_name', 'like', "%{$keyword}%");
                });
            })
            ->when($pendingOnly, function ($query) {
                return $query->where('status', 'pending');
            })
            ->orderBy('last_kana_name', 'asc')
            ->orderBy('first_kana_name', 'asc')
            ->paginate(8);
    
        $classrooms = Classroom::all();
    
        return view('admin.children.index', compact('children', 'keyword', 'classroomId', 'classrooms'));
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
            \Log::info('Child create処理を開始');
        
            $child = Child::create($validated);
            \Log::info('Child create成功: ', ['child_id' => $child->id]);
        
            if ($request->hasFile('img')) {
                \Log::info('画像のアップロード開始');
        
                if (!$request->file('img')->isValid()) {
                    \Log::error('アップロードされた画像が無効');
                    return back()->with('error', 'アップロードされた画像が無効です。');
                }
        
                // S3に画像をアップロード
                $imagePath = $request->file('img')->store('children', 's3');
        
                // デバッグ用ログ
                \Log::info('S3に保存されたパス: ' . $imagePath);
                dd($imagePath); // ここで画像パスが取得できるか確認
        
                // 画像のフルURLを保存
                $child->img = env('AWS_URL') . '/' . $imagePath;
                $child->save();
                \Log::info('画像URLをデータベースに保存: ' . $child->img);
            } else {
                \Log::warning('ファイルがアップロードされていません');
            }
        
            session()->flash('success', '新しい園児の情報を登録しました。');
        } catch (\Exception $e) {
            \Log::error('登録中にエラーが発生しました: ' . $e->getMessage());
        
            // **エラーメッセージを表示**
            return back()->with('error', '登録中にエラーが発生しました。エラー内容: ' . $e->getMessage());
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
            
        // 更新処理
        $child->update($validated);
        
        // 画像の更新処理
        if ($request->hasFile('img')) {
            if ($child->img) {
                $existingPath = parse_url($child->img, PHP_URL_PATH);
                $existingPath = ltrim($existingPath, '/');
                Storage::disk('s3')->delete($existingPath);
            }
    
            $imagePath = $request->file('img')->store('children', 's3');
            $child->update(['img' => Storage::disk('s3')->url($imagePath)]);
        }
        
        session()->flash('success', '園児の情報を更新しました。');
        return redirect()->route('admin.children.show', $child->id);
    }
}

