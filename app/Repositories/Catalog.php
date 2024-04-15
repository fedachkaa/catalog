<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CatalogRepositoryInterface;
use App\Models\Catalog as CatalogModel;
use App\Exporters\Catalog as CatalogExporter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Catalog extends RepositoryAbstract implements CatalogRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return CatalogModel::class;
    }

    /**
     * @return string
     */
    public function getExporterName(): string
    {
        return CatalogExporter::class;
    }

    /**
     * @param array $filters
     * @return Builder|Model|CatalogModel|object|null
     */
    public function getOne(array $filters = [])
    {
        $query = CatalogModel::query();

        if (!empty($filters['id'])) {
            $query = $query->where('id', (int) $filters['id']);
        }

        if (!empty($filters['group_id'])) {
            $query = $query->where('group_id', (int) $filters['group_id']);
        }

        if (!empty($filters['type'])) {
            $query = $query->where('type', $filters['type']);
        }

        return $query->first();
    }

    /**
     * @param array $filters
     * @return Builder[]|Collection|CatalogModel[]
     */
    public function getAll(array $filters = [])
    {
        $query = CatalogModel::query();

        if (!empty($filters['id'])) {
            $query = $query->where('id', (int) $filters['id']);
        }

        if (!empty($filters['group_id'])) {
            $query = $query->where('group_id', (int) $filters['group_id']);
        }

        if (!empty($filters['type'])) {
            $query = $query->where('type', $filters['type']);
        }

        if (!empty($filters['teacher_id'])) {
            $query = $query->join('catalog_supervisors', 'catalogs' . '.id', '=', 'catalog_supervisors.catalog_id');
            $query = $query->where('catalog_supervisors.teacher_id', (int) $filters['teacher_id']);
        }

        return $query->get();
    }
}
