<?php

namespace App\Http\Middleware;

use App\Repositories\Interfaces\CatalogRepositoryInterface;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;

class GetCatalogRequest
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|Response
     */
    public function handle(Request $request, Closure $next)
    {
        $catalogRepository = App::get(CatalogRepositoryInterface::class);

        $catalogId = $request->route('catalogId');
        if (empty($catalogId)) {
            return response()->json(['error' => 'Каталог не знайдено'], 400);
        }

        $catalog = $catalogRepository->getOne(['id' => $catalogId]);
        if (!$catalog) {
            return response()->json(['error' => 'Каталог не знайдено'], 404);
        }

        $request->route()->setParameter('catalogId', $catalog);

        return $next($request);
    }
}
