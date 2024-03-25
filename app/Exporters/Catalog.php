<?php

namespace App\Exporters;

use App\Repositories\Interfaces\GroupRepositoryInterface;
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
            'group' => 'group',
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
            'group_id' => $model->getGroupId(),
            'type' => $model->getType(),
        ];
    }

    /**
     * @param CatalogModel $catalog
     * @return array
     */
    protected function expandGroup(CatalogModel $catalog): array
    {
        /** @var GroupRepositoryInterface $groupRepository */
        $groupRepository = App::get(GroupRepositoryInterface::class);

        return $groupRepository->export($catalog->getGroup());
    }
}
