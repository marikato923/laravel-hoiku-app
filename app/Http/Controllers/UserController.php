<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        return view('user.show', compact('user'));
    }

    public function store(Request $request)
    {
        // バリデーション
        $validatedData = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_kana_name' => 'required|string|max:255|regex:/\A[ァ-ヴー\s]+\z/u|max:255',
            'first_kana_name' => 'required|string|max:255|regex:/\A[ァ-ヴー\s]+\z/u|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        // ユーザー作成
        $user = User::create([
            'last_name' => $validatedData['last_name'],
            'first_name' => $validatedData['first_name'],
            'last_kana_name' => $validatedData['last_kana_name'],
            'first_kana_name' => $validatedData['first_kana_name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'] ?? 'parent',
            'phone_number' => $validatedData['phone_number'] ?? null,
            'postal_code' => $validatedData['postal_code'] ?? null,
            'address' => $validatedData['address'] ?? null,
            'child_count' => $validatedData['child_count'] ?? 0,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/');
    }

    public function edit()
    {
        $user = auth()->user();

        return view('user.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_kana_name' => 'required|string|max:255|regex:/\A[ァ-ヴー\s]+\z/u|max:255',
            'first_kana_name' => 'required|string|max:255|regex:/\A[ァ-ヴー\s]+\z/u|max:255',
            'phone_number' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'address' => 'nullable|string',
            // ログイン中のユーザーの現在のメールアドレスは除外
            'email' => 'required',
                       'email',
                        Rule::unique('users', 'email')->ignore(auth()->user()->id),
        ]);

        $user = auth()->user();
        $user->update($request->all());

        return redirect()->route('user.show')->with('success', 'プロフィールを更新しました。');
    }

    public function editPassword()
    {
        return view('user.edit-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        // 現在のパスワード確認
        if (!\Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => '現在のパスワードが正しくありません。' ]);
        }

        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('user.edit-password')->with('success', 'パスワードが変更されました。');
    }
}