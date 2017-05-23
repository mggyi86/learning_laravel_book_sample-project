<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\UnauthorizedException;

class AllowIfAdmin
{

    public function handle($request, Closure $next)
    {
        if( Auth::check() && Auth::user()->isActiveStatus() )
        {
            if( Auth::user()->isAdmin() ){
                return $next($request);
            }
        }
        throw new UnauthorizedException;
    }
}
