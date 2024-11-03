<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\CatalogTopic;
use App\Models\University;
use App\Repositories\Interfaces\TopicRepositoryInterface;
use App\Services\TopicAnalyticsService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class TopicController extends Controller
{
    /** @var TopicRepositoryInterface */
    private $topicRepository;

    /** @var TopicAnalyticsService */
    private $topicAnalyticsService;

    /**
     * @param TopicRepositoryInterface $topicRepository
     * @param TopicAnalyticsService $topicAnalyticsService
     */
    public function __construct(TopicRepositoryInterface $topicRepository, TopicAnalyticsService $topicAnalyticsService)
    {
        $this->topicRepository = $topicRepository;
        $this->topicAnalyticsService = $topicAnalyticsService;
    }

    /**
     * @return Application|Factory|View
     */
    public function getTopics(University $university)
    {
        $topicsData = $this->getTopicsForUniversity($university);

        return view('userProfile.common.topics.topics', compact('topicsData'));
    }

    /**
     * @param University $university
     * @return JsonResponse
     */
    public function getTopicsAnalytics(University $university): JsonResponse
    {
        $topicsData = $this->getTopicsForUniversity($university);
        $analytics = $this->topicAnalyticsService->getTopicAnalytics($topicsData);

        return response()->json([
            'message' => 'Success.',
            'data' => $analytics,
        ])->setStatusCode(200);
    }

    /**
     * @param University $university
     * @return array
     */
    private function getTopicsForUniversity(University $university): array
    {
        $topicsData = [];
        /** @var Catalog $catalog */
        foreach ($university->getCatalogs() as $catalog) {
            /** @var CatalogTopic $catalogTopic */
            foreach ($catalog->getCatalogTopics() as $catalogTopic) {
                $topicsData[] = $this->topicRepository->export($catalogTopic->getTopic(), ['teacher', 'requests']);
            }
        }

        return$topicsData;
    }
}
