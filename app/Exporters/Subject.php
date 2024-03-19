<?php

namespace App\Exporters;

use Illuminate\Database\Eloquent\Model;
use App\Models\Subject as SubjectModel;
use Illuminate\Support\Facades\App;

class Subject extends ExporterAbstract
{
    /**
     * @return array
     */
    public function getAllowedExpands(): array
    {
        return [
            'teachers' => 'teachers',
        ];
    }

    /**
     * @param SubjectModel|Model $model
     * @return array
     */
    protected function exportModel(Model $model): array
    {
        return [
            'id' => $model->getId(),
            'university_id' => $model->getUniversityId(),
            'title' => $model->getTitle(),
        ];
    }

    /**
     * @param SubjectModel $subject
     * @return array
     */
    protected function expandTeachers(SubjectModel $subject)
    {
        /** @var \App\Repositories\Teacher $teacherRepository */
        $teacherRepository = App::get(\App\Repositories\Teacher::class);

        return $teacherRepository->exportAll($subject->getTeachers()->get(), ['user']);
    }
}
