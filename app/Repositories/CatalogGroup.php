<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CatalogGroupRepositoryInterface;
use App\Models\CatalogGroup as CatalogGroupModel;
use App\Exporters\CatalogGroup as CatalogGroupExporter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


class CatalogGroup extends RepositoryAbstract implements CatalogGroupRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return CatalogGroupModel::class;
    }

    /**
     * @return string
     */
    public function getExporterName(): string
    {
        return CatalogGroupExporter::class;
    }

    /**
     * @param array $filters
     * @return Builder|Model|CatalogGroupModel|object|null
     */
    public function getOne(array $filters = [])
    {
        $query = CatalogGroupModel::query();

        if (!empty($filters['catalog_id'])) {
            $query = $query->where('catalog_id', (int) $filters['catalog_id']);
        }

        if (!empty($filters['group_id'])) {
            $query = $query->where('group_id', (int) $filters['group_id']);
        }

        return $query->first();
    }

    /**
     * @param array $filters
     * @return Builder[]|Collection|CatalogGroupModel[]
     */
    public function getAll(array $filters = [])
    {
        $query = CatalogGroupModel::query();

        if (!empty($filters['catalog_id'])) {
            $query = $query->where('catalog_id', (int) $filters['catalog_id']);
        }

        if (!empty($filters['group_id'])) {
            $query = $query->where('group_id', (int) $filters['group_id']);
        }

        return $query->get();
    }
}
