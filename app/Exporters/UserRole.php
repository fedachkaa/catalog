<?php

namespace App\Exporters;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserRole as UserRoleModel;

class UserRole extends ExporterAbstract
{
    /**
     * @return array
     */
    public function getAllowedExpands(): array
    {
        return [];
    }

    /**
     * @param UserRoleModel|Model $model
     * @return array
     */
    protected function exportModel(Model $model): array
    {
        return [
            'id' => $model->getId(),
            'role' => $model->getRole(),
        ];
    }
}
