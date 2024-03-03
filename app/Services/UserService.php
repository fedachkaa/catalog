<?php

namespace App\Services;

use App\Mail\UserSignup;
use App\Models\User;
use App\Models\UserRole;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserService
{

    /** @var UserRepositoryInterface $userRepository */
    private $userRepository;

    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function createUser(array $data) : User
    {
        try {
            $password = Str::random(12);

            /** @var User $user */
            $user = $this->userRepository->getNew(array_merge($data, [
                'password' => password_hash($password, PASSWORD_BCRYPT),
            ]));
            $user->save();

            Mail::to($user->getEmail())->send(new UserSignup($user, $password));
        } catch(\Exception $e) {
            throw new \Exception('User not created. Errors: ' . $e->getMessage());
        }

        return $user;
    }

    /**
     * @param array $data
     * @return array
     *
     * // TODO refactor it and refactor register
     */
    private function prepareUserData(array $data) : array
    {
        return [
            'role_id' => $data['role_id'], //UserRole::USER_ROLE_UNIVERSITY_ADMIN,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['user_email'],
            'phone_number' => $data['user_phone_number'],
        ];
    }
}
