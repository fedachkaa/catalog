<?php

namespace App\Http\Middleware;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\FacultyRepositoryInterface;
use App\Repositories\Interfaces\UniversityRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class GetUniversityWithFacultyCourseRequest
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
        $courseRepository = App::get(CourseRepositoryInterface::class);

        $universityId = $request->route('universityId');
        $facultyId = $request->route('facultyId');
        $courseId = $request->route('courseId');

        if (!$universityId || !$facultyId || !$courseId) {
            return response()->json(['error' => 'Invalid get params'], 404);
        }

        $university = $universityRepository->getOne(['id' => $universityId]);
        $faculty = $facultyRepository->getOne(['id' => $facultyId, 'university_id' => $universityId]);
        $course = $courseRepository->getOne(['id' => $courseId, 'faculty_id' => $facultyId]);

        if (!$university || !$faculty || !$course) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $request->route()->setParameter('universityId', $university);
        $request->route()->setParameter('facultyId', $faculty);
        $request->route()->setParameter('courseId', $course);

        return $next($request);
    }
}
