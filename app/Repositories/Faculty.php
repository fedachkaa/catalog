<?php

namespace App\Repositories;

use App\Models\Faculty as FacultyModel;
use App\Exporters\Faculty as FacultyExporter;
use App\Repositories\Interfaces\FacultyRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Faculty extends RepositoryAbstract implements FacultyRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return FacultyModel::class;
    }

    /**
     * @return string
     */
    public function getExporterName(): string
    {
        return FacultyExporter::class;
    }

    /**
     * @param array $filters
     * @return Builder|Model|FacultyModel|object|null
     */
    public function getOne(array $filters = [])
    {
        $query = FacultyModel::query();

        if (!empty($filters['id'])) {
            $query = $query->where('id', (int) $filters['id']);
        }

        if (!empty($filters['title'])) {
            $query = $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        return $query->first();
    }

    /**
     * @param array $filters
     * @return Builder[]|Collection|FacultyModel[]
     */
    public function getAll(array $filters = [])
    {
        $query = FacultyModel::query();

        if (!empty($filters['admin_id'])) {
            $query = $query->join(\App\Models\University::TABLE_NAME, FacultyModel::TABLE_NAME . '.university_id', '=', \App\Models\University::TABLE_NAME . '.id');
            $query = $query->where(\App\Models\University::TABLE_NAME . '.admin_id', (int) $filters['admin_id']);
        }

        if (!empty($filters['university_id'])) {
            $query = $query->where('university_id', (int) $filters['university_id']);
        }

        if (!empty($filters['limit'])) {
            $query = $query->limit($filters['limit']);
        }

        if (!empty($filters['offset'])) {
            $query = $query->offset($filters['offset']);
        }

        return $query->get();
    }
}
