<?php

namespace App\Http\Middleware;

use App\Repositories\Interfaces\CatalogTopicRepositoryInterface;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;

class GetTopicRequest
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|Response
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        $catalogTopicRepository = App::get(CatalogTopicRepositoryInterface::class);

        $topicId = $request->route('topicId');
        if (empty($topicId)) {
            return response()->json(['error' => 'Тему не знайдено'], 400);
        }

        $topic = $catalogTopicRepository->getOne(['id' => $topicId]);
        if (!$topic) {
            return response()->json(['error' => 'Тему не знайдено'], 404);
        }

        $request->route()->setParameter('topicId', $topic);

        return $next($request);
    }
}
