<?php

namespace App\Repositories;

use App\Repositories\Interfaces\UserRoleRepositoryInterface;
use App\Models\UserRole as UserRoleModel;
use App\Exporters\UserRole as UserRoleExporter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserRole extends RepositoryAbstract implements UserRoleRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return UserRoleModel::class;
    }

    /**
     * @return string
     */
    public function getExporterName(): string
    {
        return UserRoleExporter::class;
    }

    /**
     * @param array $filters
     * @return Builder|Model|object|null
     */
    public function getOne(array $filters = [])
    {
        $query = UserRoleModel::query();

        if (!empty($filters['id'])) {
            $query = $query->where('id', (int) $filters['id']);
        }

        return $query->first();
    }

    /**
     * @param array $filters
     * @return Builder[]|Collection
     */
    public function getAll(array $filters = [])
    {
        $query = UserRoleModel::query();

        if (!empty($filters['id'])) {
            $query = $query->where('id', (int) $filters['id']);
        }

        return $query->get();
    }
}
