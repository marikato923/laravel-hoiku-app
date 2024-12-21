<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;



class UserController extends Controller
{
    public function store(Request $request)
    {
        // バリデーション
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'kana' => 'required|string|regex:/\A[ァ-ヴー\s　]+\z/u|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'address' => 'nullable|string',
            'child_count' => 'nullable|integer',
        ]);

        // ユーザー作成
        $user = User::create([
            'name' => $validatedData['name'],
            'kana' => $validatedData['kana'],
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
}