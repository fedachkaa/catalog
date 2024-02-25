<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\GroupController;
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
Route::get('/api/profile', [UserProfileController::class, 'getUserProfile'])->name('user.profile.get');
Route::get('/profile/api/university', [UserProfileController::class, 'getUniversity'])->name('universityAdmin.university.get');

Route::get('/api/university/{universityId}/faculties', [FacultyController::class, 'getFaculties'])->middleware('university.get');
Route::post('/api/university/{universityId}/faculty/create', [FacultyController::class, 'saveFaculty'])->middleware('university.get');
Route::get('/api/university/{universityId}/faculty/{facultyId}', [FacultyController::class, 'getFaculty'])->middleware('universityWithFaculty.get');

Route::post('/api/university/{universityId}/faculty/{facultyId}/course/create', [CourseController::class, 'saveCourse'])->middleware('universityWithFaculty.get');

Route::get('/api/university/{universityId}/faculty/{facultyId}/course/{courseId}/groups', [GroupController::class, 'getCourseGroups'])->middleware('universityWithFacultyCourse.get');
Route::post('/api/university/{universityId}/faculty/{facultyId}/course/{courseId}/group/create', [GroupController::class, 'saveGroup'])->middleware('universityWithFacultyCourse.get');

Route::get('/api/university/{universityId}/faculty/{facultyId}/course/{courseId}/group/{groupId}/students', [StudentController::class, 'getGroupStudents'])->middleware('universityWithFacultyCourseGroup.get');
Route::post('/api/university/{universityId}/faculty/{facultyId}/course/{courseId}/group/{groupId}/students', [StudentController::class, 'saveStudent'])->middleware('universityWithFacultyCourseGroup.get');
Route::post('/api/university/{universityId}/faculty/{facultyId}/course/{courseId}/group/{groupId}/students-import', [StudentController::class, 'importStudents'])->middleware('universityWithFacultyCourseGroup.get');

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
