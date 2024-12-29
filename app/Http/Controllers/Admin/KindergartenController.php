<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kindergarten;
use Illuminate\Http\Request;
use Symfony\Contracts\Service\Attribute\Required;

class KindergartenController extends Controller
{
    //概要ページ
    public function index()
    {
        $kindergarten = Kindergarten::first();

        return view('admin.kindergarten.index', compact('kindergarten'));
    }

    // 編集ページ
    public function edit(Kindergarten $kindergarten)
    {
        return view('admin.kindergarten.edit', compact('kindergarten'));
    }

    // 更新機能
    public function update(Request $request, Kindergarten $kindergarten)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|digits_between:10,11',
            'postal_code' => 'required|numeric|regex:/^\d{7}$/',
            'address' => 'required',
            'principal' => 'required',
            'establishment_date' => 'required|date',
            'number_of_employees' => 'required',
        ]);

        $kindergarten->fill($validated);

        $kindergarten->save();

        session()->flash('flash_message', '保育園概要を編集しました。');
        return redirect()->route('admin.kindergarten.index');
    }
}
