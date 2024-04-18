<?php

namespace App\Http\Middleware;

use App\Repositories\Interfaces\TopicRequestRepositoryInterface;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;

class GetTopicRequestRequest
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|Response
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        $topicRequestRepository = App::get(TopicRequestRepositoryInterface::class);

        $topicRequestId = $request->route('requestId');
        if (empty($topicRequestId)) {
            return response()->json(['error' => 'Запит не знайдено'], 400);
        }

        $topicRequest = $topicRequestRepository->getOne(['id' => $topicRequestId]);
        if (!$topicRequest) {
            return response()->json(['error' => 'Запит не знайдено'], 404);
        }

        $request->route()->setParameter('requestId', $topicRequest);

        return $next($request);
    }
}
