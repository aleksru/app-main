<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;

class ActivityUsers
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
        if (Auth::check() && !Auth::user()->isOnline()) {
            $user = Auth::user();
            $user->last_activity = Carbon::now()->addMinutes(5);
            $user->save();

            if($contTime = $user->getUnClosedTime()){
                $contTime->logout = Carbon::now()->addMinutes(5);
                $contTime->save();
            }
        }

        return $next($request);
    }
}
