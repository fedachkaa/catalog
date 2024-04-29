<?php

namespace App\Repositories;

use App\Repositories\Interfaces\SubjectRepositoryInterface;
use App\Models\Subject as SubjectModel;
use App\Exporters\Subject as SubjectExporter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Subject extends RepositoryAbstract implements SubjectRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return SubjectModel::class;
    }

    /**
     * @return string
     */
    public function getExporterName(): string
    {
        return SubjectExporter::class;
    }

    /**
     * @param array $filters
     * @return Builder|Model|SubjectModel|object|null
     */
    public function getOne(array $filters = [])
    {
        $query = SubjectModel::query();

        if (!empty($filters['id'])) {
            $query = $query->where('id', (int) $filters['id']);
        }

        return $query->first();
    }

    /**
     * @param array $filters
     * @return Builder[]|Collection|SubjectModel[]
     */
    public function getAll(array $filters = [])
    {
        $query = SubjectModel::query();

        if (!empty($filters['university_id'])) {
            $query = $query->where('university_id', (int) $filters['university_id']);
        }

        if (!empty($filters['searchText'])) {
            $query = $query->where('title', 'LIKE',  '%' . $filters['searchText'] . '%');
        }

        return $query->get();
    }
}
