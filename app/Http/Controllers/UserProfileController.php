<?php

namespace App\Http\Controllers;

use App\Repositories\University;
use App\Repositories\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserProfileController extends Controller
{
    /** @var UserService */
    private $userService;

    /** @var University */
    private $universityRepository;

    /** @var User  */
    private $userRepository;

    /**
     * @param UserService $userService
     * @param University $universityRepository
     */
    public function __construct(UserService $userService, University $universityRepository, User $userRepository)
    {
        $this->userService          = $userService;
        $this->userRepository       = $userRepository;
        $this->universityRepository = $universityRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function userProfile()
    {
        $user = $this->userRepository->export(auth()->user());
        return view('userProfile.userProfile', compact('user'));
    }

    /**
     * @return JsonResponse
     */
    public function getUserProfile(): JsonResponse
    {
        return response()->json([
            'data' => $this->userRepository->export(auth()->user()),
        ])->setStatusCode(200);
    }

    /**
     * AJAX Route
     *
     * @return JsonResponse
     */
    public function getUniversity(): JsonResponse
    {
        $universityAdmin = auth()->user();
        $university      = $this->universityRepository->getOne(['admin_id' => $universityAdmin->getId()]);

        return response()->json([
            'data' => $this->universityRepository->export($university, ['faculties']),
        ])->setStatusCode(200);
    }
}
