<?php

namespace App\Exporters;

use App\Repositories\Interfaces\TopicRequestRepositoryInterface;
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
            'course' => 'course',
            'faculty' => 'faculty',
            'topicRequests' => 'topicRequests',
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

    /**
     * @param StudentModel $student
     * @return array
     */
    protected function expandCourse(StudentModel $student): array
    {
        /** @var \App\Repositories\Course $courseRepository */
        $courseRepository = App::get(\App\Repositories\Course::class);

        return $courseRepository->export($student->getGroup()->getCourse());
    }

    /**
     * @param StudentModel $student
     * @return array
     */
    protected function expandFaculty(StudentModel $student) : array
    {
        /** @var \App\Repositories\Faculty $facultyRepository */
        $facultyRepository = App::get(\App\Repositories\Faculty::class);

        return $facultyRepository->export($student->getGroup()->getCourse()->getFaculty());
    }

    /**
     * @param StudentModel $student
     * @return array
     */
    protected function expandTopicRequests(StudentModel $student): array
    {
        /** @var TopicRequestRepositoryInterface $topicRequestRepository */
        $topicRequestRepository = App::get(TopicRequestRepositoryInterface::class);

        return $topicRequestRepository->exportAll($student->getTopicRequests(), ['topic']);
    }
}
