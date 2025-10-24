<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->user()?->currentAccessToken();

        if ($token && $token->expires_at && $token->expires_at->isPast()) {
            return response()->json(['message' => 'Token expirado'], 401);
        }

        return $next($request);

    }
}
