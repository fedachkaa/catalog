<?php

namespace App\Http\Middleware;

use App\Models\UserRole;
use App\Repositories\User as UserRepository;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

class CurrentUserData
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            View::share('user', []);
            return $next($request);
        }

        /** @var UserRepository $userRepository */
        $userRepository = App::get(UserRepository::class);

        $expands = match ($user->getRoleId()) {
            UserRole::USER_ROLE_UNIVERSITY_ADMIN, UserRole::USER_ROLE_TEACHER => ['university'],
            default => [],
        };

        View::share('user', $userRepository->export($user, $expands));

        return $next($request);
    }
}
