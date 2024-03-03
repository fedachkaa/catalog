<?php

namespace App\Exporters;

use App\Models\Teacher as TeacherModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Teacher extends ExporterAbstract
{
    /**
     * @return array
     */
    public function getAllowedExpands(): array
    {
        return [
            'user' => 'user',
            'faculty' => 'faculty',
        ];
    }

    /**
     * @param TeacherModel|Model $model
     * @return array
     */
    protected function exportModel(Model $model): array
    {
        return [
            'user_id' => $model->getUserId(),
            'faculty_id' => $model->getFacultyId(),
            'created_at' => $model->getCreatedAt(),
            'updated_at' => $model->getUpdatedAt(),
        ];
    }

    /**
     * @param TeacherModel $teacher
     * @return array
     */
    protected function expandUser(TeacherModel $teacher): array
    {
        /** @var \App\Repositories\User $userRepository */
        $userRepository = App::get(\App\Repositories\User::class);

        return $userRepository->export($teacher->getUser());
    }

    /**
     * @param TeacherModel $teacher
     * @return array
     */
    protected function expandFaculty(TeacherModel $teacher): array
    {
        /** @var \App\Repositories\Faculty $facultyRepository */
        $facultyRepository = App::get(\App\Repositories\Faculty::class);

        return $facultyRepository->export($teacher->getFaculty());
    }
}
