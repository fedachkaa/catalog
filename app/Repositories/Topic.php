<?php

namespace App\Repositories;

use App\Repositories\Interfaces\TopicRepositoryRepositoryInterface;
use App\Models\Topic as TopicModel;
use App\Exporters\Topic as TopicExporter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Topic extends RepositoryAbstract implements TopicRepositoryRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return TopicModel::class;
    }

    /**
     * @return string
     */
    public function getExporterName(): string
    {
        return TopicExporter::class;
    }

    /**
     * @param array $filters
     * @return Builder|Model|TopicModel|object|null
     */
    public function getOne(array $filters = [])
    {
        $query = TopicModel::query();

        if (!empty($filters['topic'])) {
            $query = $query->where('topic', (string) $filters['topic']);
        }
        return $query->first();
    }

    /**
     * @param array $filters
     * @return Builder[]|Collection|TopicModel[]
     */
    public function getAll(array $filters = [])
    {
        $query = TopicModel::query();

        return $query->get();
    }
}
