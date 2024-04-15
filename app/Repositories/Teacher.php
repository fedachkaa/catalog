<?php

namespace App\Repositories;

use App\Models\Student as StudentModel;
use App\Repositories\Interfaces\TeacherRepositoryInterface;
use App\Models\Teacher as TeacherModel;
use App\Exporters\Teacher as TeacherExporter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Teacher extends RepositoryAbstract implements TeacherRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return TeacherModel::class;
    }

    /**
     * @return string
     */
    public function getExporterName(): string
    {
        return TeacherExporter::class;
    }

    /**
     * @param array $filters
     * @return Builder|Model|StudentModel|object|null
     */
    public function getOne(array $filters = [])
    {
        $query = TeacherModel::query();

        if (!empty($filters['user_id'])) {
            $query = $query->where('user_id', (int) $filters['user_id']);
        }

        if (!empty($filters['faculty_id'])) {
            $query = $query->where('faculty_id', (int) $filters['faculty_id']);
        }

        return $query->first();
    }

    /**
     * @param array $filters
     * @return Builder[]|Collection|TeacherModel[]
     */
    public function getAll(array $filters = [])
    {
        $query = TeacherModel::query();

        if (!empty($filters['user_id'])) {
            $query = $query->where('user_id', (int) $filters['user_id']);
        }

        if (!empty($filters['faculty_id'])) {
            $query = $query->where('faculty_id', (int) $filters['faculty_id']);
        }

        if (!empty($filters['university_id'])) {
            $query = $query->join(\App\Models\Faculty::TABLE_NAME, \App\Models\Faculty::TABLE_NAME . '.id', '=', TeacherModel::TABLE_NAME . '.faculty_id');

            $query = $query->where(\App\Models\Faculty::TABLE_NAME . '.university_id', (int) $filters['university_id']);
        }

        if (!empty($filters['searchText'])) {
            $query = $query->join(\App\Models\User::TABLE_NAME, \App\Models\User::TABLE_NAME . '.id', '=', TeacherModel::TABLE_NAME . '.user_id');
            $query->where(\App\Models\User::TABLE_NAME . '.first_name', 'LIKE', '%' . $filters['searchText'] . '%')
                ->orWhere(\App\Models\User::TABLE_NAME . '.last_name', 'LIKE', '%' . $filters['searchText'] . '%');
        }

        return $query->get();
    }
}
