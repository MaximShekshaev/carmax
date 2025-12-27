<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SimpleAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        
        if ($token !== env('ADMIN_TOKEN')) {
            return response()->json(['message' => 'Access denied'], 401);
        }

        return $next($request);
    }
}
