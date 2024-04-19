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
            'university' => 'university'
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
            'role_text' => \App\Models\UserRole::AVAILABLE_USER_ROLES[$model->getRoleId()],
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

    /**
     * @param UserModel $user
     * @return array
     */
    protected function expandUniversity(UserModel $user): array
    {
        /** @var \App\Repositories\University $universityRepository */
        $universityRepository = App::get(\App\Repositories\University::class);

        if ($user->isUniversityAdmin()) {
            return $universityRepository->export($user->getUniversityAdmin());
        } else if ($user->isTeacher()) {
            return $universityRepository->export($user->getTeacher()->getFaculty()->getUniversity());
        } else if ($user->isStudent()) {
            return $universityRepository->export($user->getStudent()->getGroup()->getCourse()->getFaculty()->getUniversity());
        } else {
            return [];
        }
    }
}
