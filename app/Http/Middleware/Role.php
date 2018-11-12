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
        if (count(array_diff($request->user()->roles->pluck('name')->toArray(), explode('|',$role))) < count($request->user()->roles->pluck('name')->toArray())){
            return $next($request);
        }

        abort(403);

    }
}
