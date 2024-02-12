<?php

namespace App\Http\Controllers;

use App\Repositories\University;
use App\Services\UserService;

class UserProfileController extends Controller
{
    /** @var UserService */
    private $userService;

    /** @var University */
    private $universityRepository;

    /**
     * @param UserService $userService
     * @param University $universityRepository
     */
    public function __construct(UserService $userService, University $universityRepository)
    {
        $this->userService = $userService;
        $this->universityRepository = $universityRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function userProfile()
    {
        $user = auth()->user()->toArray();
        return view('userProfile.userProfile', compact('user'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUniversity()
    {
        $universityAdmin = auth()->user();

        $university = $this->universityRepository->getOne(['admin_id' => $universityAdmin->getId()]);

        return response()->json([
            'data' => $university->toArray(),
        ])->setStatusCode(200);
    }
}
