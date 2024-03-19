<?php

namespace App\Http\Middleware;

use App\Repositories\Interfaces\UniversityRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class UniversityRequest
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $universityRepository = App::get(UniversityRepositoryInterface::class);

        $universityId = $request->route('universityId');
        if (empty($universityId)) {
            return response()->json(['error' => 'Invalid get params'], 404);
        }

        $university = $universityRepository->getOne(['id' => $universityId]);

        if (!$university) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $request->route()->setParameter('universityId', $university);

        return $next($request);
    }
}
