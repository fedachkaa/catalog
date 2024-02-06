<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

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

    public function userProfile()
    {
        return view('userProfile.userProfile');
    }
}
