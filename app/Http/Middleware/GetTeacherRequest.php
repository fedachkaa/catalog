<?php

namespace App\Http\Middleware;

use App\Repositories\Interfaces\TeacherRepositoryInterface;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Response;

class GetTeacherRequest
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|Response
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        $teacherRepository = App::get(TeacherRepositoryInterface::class);

        $teacherId = $request->route('teacherId');
        if (empty($teacherId)) {
            return response()->json(['error' => 'Викладача не знайдено'], 400);
        }

        $teacher = $teacherRepository->getOne(['user_id' => $teacherId]);
        if (!$teacher) {
            return response()->json(['error' => 'Викладача не знайдено'], 404);
        }

        $request->route()->setParameter('teacherId', $teacher);

        return $next($request);
    }
}
