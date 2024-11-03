<?php

namespace App\Services;

class TopicAnalyticsService
{
    /** @var string */
    const ANALYTICS_TYPE_MOST_POPULAR = 'most_popular';
    const ANALYTICS_TYPE_LEAST_POPULAR = 'least_popular';
    const ANALYTICS_TYPE_PREDICTED_POPULAR_TOPICS = 'predicted_popular_topics';

    /** @var array */
    const AVAILABLE_ANALYTICS = [
        self::ANALYTICS_TYPE_MOST_POPULAR => 'Найбільш популярні теми',
        self::ANALYTICS_TYPE_LEAST_POPULAR => 'Найменш популярні теми',
    ];

    /** @var OpenAiService */
    private $openAiService;

    /**
     * @param OpenAiService $openAiService
     */
    public function __construct(OpenAiService $openAiService)
    {
        $this->openAiService = $openAiService;
    }

    /**
     * @param array $topicsData
     * @return array
     */
    public function getTopicAnalytics(array $topicsData): array
    {
        return [
            ...$this->getAnalyticsByPopularity($topicsData),
            self::ANALYTICS_TYPE_PREDICTED_POPULAR_TOPICS => $this->getPredictedPopularTopics($topicsData),
        ];
    }

    /**
     * @param array $topicsData
     * @return array
     */
    public function getPredictedPopularTopics(array $topicsData): array
    {
        $aiGeneratedTopics = array_filter($topicsData, function ($topic) {
            return $topic['is_ai_generated'];
        });

        usort($aiGeneratedTopics, function ($a, $b) {
            return count($b['requests']) <=> count($a['requests']);
        });

        $mostPopularTopics = array_slice($aiGeneratedTopics, 0, 3);

        if (empty($mostPopularTopics)) {
            return [];
        }

        $data = [];
        foreach ($mostPopularTopics as $mostPopularTopic) {
            if (in_array($mostPopularTopic['keyword'], array_column($data, 'keyword'))) {
                continue;
            }
            $data[] = [
                'keyword' => $mostPopularTopic['keyword'],
                'topics' => explode("\n\n", $this->openAiService->sendRequest($mostPopularTopic['keyword']))
            ];
        }

        return $data;
    }

    /**
     * @param array $topicsData
     * @return array
     */
    private function getAnalyticsByPopularity(array $topicsData): array
    {
        usort($topicsData, function ($a, $b) {
            return count($b['requests']) <=> count($a['requests']);
        });

        $mostPopular = array_filter($topicsData, function ($topic) {
            return count($topic['requests']) > 0;
        });

        $leastPopular = array_filter($topicsData, function ($topic) use ($mostPopular) {
            return !in_array($topic['id'], array_column($mostPopular, 'id'));
        });

        return [
            self::ANALYTICS_TYPE_MOST_POPULAR => $this->prepareForChart(array_slice($mostPopular, 0, 3)),
            self::ANALYTICS_TYPE_LEAST_POPULAR => $this->prepareForChart(array_slice($leastPopular, -3)),
        ];
    }

    /**
     * @param array $topicsData
     * @return array
     */
    private function prepareForChart(array $topicsData): array
    {
        $data = [];
        foreach ($topicsData as $topic) {
            $data[] = [
                'topic' => $topic['topic'],
                'requestCount' => count($topic['requests']),
            ];
        }

        return $data;
    }
}
