<?php

namespace App\Http\Middleware;

use App\Repositories\Interfaces\SubjectRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Closure;

class GetSubjectRequest extends UniversityRequest
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        parent::handle( $request, $next);

        $subjectRepository = App::get(SubjectRepositoryInterface::class);

        $subjectId = $request->route('subjectId');
        if (empty($subjectId)) {
            return response()->json(['error' => 'Invalid get params'], 404);
        }

        $subject = $subjectRepository->getOne(['id' => $subjectId]);

        if (!$subject) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $request->route()->setParameter('subjectId', $subject);

        return $next($request);
    }
}
