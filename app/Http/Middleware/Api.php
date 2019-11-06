<?php

namespace App\Http\Middleware;

use Closure;
use App\PriceType;
use Illuminate\Support\Facades\Log;

class Api
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->get('key') || $request->get('key') !== env('APP_API_KEY')){
            Log::channel('api_prices')->error(["Middleware-Api. No key authorization or invalid key!", $request->all()]);
            return abort(404);
        }
        return $next($request);
    }
}
