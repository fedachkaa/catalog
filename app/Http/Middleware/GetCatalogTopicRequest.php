<?php

namespace App\Http\Middleware;

use App\Repositories\Interfaces\CatalogTopicRepositoryInterface;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;

class GetCatalogTopicRequest
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|Response
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse
    {
        $catalogTopicRepository = App::get(CatalogTopicRepositoryInterface::class);

        $catalogTopicId = $request->route('catalogTopicId');
        \logger('jere');
        \logger($catalogTopicId);
        if (empty($catalogTopicId)) {
            return response()->json(['error' => 'Тему не знайдено'], 400);
        }

        $catalogTopic = $catalogTopicRepository->getOne(['id' => $catalogTopicId]);
        if (!$catalogTopic) {
            return response()->json(['error' => 'Тему не знайдено'], 404);
        }

        $request->route()->setParameter('catalogTopicId', $catalogTopic);

        return $next($request);
    }
}
