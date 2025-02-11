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
    Log::info('メール認証の処理開始');
    
    // デバッグ: ユーザー情報をログに出力
    if (!$request->user()) {
        Log::warning('メール認証処理でユーザーが未ログインのため、ログインを試行');
            
        $user = \App\Models\User::find($request->route('id'));
            
        if ($user) {
            Auth::login($user);
            Log::info('未ログインのため、再ログイン処理を実行');
        } else {
            Log::error('メール認証時にユーザーが見つからない');
            return redirect()->route('login')->with('error', '認証エラーが発生しました。もう一度ログインしてください。');
        }
    } else {
        Log::info('ログインユーザーあり', ['user_id' => $request->user()->id]);
    }
    
    if ($request->user()->hasVerifiedEmail()) {
        Log::info('既に認証済みのため、HOMEへリダイレクト');
        return redirect()->route('home')->with('verified', true);
    }
    
    if ($request->user()->markEmailAsVerified()) {
        event(new Verified($request->user()));
    
    session()->flash('success', 'メール認証に成功しました。こどもログへようこそ!');
    }
    
    return redirect()->route('home');
    }
}