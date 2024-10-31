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
        $topicsData = [];
        /** @var Catalog $catalog */
        foreach ($university->getCatalogs() as $catalog) {
            /** @var CatalogTopic $catalogTopic */
            foreach ($catalog->getCatalogTopics() as $catalogTopic) {
                $topicsData[] = $this->topicRepository->export($catalogTopic->getTopic(), ['teacher', 'requests']);
            }
        }

        $analytics = $this->topicAnalyticsService->getTopicAnalytics($topicsData);

        return view('userProfile.common.topics.topics', compact('topicsData', 'analytics'));
    }
}
