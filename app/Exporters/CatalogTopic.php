<?php

namespace App\Exporters;

use App\Repositories\Interfaces\CatalogRepositoryInterface;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\CatalogTopic as CatalogTopicModel;
use Illuminate\Support\Facades\App;

class CatalogTopic extends ExporterAbstract
{
    /**
     * @return array
     */
    public function getAllowedExpands(): array
    {
        return [
            'catalog' => 'catalog',
            'student' => 'student',
            'teacher' => 'teacher',
        ];
    }

    /**
     * @param CatalogTopicModel|Model $model
     * @return array
     */
    protected function exportModel(Model $model): array
    {
        return [
            'id' => $model->getId(),
            'catalog_id' => $model->getCatalogId(),
            'topic' => $model->getTopic(),
        ];
    }

    /**
     * @param CatalogTopicModel $catalogTopic
     * @return array
     */
    protected function expandCatalog(CatalogTopicModel $catalogTopic): array
    {
        /** @var CatalogRepositoryInterface $catalogRepository */
        $catalogRepository = App::get(CatalogRepositoryInterface::class);

        return $catalogRepository->export($catalogTopic->getCatalog());
    }

    /**
     * @param CatalogTopicModel $catalogTopic
     * @return array
     */
    protected function expandStudent(CatalogTopicModel $catalogTopic): array
    {
        /** @var StudentRepositoryInterface $studentRepository */
        $studentRepository = App::get(StudentRepositoryInterface::class);

        return $studentRepository->export($catalogTopic->getStudent(), ['user']);
    }

    /**
     * @param CatalogTopicModel $catalogTopic
     * @return array
     */
    protected function expandTeacher(CatalogTopicModel $catalogTopic): array
    {
        /** @var TeacherRepositoryInterface $teacherRepository */
        $teacherRepository = App::get(TeacherRepositoryInterface::class);

        return $teacherRepository->export($catalogTopic->getTeacher(), ['user']);
    }
}
