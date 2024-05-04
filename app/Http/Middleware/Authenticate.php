<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Http\RedirectResponse;

class Authenticate extends Middleware
{
    /**
     * @param $request
     * @param Closure $next
     * @param mixed ...$guards
     * @return RedirectResponse|mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        return $next($request);
    }
}
