<?php

namespace App\Repositories;

use App\Models\University as UniversityModel;
use App\Repositories\Interfaces\UniversityRepositoryInterface;
use App\Exporters\University as UniversityExporter;

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
     * @return string
     */
    public function getExporterName(): string
    {
        return UniversityExporter::class;
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

        if (!empty($filters['title'])) {
            $query = $query->where('name','like','%' . $filters['title'] . '%');
        }

        if (!empty($filters['city'])) {
            $query = $query->where('city','like','%' . $filters['city'] . '%');
        }

        if (!empty($filters['email'])) {
            $query = $query->where('email', 'like','%' . $filters['email'] . '%');
        }

        if (!empty($filters['accreditation_level']) && in_array($filters['accreditation_level'], array_keys(UniversityModel::AVAILABLE_ACCREDITATION_LEVELS))) {
            $query = $query->where('accreditation_level', $filters['accreditation_level']);
        }

        if (isset($filters['status']) && in_array($filters['status'], array_keys(UniversityModel::AVAILABLE_STATUSES))) {
            if ($filters['status'] === 1) {
                $query = $query->whereNotNull('activated_at');
            } else {
                $query = $query->whereNull('activated_at');
            }
        }
        if (!empty($filters['createdAt'])) {
            $createdAtFormatted = date('Y-m-d', strtotime($filters['createdAt']));
            $query = $query->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') = ?", [$createdAtFormatted]);
        }

        return $query->get();
    }
}
