<?php


namespace App\Http\Middleware;

use Closure;

class Group
{
    public function handle($request, Closure $next, $group)
    {
        if ($request->user()->is_admin) {
            return $next($request);
        }
        if($request->user()->getUserGroupName() === $group){
            return $next($request);
        }
        abort(403);
    }
}
