<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/university/create', [UniversityController::class, 'create'])->name('university.create');
Route::post('/university/create', [UniversityController::class, 'store'])->name('university.store');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/profile', [UserProfileController::class, 'userProfile'])->name('user.profile');
Route::get('/profile/api/university', [UserProfileController::class, 'getUniversity'])->name('universityAdmin.university.get');
Route::post('/profile/api/university/faculty/create', [\App\Http\Controllers\FacultyController::class, 'saveFaculty'])->name('universityAdmin.faculty.post');

Route::put('/user/api/change-password', [AuthController::class, 'changePassword'])->name('user.changePassword');
Route::get('forget-password', [AuthController::class, 'showForgetPassword'])->name('forget.password.get');
Route::post('forget-password', [AuthController::class, 'sendResetLink'])->name('forget.password.post');
Route::get('reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('reset.password.get');
Route::post('reset-password', [AuthController::class, 'submitResetPassword'])->name('reset.password.post');

//
//// TODO add middleware
//Route::get('/admin/overview', [DashboardOverviewController::class, 'overview'])->name('dashboard.overview');
//Route::get('/admin/university/{id}', [DashboardUniversityController::class, 'universitySingle'])->name('university.single');
//Route::post('/admin/university/{id}/activate', [DashboardUniversityController::class, 'universityActivation'])->name('university.activation');
