<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next, string $user_type, ?string $user_type2 = null, ?string $user_type3 = null)
    {
        if ($request->user()->isType($user_type)) {
            return $next($request);
        }
        if ($user_type2 && $request->user()->isType($user_type2)) {
            return $next($request);
        }
        if ($user_type3 && $request->user()->isType($user_type3)) {
            return $next($request);
        }
        abort(404);
    }
}
