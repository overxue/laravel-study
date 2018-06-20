<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfEmailVerified
{
    public function handle($request, Closure $next)
    {
        if (!$request->user()->email_verified) {
            return redirect(route('email_verify_notice'));
        }
        return $next($request);
    }
}
