<?php

namespace App\Exporters;
use App\Repositories\Interfaces\CatalogRepositoryInterface;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use App\Models\CatalogSupervisor as CatalogSupervisorModel;
use Illuminate\Support\Facades\App;

class CatalogSupervisor extends ExporterAbstract
{
    /**
     * @return array
     */
    public function getAllowedExpands(): array
    {
        return [
            'catalog' => 'catalog',
            'teacher' => 'teacher',
        ];
    }

    /**
     * @param CatalogSupervisorModel|Model $model
     * @return array
     */
    protected function exportModel(Model $model): array
    {
        return [
            'catalog_id' => $model->getCatalogId(),
            'teacher_id' => $model->getTeacherId(),
        ];
    }

    /**
     * @param CatalogSupervisorModel $catalogSupervisor
     * @return array
     */
    protected function expandCatalog(CatalogSupervisorModel $catalogSupervisor): array
    {
        /** @var CatalogRepositoryInterface $catalogRepository */
        $catalogRepository = App::get(CatalogRepositoryInterface::class);

        return $catalogRepository->export($catalogSupervisor->getCatalog());
    }

    /**
     * @param CatalogSupervisorModel $catalogSupervisor
     * @return array
     */
    protected function expandTeacher(CatalogSupervisorModel $catalogSupervisor): array
    {
        /** @var TeacherRepositoryInterface $teacherRepository */
        $teacherRepository = App::get(TeacherRepositoryInterface::class);

        return $teacherRepository->export($catalogSupervisor->getTeacher());
    }
}
