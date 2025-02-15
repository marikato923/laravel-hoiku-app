<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAuthenticatedAsUserAndAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user(); // 現在のユーザー取得

        // 条件: ユーザーとしてログイン済み & メール認証済み & 管理者ではない
        if ($user && $user->hasVerifiedEmail() && !Auth::guard('admin')->check()) {
            return $next($request);
        }

        //  管理者としてログイン済みなら、管理者のホームへリダイレクト
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.home');
        }

        // それ以外はログインページへリダイレクト
        return redirect()->route('login');
    }
}
