<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiV2
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('Authorization') !== env('APP_API_KEY')) {
            Log::channel('api_prices')->error(["Middleware-ApiV2.No key authorization or invalid key! ", $request->all()]);
            return response()->json(['error' => 'No key authorization or invalid key'], 403);
        }

        return $next($request);
    }
}