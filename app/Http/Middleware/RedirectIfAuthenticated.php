<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // 管理者の場合
                if ($guard === 'admin') {
                    return redirect(RouteServiceProvider::ADMIN_HOME);
                }

                // ユーザーの場合
                if ($guard === 'web') {
                    // メール認証が必要で、未認証の場合
                    if (method_exists($user, 'hasVerifiedEmail') && !$user->hasVerifiedEmail()) {
                        return redirect()->route('verification.notice');
                    }

                    return redirect(RouteServiceProvider::HOME);
                }
            }
        }

        return $next($request);
    }
}