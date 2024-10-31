<?php

namespace App\Exporters;

use App\Models\TopicRequest as TopicRequestModel;
use App\Repositories\Interfaces\CatalogRepositoryInterface;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Repositories\Interfaces\TopicRepositoryInterface;
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
            'catalog' => 'catalog',
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
            'catalog_id' => $model->getCatalogId(),
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
        /** @var TopicRepositoryInterface $topicRepository */
        $topicRepository = App::get(TopicRepositoryInterface::class);

        return $topicRepository->export($topicRequest->getTopic(), ['catalog', 'teacher', 'student']);
    }

    /**
     * @param TopicRequestModel $topicRequest
     * @return array
     */
    protected function expandStudent(TopicRequestModel $topicRequest): array
    {
        /** @var StudentRepositoryInterface $studentRepository */
        $studentRepository = App::get(StudentRepositoryInterface::class);

        return $studentRepository->export($topicRequest->getStudent(), ['user']);
    }

    /**
     * @param TopicRequestModel $topicRequest
     * @return array
     */
    protected function expandCatalog(TopicRequestModel $topicRequest): array
    {
        /** @var CatalogRepositoryInterface $catalogRepository */
        $catalogRepository = App::get(CatalogRepositoryInterface::class);

        return $catalogRepository->export($topicRequest->getCatalog());
    }
}
