<?php

namespace App\Exporters;

use App\Repositories\Interfaces\CatalogTopicRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\FacultyRepositoryInterface;
use App\Repositories\Interfaces\GroupRepositoryInterface;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\Catalog as CatalogModel;
use Illuminate\Support\Facades\App;

class Catalog extends ExporterAbstract
{
    /**
     * @return array
     */
    public function getAllowedExpands(): array
    {
        return [
            'topics' => 'topics',
            'groups' => 'groups',
            'supervisors' => 'supervisors',
            'faculty' => 'faculty',
            'course' => 'course',
        ];
    }

    /**
     * @param CatalogModel|Model $model
     * @return array
     */
    protected function exportModel(Model $model): array
    {
        return [
            'id' => $model->getId(),
            'type' => $model->getType(),
            'typeTitle' => CatalogModel::AVAILABLE_CATALOG_TYPES[$model->getType()],
            'is_active' => $model->getIsActive(),
            'created_at' => $model->getCreatedAt(),
            'updated_at' => $model->getUpdatedAt(),
            'activated_at' => $model->getActivatedAt(),
        ];
    }

    /**
     * @param CatalogModel $catalog
     * @return array
     */
    protected function expandGroups(CatalogModel $catalog): array
    {
        /** @var GroupRepositoryInterface $groupRepository */
        $groupRepository = App::get(GroupRepositoryInterface::class);

        $groups = [];
        /** @var \App\Models\CatalogGroup $group */
        foreach ($catalog->getGroups()->get() as $group) {
            $groups[] = $groupRepository->export($group->getGroup());
        }

        return $groups;
    }

    /**
     * @param CatalogModel $catalog
     * @return array
     */
    protected function expandSupervisors(CatalogModel $catalog): array
    {
        /** @var TeacherRepositoryInterface $teacherRepository */
        $teacherRepository = App::get(TeacherRepositoryInterface::class);

        $teachers = [];
        /** @var \App\Models\CatalogSupervisor $teacher */
        foreach ($catalog->getSupervisors()->get() as $teacher) {
            $teachers[] = $teacherRepository->export($teacher->getTeacher(), ['user']);
        }

        return $teachers;
    }

    /**
     * @param CatalogModel $catalog
     * @return array
     */
    protected function expandFaculty(CatalogModel $catalog): array
    {
        /** @var FacultyRepositoryInterface $facultyRepository */
        $facultyRepository = App::get(FacultyRepositoryInterface::class);

        /** @var \App\Models\CatalogGroup $catalogGroup */
        $catalogGroup = $catalog->getGroups()?->first();

        return $facultyRepository->export($catalogGroup?->getGroup()->getCourse()->getFaculty());
    }

    /**
     * @param CatalogModel $catalog
     * @return array
     */
    protected function expandCourse(CatalogModel $catalog): array
    {
        /** @var CourseRepositoryInterface $courseRepository */
        $courseRepository = App::get(CourseRepositoryInterface::class);

        /** @var \App\Models\CatalogGroup $catalogGroup */
        $catalogGroup = $catalog->getGroups()?->first();

        return $courseRepository->export($catalogGroup?->getGroup()->getCourse());
    }

    /**
     * @param CatalogModel $catalog
     * @return array
     */
    protected function expandTopics(CatalogModel $catalog): array
    {
        /** @var CatalogTopicRepositoryInterface $catalogTopicRepository */
        $catalogTopicRepository = App::get(CatalogTopicRepositoryInterface::class);

        return $catalogTopicRepository->exportAll($catalog->getTopics(), ['student', 'teacher']);
    }
}
