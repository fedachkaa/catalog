<?php

namespace App\Repositories;

use App\Repositories\Interfaces\CatalogTopicRepositoryInterface;
use App\Models\CatalogTopic as CatalogTopicModel;
use App\Exporters\CatalogTopic as CatalogTopicExporter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


class CatalogTopic extends RepositoryAbstract implements CatalogTopicRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return CatalogTopicModel::class;
    }

    /**
     * @return string
     */
    public function getExporterName(): string
    {
        return CatalogTopicExporter::class;
    }

    /**
     * @param array $filters
     * @return Builder|Model|CatalogTopicModel|object|null
     */
    public function getOne(array $filters = [])
    {
        $query = CatalogTopicModel::query();

        if (!empty($filters['id'])) {
            $query = $query->where('id', (int) $filters['id']);
        }

        if (!empty($filters['catalog_id'])) {
            $query = $query->where('catalog_id', (int) $filters['catalog_id']);
        }

        if (!empty($filters['topic'])) {
            $query = $query->where('topic','LIKE', '%' . $filters['topic'] . '%');
        }

        return $query->first();
    }

    /**
     * @param array $filters
     * @return Builder[]|Collection|CatalogTopicModel[]
     */
    public function getAll(array $filters = [])
    {
        $query = CatalogTopicModel::query();

        if (!empty($filters['id'])) {
            $query = $query->where('id', (int) $filters['id']);
        }

        if (!empty($filters['catalog_id'])) {
            $query = $query->where('catalog_id', (int) $filters['catalog_id']);
        }

        if (!empty($filters['teacher_id'])) {
            $query = $query->where('teacher_id', (int) $filters['teacher_id']);
        }

        if (!empty($filters['topic'])) {
            $query = $query->where('topic','LIKE', '%' . $filters['topic'] . '%');
        }

        return $query->get();
    }
}
