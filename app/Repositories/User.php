<?php

namespace App\Repositories;

use App\Models\User as UserModel;
use App\Exporters\User as UserExporter;
use App\Repositories\Interfaces\UserRepositoryInterface;

class User extends RepositoryAbstract implements UserRepositoryInterface
{
    /**
     * @return string
     */
    public function getModelName(): string
    {
        return UserModel::class;
    }

    /**
     * @return string
     */
    public function getExporterName(): string
    {
        return UserExporter::class;
    }

    /**
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Model|\App\Models\User|null
     */
    public function getOne(array $filters = [])
    {
        $query = UserModel::query();

        if (!empty($filters['id'])) {
            $query = $query->where('id', (int) $filters['id']);
        }

        if (!empty($filters['email'])) {
            $query = $query->where('email', $filters['email']);
        }

        return $query->first();
    }

    /**
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAll(array $filters = [])
    {
        $query = UserModel::query();

        if (!empty($filters['id'])) {
            $query = $query->where('id', (int) $filters['id']);
        }

        if (!empty($filters['role_id'])) {
            $query = $query->where('role_id', (int) $filters['role_id']);
        }

        if (!empty($filters['email'])) {
            $query = $query->where('email', 'like','%' . $filters['email'] . '%');
        }

        return $query->get();
    }
}
