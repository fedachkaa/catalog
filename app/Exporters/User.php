<?php

namespace App\Exporters;

use Illuminate\Database\Eloquent\Model;
use App\Models\User as UserModel;
use Illuminate\Support\Facades\App;

class User extends ExporterAbstract
{
    /**
     * @return array
     */
    public function getAllowedExpands(): array
    {
        return [
            'userRole' => 'userRole',
        ];
    }

    /**
     * @param UserModel|Model $model
     * @return array
     */
    protected function exportModel(Model $model): array
    {
        return [
            'id' => $model->getId(),
            'role_id' => $model->getRoleId(),
            'first_name' => $model->getFirstName(),
            'last_name' => $model->getLastName(),
            'full_name' => $model->getFirstName() . ' ' . $model->getLastName(),
            'email' => $model->getEmail(),
            'phone_number' => $model->getPhoneNumber(),
            'created_at' => $model->getCreatedAt(),
            'updated_at' => $model->getUpdatedAt(),
        ];
    }

    /**
     * @param UserModel $user
     * @return array
     */
    protected function expandUserRole(UserModel $user): array
    {
        /** @var \App\Repositories\UserRole $userRoleRepository */
        $userRoleRepository = App::get(\App\Repositories\UserRole::class);

        return $userRoleRepository->export($user->getUserRole());
    }
}
