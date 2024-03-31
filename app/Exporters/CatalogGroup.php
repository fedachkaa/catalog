<?php

namespace App\Exporters;

use App\Models\CatalogGroup as CatalogGroupModel;
use App\Repositories\Interfaces\CatalogRepositoryInterface;
use App\Repositories\Interfaces\GroupRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class CatalogGroup extends ExporterAbstract
{
    /**
     * @return array
     */
    public function getAllowedExpands(): array
    {
        return [
            'catalog' => 'catalog',
            'group' => 'group',
        ];
    }

    /**
     * @param CatalogGroupModel|Model $model
     * @return array
     */
    protected function exportModel(Model $model): array
    {
        return [
            'catalog_id' => $model->getCatalogId(),
            'group_id' => $model->getGroupId(),
        ];
    }

    /**
     * @param CatalogGroupModel $catalogGroup
     * @return array
     */
    protected function expandCatalog(CatalogGroupModel $catalogGroup): array
    {
        /** @var CatalogRepositoryInterface $catalogRepository */
        $catalogRepository = App::get(CatalogRepositoryInterface::class);

        return $catalogRepository->export($catalogGroup->getCatalog());
    }

    /**
     * @param CatalogGroupModel $catalogGroup
     * @return array
     */
    protected function expandGroup(CatalogGroupModel $catalogGroup): array
    {
        /** @var GroupRepositoryInterface $groupRepository */
        $groupRepository = App::get(GroupRepositoryInterface::class);

        return $groupRepository->export($catalogGroup->getGroup());
    }
}
