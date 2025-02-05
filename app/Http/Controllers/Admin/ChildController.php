<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ChildController extends Controller
{
    // 子供情報一覧（承認待ちを含む）
    public function index(Request $request)
    {
        $keyword = $request->input('keyword', '');
        $classroomId = $request->input('classroom_id', null);
        $pendingOnly = $request->input('pending_only', false);
    
        $children = Child::query()
            ->when(!is_null($classroomId), function ($query) use ($classroomId) {
                return $query->where('classroom_id', $classroomId);
            })
            ->when(is_null($classroomId), function ($query) {
                return $query->whereNull('classroom_id'); 
            })
            ->when($keyword, function ($query, $keyword) {
                return $query->where(function ($query) use ($keyword) {
                    $query->where('last_name', 'like', "%{$keyword}%")
                        ->orWhere('first_name', 'like', "%{$keyword}%")
                        ->orWhere('last_kana_name', 'like', "%{$keyword}%")
                        ->orWhere('first_kana_name', 'like', "%{$keyword}%")
                        ->orWhere('last_name', $keyword) 
                        ->orWhere('first_name', $keyword) 
                        ->orWhere('last_kana_name', $keyword) 
                        ->orWhere('first_kana_name', $keyword);
                });
            })
            ->when($pendingOnly, function ($query) {
                return $query->where('status', 'pending');
            })
            ->orderBy('last_kana_name', 'asc')
            ->orderBy('first_kana_name', 'asc')
            ->paginate(10);
    
        $classrooms = Classroom::all();
    
        return view('admin.children.index', compact('children', 'keyword', 'classroomId', 'classrooms'));
    }
                    
    // 子供情報の詳細（承認待ちの内容確認を含む）
    public function show(Child $child)
    {
        $classrooms = \App\Models\Classroom::all();

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
            'classroom_id' => 'nullable|exists:classrooms,id',
            'user_id' => 'required|exists:users,id',
            'img' => 'file|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:2048',
        ]);

        try {
            $child = Child::create($validated);
    
            if ($request->hasFile('img')) {
                $imagePath = $request->file('img')->store('public/children');
                $child->img = basename($imagePath);
                $child->save();
            }
            session()->flash('success', '新しい園児の情報を登録しました。');
        } catch (\Exception $e) {
            session()->flash('error', '登録中にエラーが発生しました。');
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
            'medical_history' => 'nullable|string',
            'has_allergy' => 'required|boolean',
            'allergy_type' => 'nullable|string',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'user_id' => 'required|exists:users,id',
            'img' => 'file|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:2048',
        ]);
        
    
        // 更新処理
        $child->update($validated);
    
        // 画像処理（オプション）
        if ($request->hasFile('img')) {
            if ($child->img && Storage::exists('public/children/' . $child->img)) {
                Storage::delete('public/children/' . $child->img);
            }
    
            $imagePath = $request->file('img')->store('public/children');
            $child->img = basename($imagePath);
            $child->save();
        }
    
        session()->flash('success', '園児の情報を更新しました。');
        return redirect()->route('admin.children.show', $child->id);
    }
}
