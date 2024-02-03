<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use App\Repositories\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /** @var User */
    protected $userRepository;

    /**
     * @param User $userRepository
     */
    public function __construct(User $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return View
     */
    public function login() : View
    {
        return view('auth.login');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request) : RedirectResponse
    {
        $user = $this->userRepository->getOne(['email' => $request->post('email')]);

        if (!empty($user) && Hash::check($request->post('password'), $user->getPassword())) {
            Auth::login($user, true);
            $request->session()->regenerate();

            if ($user->getRoleId() === UserRole::USER_ROLE_ADMIN) {
                return redirect()->route('admin');
            }

            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Your provided credentials do not match in our records. ',
        ])->onlyInput('email');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Успішний вихід з акаунту!');
    }
}
