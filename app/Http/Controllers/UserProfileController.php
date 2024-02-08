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

    /**
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();

        $data = $request->validated();

        try {
            $user->updateOrFail(['password' => password_hash($data['password'], PASSWORD_BCRYPT)]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Internal serve error',
                'error' => $e->getMessage()
            ])->setStatusCode(500);
        }

        // TODO send email about successful changing password
        return response()->json([
            'message' => 'Password successfully changed!',
        ])->setStatusCode(200);
    }
}
