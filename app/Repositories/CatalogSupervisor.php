<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CatalogSupervisorRepositoryInterface;
use App\Models\CatalogSupervisor as CatalogSupervisorModel;
use App\Exporters\CatalogSupervisor as CatalogSupervisorExporter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CatalogSupervisor extends RepositoryAbstract implements CatalogSupervisorRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return CatalogSupervisorModel::class;
    }

    /**
     * @return string
     */
    public function getExporterName(): string
    {
        return CatalogSupervisorExporter::class;
    }

    /**
     * @param array $filters
     * @return Builder|Model|CatalogSupervisorModel|object|null
     */
    public function getOne(array $filters = [])
    {
        $query = CatalogSupervisorModel::query();

        if (!empty($filters['catalog_id'])) {
            $query = $query->where('catalog_id', (int) $filters['catalog_id']);
        }

        if (!empty($filters['teacher_id'])) {
            $query = $query->where('teacher_id', (int) $filters['teacher_id']);
        }

        return $query->first();
    }

    /**
     * @param array $filters
     * @return Builder[]|Collection|CatalogSupervisorModel[]
     */
    public function getAll(array $filters = [])
    {
        $query = CatalogSupervisorModel::query();

        if (!empty($filters['catalog_id'])) {
            $query = $query->where('catalog_id', (int) $filters['catalog_id']);
        }

        if (!empty($filters['teacher_id'])) {
            $query = $query->where('teacher_id', (int) $filters['teacher_id']);
        }

        return $query->get();
    }
}
