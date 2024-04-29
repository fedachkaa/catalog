<?php

namespace App\Http\Middleware;

use App\Repositories\Interfaces\FacultyRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Closure;

class GetFacultyRequest
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $facultyRepository = App::get(FacultyRepositoryInterface::class);

        $facultyId = $request->route('facultyId');
        if (empty($facultyId)) {
            return response()->json(['error' => 'Invalid get params'], 404);
        }

        $faculty = $facultyRepository->getOne(['id' => $facultyId]);

        if (!$faculty) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $request->route()->setParameter('facultyId', $faculty);

        return $next($request);
    }
}
