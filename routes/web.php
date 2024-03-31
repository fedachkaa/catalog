<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
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

Route::get('/university/{universityId}', [UniversityController::class, 'getUniversity'])->middleware('university.get');

Route::get('/university/{universityId}/faculties', [FacultyController::class, 'getFaculties'])->middleware('university.get');
Route::get('/api/university/{universityId}/faculties', [FacultyController::class, 'getFacultiesList'])->middleware('university.get');
Route::post('/api/university/{universityId}/faculty/create', [FacultyController::class, 'saveFaculty'])->middleware('university.get');
Route::put('/api/university/{universityId}/faculty/{facultyId}', [FacultyController::class, 'updateFaculty'])->middleware('faculty.get');

Route::get('/api/university/{universityId}/courses', [CourseController::class, 'getCoursesList'])->middleware('university.get');
Route::post('/api/university/{universityId}/courses/create', [CourseController::class, 'saveCourse'])->middleware('university.get');

Route::get('/api/university/{universityId}/groups', [GroupController::class, 'getGroupsList'])->middleware('university.get');
Route::post('/api/university/{universityId}/groups/create', [GroupController::class, 'saveGroup'])->middleware('university.get');

Route::get('/university/{universityId}/students', [StudentController::class, 'getStudents'])->middleware('university.get');
Route::post('/api/university/{universityId}/students', [StudentController::class, 'saveStudent'])->middleware('university.get');
Route::post('/api/university/{universityId}/students-import', [StudentController::class, 'importStudents'])->middleware('university.get');
Route::get('/api/university/{universityId}/students', [StudentController::class, 'getStudentsList'])->middleware('university.get');

Route::get('/university/{universityId}/subjects', [SubjectController::class, 'getSubjects'])->middleware('university.get');
Route::get('/api/university/{universityId}/subjects', [SubjectController::class, 'getSubjectsList'])->middleware('university.get');
Route::post('/api/university/{universityId}/subject', [SubjectController::class, 'saveSubject'])->middleware('university.get');
Route::put('/api/university/{universityId}/subject/{subjectId}', [SubjectController::class, 'updateSubject'])->middleware('subject.get');

Route::get('/university/{universityId}/teachers', [TeacherController::class, 'getTeachers'])->middleware('university.get');
Route::get('/api/university/{universityId}/teachers', [TeacherController::class, 'getTeachersList'])->middleware('university.get');
Route::post('/api/university/{universityId}/teachers', [TeacherController::class, 'saveTeacher'])->middleware('university.get');

Route::get('/university/{universityId}/catalogs', [CatalogController::class, 'getCatalogs'])->middleware('university.get');
Route::get('/api/university/{universityId}/catalogs', [CatalogController::class, 'getCatalogsList'])->middleware('university.get');
Route::post('/api/university/{universityId}/catalogs/create', [CatalogController::class, 'saveCatalog'])->middleware('university.get');
Route::get('/university/{universityId}/catalogs/{catalogId}', [CatalogController::class, 'editCatalog'])->middleware('university.get')->middleware('catalog.get');
Route::post('/api/university/{universityId}/catalogs/{catalogId}/topic', [CatalogController::class, 'saveCatalogTopic'])->middleware('university.get')->middleware('catalog.get');
Route::put('/api/university/{universityId}/catalogs/{catalogId}/topic/{topicId}', [CatalogController::class, 'updateCatalogTopic'])->middleware('university.get')->middleware('catalog.get')->middleware('topic.get');

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
