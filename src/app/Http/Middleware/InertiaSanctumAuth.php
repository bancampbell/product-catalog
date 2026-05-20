<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class InertiaSanctumAuth
{
    public function handle(Request $request, Closure $next)
    {

        $token = $request->bearerToken() ?? $request->query('token');

        if ($token) {
            $accessToken = PersonalAccessToken::findToken($token);
            if ($accessToken && $user = $accessToken->tokenable) {
                auth()->login($user);
            }
        }

        if (!auth()->check()) {
            return redirect('/admin/login');
        }

        return $next($request);
    }
}
