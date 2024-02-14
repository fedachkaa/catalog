<?php

namespace App\Exporters;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student as StudentModel;
use Illuminate\Support\Facades\App;

class Student extends ExporterAbstract
{
    /**
     * @return array
     */
    public function getAllowedExpands(): array
    {
        return [
            'user' => 'user',
            'group' => 'group',
        ];
    }

    /**
     * @param StudentModel|Model $model
     * @return array
     */
    protected function exportModel(Model $model): array
    {
        return [
            'user_id' => $model->getUserId(),
            'group_id' => $model->getGroupId(),
            'created_at' => $model->getCreatedAt(),
            'updated_at' => $model->getUpdatedAt(),
        ];
    }

    /**
     * @param StudentModel $student
     * @return array
     */
    protected function expandUser(StudentModel $student): array
    {
        /** @var \App\Repositories\User $userRepository */
        $userRepository = App::get(\App\Repositories\User::class);

        return $userRepository->export($student->getUser());
    }

    /**
     * @param StudentModel $student
     * @return array
     */
    protected function expandGroup(StudentModel $student): array
    {
        /** @var \App\Repositories\Group $groupRepository */
        $groupRepository = App::get(\App\Repositories\Group::class);

        return $groupRepository->export($student->getGroup());
    }
}
