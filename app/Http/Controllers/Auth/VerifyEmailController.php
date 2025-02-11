<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        Log::info('メール認証の処理開始', ['user' => $request->user()]);

        if ($request->user()->hasVerifiedEmail()) {
            Log::info('既に認証済みのため、HOMEへリダイレクト');
            return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
    }
    
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
    
        session()->flash('success', 'メール認証に成功しました。こどもログへようこそ!');

        Auth::login($request->user());
    }
    
    return redirect()->route('home');
    
    }
}
