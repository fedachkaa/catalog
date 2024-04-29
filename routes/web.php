<?php

use App\Http\Controllers\Admin\AdminUniversityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Admin\AdminOverviewController;
use Illuminate\Support\Facades\Auth;
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
    if (Auth::check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('dashboard.overview');
        }
        return redirect()->route('user.profile');
    } else {
        return redirect()->route('login');
    }
})->name('home');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::put('/user/api/change-password', [AuthController::class, 'changePassword'])->name('user.changePassword');
Route::get('forget-password', [AuthController::class, 'showForgetPassword'])->name('forget.password.get');
Route::post('forget-password', [AuthController::class, 'sendResetLink'])->name('forget.password.post');
Route::get('reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('reset.password.get');
Route::post('reset-password', [AuthController::class, 'submitResetPassword'])->name('reset.password.post');

Route::get('/university', [UniversityController::class, 'create'])->name('university.create');
Route::post('/university', [UniversityController::class, 'store'])->name('university.store');
Route::get('/university-registration-success', [UniversityController::class, 'registrationSuccess'])->name('university.success');

Route::get('/profile', [UserProfileController::class, 'userProfile'])->name('user.profile');

Route::prefix('/university/{universityId}')->middleware('university.get')->group(function () {
    Route::get('/', [UniversityController::class, 'getUniversity']);
    Route::get('/faculties', [FacultyController::class, 'getFaculties']);
    Route::get('/students', [StudentController::class, 'getStudents']);
    Route::get('/topic-requests', [StudentController::class, 'getStudentTopicRequests'])->middleware('university.get');

    Route::get('/subjects', [SubjectController::class, 'getSubjects']);

    Route::get('/teachers', [TeacherController::class, 'getTeachers']);

    Route::get('/catalogs', [CatalogController::class, 'getCatalogs']);
    Route::get('/catalogs/{catalogId}', [CatalogController::class, 'editCatalog'])->middleware('catalog.get')->name('view.catalog'); // TODO check middleware

});

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/overview', [AdminOverviewController::class, 'overview'])->name('dashboard.overview');
    Route::get('/university/{universityId}', [AdminUniversityController::class, 'universitySingle'])->name('university.single')->middleware('university.get');
    Route::put('/api/university/{universityId}', [AdminUniversityController::class, 'updateUniversity'])->middleware('university.get');
});
