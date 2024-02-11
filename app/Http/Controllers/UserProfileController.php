<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Models\User;
use App\Services\UserService;

class UserProfileController extends Controller
{
    /** @var UserService */
    private $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function userProfile()
    {
        $user = auth()->user()->toArray();
        return view('userProfile.userProfile', compact('user'));
    }
}
