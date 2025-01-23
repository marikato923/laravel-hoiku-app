<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Models\Classroom;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChildController extends Controller
{
    // 一覧ページ
    public function index(Request $request)
    {
        $keyword = $request->input('keyword', '');

        $classroomId = $request->input('classroom_id');
        
        $children = Child::when($keyword, function ($query, $keyword) {
            return $query->where('last_name', 'like', "%{$keyword}%")
                         ->orwhere('first_name', 'like', "%{$keyword}")
                         ->orwhere('last_kana_name', 'like', "%{$keyword}")
                         ->orwhere('first_kana_name', 'like', "%{{$keyword}}");
        })
        ->when($classroomId === null, function ($query) {
            return $query->whereNull('classroom_id');
        })
        ->when($classroomId !== null, function ($query) use ($classroomId) {
            return $query->where('classroom_id', $classroomId);      
        })
        ->paginate(10);

        $classrooms = Classroom::all();

        return view('admin.children.index', compact('children', 'keyword', 'classrooms', 'classroomId'));
    }

    // 詳細ページ
    public function show(Child $child)
    {
        $users = User::all();

        return view('admin.children.show', compact('child', 'users'));
    }

    // 作成ページ
    public function create()
    {
        $classrooms = Classroom::all();

        $users = User::all();

        return view('admin.children.create', compact('classrooms', 'users'));
    }

    // 登録処理
    public function store(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_kana_name' => 'required|string|max:255',
            'first_kana_name' => 'required|string|max:255',
            'img' => 'file|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:2048',
            'birthdate' => 'required|date',
            'admission_date' => 'required|date|after:birthdate',
            'medical_history' => 'nullable|string',
            'has_allergy' => 'required|boolean',
            'allergy_type' => 'nullable|string',
            'classroom_id' => 'required|exists:classrooms,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $child = new Child($validated);
        $child->classroom_id = $request->input('classroom_id');

        // 画像処理
        if ($request->hasFile('img')) {
            $imagePath = $request->file('img')->store('public/children');
            $child->img = basename($imagePath);
        } else {
            $child->img = 'default.png';
        }

        $child->save();

        session()->flash('flash_message', '新しい園児の情報を登録しました。');
        return redirect()->route('admin.children.index');
    }

    // 編集ページ
    public function edit(Child $child)
    {
        $classrooms = Classroom::all();

        $users = User::all();

        return view('admin.children.edit', compact('child', 'classrooms', 'users'));
    }

    public function update(Request $request, Child $child)
    {
        // バリデーション
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_kana_name' => 'required|string|max:255',
            'first_kana_name' => 'required|string|max:255',
            'img' => 'file|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:2048',
            'birthdate' => 'required|date',
            'admission_date' => 'required|date|after:birthdate',
            'medical_history' => 'nullable|string',
            'has_allergy' => 'required|boolean',
            'allergy_type' => 'nullable|string',
            'classroom_id' => 'required|exists:classrooms,id',
            'user_id' => 'required|exists:users,id',
        ]);
        
        // 画像の更新処理
        if ($request->hasFile('img')) {
            // 古い画像があれば削除
            if ($child->img && Storage::exists('public/children/' . $child->img)) {
                Storage::delete('public/children/' . $child->img);
            }
    
            // 新しい画像を保存
            $imagePath = $request->file('img')->store('public/children');
            $validated['img'] = basename($imagePath);
        } elseif ($request->input('img') === '') {
            // 画像が空文字の場合（画像削除）
            $validated['img'] = '';
        }
        
        // データ更新
        $child->fill($validated);
        $child->classroom_id = $request->input('classroom_id');
        $child->save();
        
        session()->flash('flash_message', '園児の情報を編集しました。');
        return redirect()->route('admin.children.show', $child->id);
    }

    // 削除処理
    public function destroy(Child $child)
    {
        if ($child->image) {
            Storage::delete('public/children/' . $child->id);
        }

        $child->delete();

        session()->flash('flash_message', '園児の情報を削除しました。');
        return redirect()->route('admin.children.index');
    }
}