<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FactoryMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->section === 'Factory') {
            return $next($request);
        }

        abort(403);
    }
}
