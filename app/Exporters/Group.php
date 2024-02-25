<?php

namespace App\Exporters;

use Illuminate\Database\Eloquent\Model;
use App\Models\Group as GroupModel;
use Illuminate\Support\Facades\App;

class Group extends ExporterAbstract
{
    /**
     * @return array
     */
    public function getAllowedExpands(): array
    {
        return [
            'course' => 'course',
        ];
    }

    /**
     * @param GroupModel|Model $model
     * @return array
     */
    protected function exportModel(Model $model): array
    {
        return [
            'id' => $model->getId(),
            'course_id' => $model->getCourseId(),
            'title' => $model->getTitle(),
        ];
    }

    /**
     * @param GroupModel $group
     * @return array
     */
    protected function expandCourse(GroupModel $group): array
    {
        /** @var \App\Repositories\Course $courseRepository */
        $courseRepository = App::get(\App\Repositories\Course::class);

        return $courseRepository->export($group->getCourse());
    }
}
