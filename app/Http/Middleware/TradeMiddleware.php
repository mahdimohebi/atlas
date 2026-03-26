<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TradeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->section === 'Trade_al') {
            return $next($request);
        }

        abort(403);
    }
}
