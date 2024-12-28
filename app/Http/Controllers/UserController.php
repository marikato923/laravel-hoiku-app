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
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_kana_name' => 'required|string|max:255|regex:/\A[ァ-ヴー\s]+\z/u|max:255',
            'first_kana_name' => 'required|string|max:255|regex:/\A[ァ-ヴー\s]+\z/u|max:255',
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
}