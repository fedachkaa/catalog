<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Mail\ForgetPassword;
use App\Models\UserRole;
use App\Repositories\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function showForgetPassword() : View
    {
        return view('auth.forgetPassword');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function sendResetLink(Request $request) : RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->post('email'),
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::to($request->post('email'))->send(new ForgetPassword($token));

        return back()->with('message', 'На вашу електронну пошту надіслано посилання для скидання паролю!');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function showResetPassword($token): View
    {
        return view('auth.resetPassword', ['token' => $token]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function submitResetPassword(Request $request) : RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->post('email'),
                'token' => $request->post('token'),
            ])
            ->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = $this->userRepository->getOne(['email' => $request->post('email')]);
        try {
            $user->updateOrFail(['password' => password_hash($request->post('password'), PASSWORD_BCRYPT)]);
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Internal server error. Error: ' . $e->getMessage());
        }

        DB::table('password_resets')->where(['email' => $request->post('email')])->delete();

        if (auth()->user()->getId() === $user->getId()) {
            return redirect('/profile')->with('message', 'Пароль успішно змінено!');

        }

        return redirect('/login')->with('message', 'Пароль успішно змінено!');
    }

    /**
     * @param ChangePasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        /** @var \App\Models\User $user */
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
