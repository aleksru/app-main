<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiV2
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('Authorization') !== env('APP_API_KEY')) {
            return response()->json(['error' => 'No key authorization or invalid key'], 403);
        }

        return $next($request);
    }
}