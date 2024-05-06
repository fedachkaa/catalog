<?php

namespace App\Http\Middleware;

use App\Models\UserRole;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use App\Repositories\Interfaces\UniversityRepositoryInterface;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class GetUniversityRequest
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $universityRepository = App::get(UniversityRepositoryInterface::class);

        $universityId = $request->route('universityId');
        $university = $universityRepository->getOne(['id' => $universityId]);

        $currentUser = auth()->user();
        $entity = null;

        if ($currentUser->getRoleId() === UserRole::USER_ROLE_UNIVERSITY_ADMIN) {
            $entity = $universityRepository->getOne([
                'id' => $universityId,
                'admin_id' => $currentUser->getId(),
            ]);
        } else if ($currentUser->getRoleId() === UserRole::USER_ROLE_TEACHER) {
            $teacherRepository = App::get(TeacherRepositoryInterface::class);

            $entity = $teacherRepository->getAll([
                'user_id' => $currentUser->getId(),
                'university_id' => $universityId,
            ]);
        } else if ($currentUser->getRoleId() === UserRole::USER_ROLE_STUDENT) {
            $studentRepository = App::get(StudentRepositoryInterface::class);

            $entity = $studentRepository->getAll([
                'user_id' => $currentUser->getId(),
                'university_id' => $universityId,
            ]);
        } else if ($currentUser->getRoleId() === UserRole::USER_ROLE_ADMIN) {
            $entity = $university;
        }

        if (empty($entity)) {
            abort(403);
        }

        if (!$university) {
            return response()->json(['error' => 'Університет не знайдено'], 404);
        }

        $request->route()->setParameter('universityId', $university);

        return $next($request);
    }
}
