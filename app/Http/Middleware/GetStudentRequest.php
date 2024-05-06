<?php

namespace App\Http\Middleware;

use App\Repositories\Interfaces\StudentRepositoryInterface;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;

class GetStudentRequest
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|Response
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        $studentRepository = App::get(StudentRepositoryInterface::class);

        $studentId = $request->route('studentId');
        if (empty($studentId)) {
            return response()->json(['error' => 'Студента не знайдено'], 400);
        }

        $student = $studentRepository->getOne(['user_id' => $studentId]);
        if (!$student) {
            return response()->json(['error' => 'Студента не знайдено'], 404);
        }

        $request->route()->setParameter('studentId', $student);

        return $next($request);
    }
}
