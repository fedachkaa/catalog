<?php

namespace App\Exporters;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use App\Repositories\Faculty as FacultyRepository;
use App\Repositories\Group as GroupRepository;
use App\Models\Course as CourseModel;

class Course extends ExporterAbstract
{
    /**
     * @return array
     */
    public function getAllowedExpands(): array
    {
        return [
            'faculty' => 'faculty',
            'groups' => 'groups',
        ];
    }

    /**
     * @param CourseModel|Model $model
     * @return array
     */
    protected function exportModel(Model $model): array
    {
        return [
            'id' => $model->getId(),
            'faculty_id' => $model->getFacultyId(),
            'course' => $model->getCourse(),
        ];
    }

    /**
     * @param CourseModel $course
     * @return array
     */
    protected function expandFaculty(CourseModel $course): array
    {
        /** @var FacultyRepository $facultyRepository */
        $facultyRepository = App::get(FacultyRepository::class);

        return $facultyRepository->export($course->getFaculty());
    }

    /**
     * @param CourseModel $course
     * @return array
     */
    protected function expandGroups(CourseModel $course): array
    {
        /** @var GroupRepository $groupRepository */
        $groupRepository = App::get(GroupRepository::class);

        return $groupRepository->exportAll($course->getGroups());
    }
}
