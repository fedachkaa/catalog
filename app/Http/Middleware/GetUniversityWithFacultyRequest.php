<?php

namespace App\Http\Middleware;

use App\Repositories\Interfaces\FacultyRepositoryInterface;
use App\Repositories\Interfaces\UniversityRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class GetUniversityWithFacultyRequest
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $universityRepository = App::get(UniversityRepositoryInterface::class);
        $facultyRepository = App::get(FacultyRepositoryInterface::class);

        $universityId = $request->route('universityId');
        $facultyId = $request->route('facultyId');
        if (empty($universityId) || empty($facultyId)) {
            return response()->json(['error' => 'Invalid get params'], 404);
        }

        $university = $universityRepository->getOne(['id' => $universityId]);
        $faculty = $facultyRepository->getOne([
            'id' => $facultyId,
            'university_id' => $universityId,
        ]);
        if (!$university || !$faculty) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $request->route()->setParameter('universityId', $university);
        $request->route()->setParameter('facultyId', $faculty);

        return $next($request);
    }
}
