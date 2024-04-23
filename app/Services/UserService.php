<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
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
            /** @var User $user */
            $user = $this->userRepository->getNew(array_merge($data, [
                'password' => password_hash(Str::random(12), PASSWORD_BCRYPT),
            ]));

            $user->save();
        } catch(\Exception $e) {
            throw new \Exception('User not created. Errors: ' . $e->getMessage());
        }

        return $user;
    }
}
