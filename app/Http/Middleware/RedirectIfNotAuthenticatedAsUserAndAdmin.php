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
        // ユーザーが認証されていて、かつ管理者として認証されていない場合
        if (Auth::check() && !Auth::guard('admin')->check()) {
            // ユーザー側のホームページにアクセスできる
            return $next($request);
        }

        // ユーザーとして認証されていないか、管理者として認証されている場合はログインページにリダイレクト
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.home');
        }

        return redirect()->route('login');
    }
}