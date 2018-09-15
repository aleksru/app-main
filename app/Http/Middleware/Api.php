<?php

namespace App\Http\Middleware;

use Closure;
use App\PriceType;

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
        if (!$request->get('key') || 
                $request->get('key') !== env('APP_API_KEY') || 
                !$request->get('pricelist') ||
                !in_array($request->get('pricelist'), PriceType::getPriceTypesName())){
            return abort(404);
        }
        return $next($request);
    }
}
