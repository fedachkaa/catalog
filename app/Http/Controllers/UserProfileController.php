<?php

namespace App\Http\Controllers;

use App\Repositories\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserProfileController extends Controller
{
    /** @var UserService */
    private $userService;

    /** @var User  */
    private $userRepository;

    /**
     * @param UserService $userService
     * @param User $userRepository
     */
    public function __construct(
        UserService $userService,
        User $userRepository
    ){
        $this->userService          = $userService;
        $this->userRepository       = $userRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function userProfile()
    {
        $user = $this->userRepository->export(auth()->user(), ['university']);
        return view('userProfile.userProfile', compact('user'));
    }

    /**
     * @return JsonResponse
     */
    public function getUserProfile(): JsonResponse
    {
        return response()->json([
            'data' => $this->userRepository->export(auth()->user(), ['university']),
        ])->setStatusCode(200);
    }
}
