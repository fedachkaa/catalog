<?php

namespace App\Exporters;

use App\Models\TopicRequest as TopicRequestModel;
use App\Repositories\Interfaces\CatalogTopicRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class TopicRequest extends ExporterAbstract
{
    /**
     * @return array
     */
    public function getAllowedExpands(): array
    {
        return [
            'topic' => 'topic',
            'student' => 'student',
        ];
    }

    /**
     * @param TopicRequestModel|Model $model
     * @return array
     */
    public function exportModel(Model $model): array
    {
        return [
            'id' => $model->getId(),
            'topic_id' => $model->getTopicId(),
            'student_id' => $model->getStudentId(),
            'status' => $model->getStatus(),
            'status_text' => TopicRequestModel::AVAILABLE_STATUSES[$model->getStatus()],
            'created_at' => $model->getCreatedAt(),
            'updated_at' => $model->getUpdatedAt(),
        ];
    }

    /**
     * @param TopicRequestModel $topicRequest
     * @return array
     */
    protected function expandTopic(TopicRequestModel $topicRequest): array
    {
        /** @var CatalogTopicRepositoryInterface $catalogTopicRepository */
        $catalogTopicRepository = App::get(CatalogTopicRepositoryInterface::class);

        return $catalogTopicRepository->export($topicRequest->getTopic(), ['catalog', 'teacher']);
    }
}
