<?php

namespace App\Repositories;

use App\Models\University as UniversityModel;
use App\Repositories\Interfaces\UniversityRepositoryInterface;

class University extends RepositoryAbstract implements UniversityRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return UniversityModel::class;
    }

    /**
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getOne(array $filters = [])
    {
        $query = UniversityModel::query();

        if (!empty($filters['id'])) {
            $query = $query->where('id', (int) $filters['id']);
        }

        if (!empty($filters['admin_id'])) {
            $query = $query->where('admin_id', (int) $filters['admin_id']);
        }

        return $query->first();
    }

    /**
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll(array $filters = [])
    {
        $query = UniversityModel::query();

        if (!empty($filters['id'])) {
            $query = $query->where('id', (int) $filters['id']);
        }

        if (!empty($filters['city'])) {
            $query = $query->where('city','like','%' . $filters['city'] . '%');
        }

        if (!empty($filters['email'])) {
            $query = $query->where('email', 'like','%' . $filters['email'] . '%');
        }

        if (!empty($filters['accreditation_level']) && in_array($filters['accreditation_level'], UniversityModel::AVAILABLE_ACCREDITATION_LEVELS)) {
            $query = $query->where('accreditation_level', $filters['accreditation_level']);
        }

        return $query->get();
    }
}
