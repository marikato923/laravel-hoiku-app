<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'kana' => ['required', 'string', 'regex:/\A[ァ-ヴー\s　]+\z/u', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable', 'string'],
            'phone_number' => ['nullable', 'digits_between:10,11'],
            'postal_code' => ['required', 'numeric', 'regex:/^\d{7}$/'],
            'address' => ['nullable', 'string'],
            'child_count' => ['nullable', 'integer'],
        ]);

        // ユーザー作成
        $user = User::create([
            'name' => $validatedData['name'],
            'kana' => $validatedData['kana'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'] ?? 'parent',
            'phone_number' => $validatedData['phone_number'] ?? null,
            'postal_code' => $validatedData['postal_code'],
            'address' => $validatedData['address'] ?? null,
            'child_count' => $validatedData['child_count'] ?? 0, 
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }
}