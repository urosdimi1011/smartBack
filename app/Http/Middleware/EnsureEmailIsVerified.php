<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Vaš email nije verifikovan.'], 403);
        }
        return $next($request);
    }
}
