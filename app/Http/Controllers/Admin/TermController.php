<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;
use Symfony\Contracts\Service\Attribute\Required;

class TermController extends Controller
{
    //概要ページ
    public function index()
    {
        $term = Term::first();

        return view('admin.terms.index', compact('term'));
    }

    // 編集ページ
    public function edit(Term $term)
    {
        return view('admin.terms.edit', compact('term'));
    }

    // 更新機能
    public function update(Request $request, Term $term)
    {
        $validated = $request->validate([
            'content' => 'required',
        ]);

        $term->fill($validated);

        $term->save();

        session()->flash('success', '利用規約を編集しました。');
        return redirect()->route('admin.terms.index');
    }
}
