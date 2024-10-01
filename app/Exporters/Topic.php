<?php

namespace App\Exporters;

use App\Models\Topic as TopicModel;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Topic extends ExporterAbstract
{
    /**
     * @return array
     */
    public function getAllowedExpands(): array
    {
        return [
            'teacher' => 'teacher',
        ];
    }

    /**
     * @param TopicModel|Model $model
     * @return array
     */
    protected function exportModel(Model $model): array
    {
        return [
            'id' => $model->getId(),
            'teacher_id' => $model->getTeacherId(),
            'topic' => $model->getTopic(),
            'is_ai_generated' => $model->getIsAiGenerated(),
            'created_at' => $model->getCreatedAt(),
            'updated_at' => $model->getUpdatedAt(),
        ];
    }

    /**
     * @param TopicModel $topic
     * @return array
     */
    protected function expandTeacher(TopicModel $topic): array
    {
        /** @var TopicModel $teacherRepository */
        $teacherRepository = App::get(TeacherRepositoryInterface::class);

        return $teacherRepository->export($topic->getTeacher(), ['user']);
    }
}
