<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBan
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->is_banned) {
            // Если пользователь пытается зайти на любую страницу кроме logout
            if (!$request->routeIs('logout')) {
                // Завершаем сессию
                Auth::logout();
                
                // Возвращаем страницу бана
                return response()->view('auth.banned', [], 403);
            }
        }

        return $next($request);
    }
}
