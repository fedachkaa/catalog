<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Admin
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|Response
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
