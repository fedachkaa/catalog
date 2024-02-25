<?php

namespace App\Http\Middleware;

use App\Repositories\Interfaces\UniversityRepositoryInterface;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;

class GetUniversityRequest
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|Response
     */
    public function handle(Request $request, Closure $next)
    {
        $universityRepository = App::get(UniversityRepositoryInterface::class);

        $universityId = $request->route('universityId');
        $university = $universityRepository->getOne(['id' => $universityId]);

        if (!$university) {
            return response()->json(['error' => 'Університет не знайдено'], 404);
        }

        $request->route()->setParameter('universityId', $university);

        return $next($request);
    }
}
