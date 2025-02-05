<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpParser\Builder\Class_;

class ClassroomController extends Controller
{
    public function index(Request $request) 
    {
        $keyword = $request->input('keyword'); // 検索ボックスのキーワードを取得
        $query = Classroom::query();

        if ($keyword !== null) {
            $classrooms = Classroom::where('name', 'like', "%{$keyword}%")
                        ->orderBy('age_group')
                        ->paginate(10);
            $total = $classrooms->total();
        } else {
            $classrooms = $query->orderBy('age_group')
                                ->paginate(10);
            $total = Classroom::count();
        }

        return view('admin.classrooms.index', compact('classrooms', 'keyword'));
    }

    public function store(Request $request)
     {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'age_group' => 'nullable|string|max:255',
            'theme_color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/'
        ]);

        $classroom = new Classroom($validated);
        $classroom->save();

        session()->flash('success', 'クラスの情報を登録しました。');
        return redirect()->route('admin.classrooms.index');
    }

    public function update(Request $request, Classroom $classroom)
    {
       $validated = $request->validate([
           'name' => 'required|string|max:255',
           'age_group' => 'nullable|string|max:255',
           'theme_color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
       ]);

       $classroom->fill($validated);
       $classroom->save();

       session()->flash('success', 'クラスの情報を登録しました。');
       return redirect()->route('admin.classrooms.index');
   }

   public function destroy(Classroom $classroom)
   {

    $classroom->delete();

    session()->flash('success', 'クラスを削除しました。');
    return redirect()->route('admin.classrooms.index');

   }
}
