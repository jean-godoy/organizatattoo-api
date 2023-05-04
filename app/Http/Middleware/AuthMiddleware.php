<?php

namespace App\Http\Middleware;

use App\Util\JWT\GenerateToken;
use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
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
        $header = $request->header('Authorization') ?? null;

        if ($header) {
            $token = explode(' ', $header)[1];
            $is_valid = GenerateToken::checkAuth($token);

            if ($is_valid['status']) {
                return $next($request);
            }

            return response()->json($is_valid);
        }
    }
}
