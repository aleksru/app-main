<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    /**
     * @param $request
     * @param Closure $next
     * @param $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if ($request->user()->is_admin) {
            return $next($request);
        }
        if (!$request->user()->roles->pluck('name')->contains($role)){
            abort(403);
        }

        return $next($request);
    }
}
