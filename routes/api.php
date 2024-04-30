<?php

use App\Http\Controllers\Admin\AdminOverviewController;
use App\Http\Controllers\Admin\AdminUniversityController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/profile', [UserProfileController::class, 'getUserProfile'])->name('user.profile.get');
Route::get('/user/{userId}', [UserProfileController::class, 'getBaseUserInfo']);

Route::prefix('/university/{universityId}')->middleware('university.get')->group(function () {
    Route::get('/faculties', [FacultyController::class, 'getFacultiesList']);
    Route::post('/faculties', [FacultyController::class, 'saveFaculty']);
    Route::put('/faculties/{facultyId}', [FacultyController::class, 'updateFaculty'])->middleware('faculty.get');

    Route::get('/courses', [CourseController::class, 'getCoursesList']);
    Route::post('/courses', [CourseController::class, 'saveCourse']);

    Route::get('/groups', [GroupController::class, 'getGroupsList']);
    Route::post('/groups', [GroupController::class, 'saveGroup']);

    Route::get('/students', [StudentController::class, 'getStudentsList']);
    Route::post('/students', [StudentController::class, 'saveStudent']);
    Route::post('/students-import', [StudentController::class, 'importStudents']);
    Route::get('/students/{studentId}', [StudentController::class, 'editStudent'])->middleware('student.get');
    Route::put('/students/{studentId}', [StudentController::class, 'updateStudent'])->middleware('student.get');
    Route::delete('/students/{studentId}', [StudentController::class, 'deleteStudent'])->middleware('student.get');

    Route::get('/subjects', [SubjectController::class, 'getSubjectsList']);
    Route::post('/subjects', [SubjectController::class, 'saveSubject']);
    Route::put('/subjects/{subjectId}', [SubjectController::class, 'updateSubject'])->middleware('subject.get');

    Route::get('/teachers', [TeacherController::class, 'getTeachersList']);
    Route::post('/teachers', [TeacherController::class, 'saveTeacher']);
    Route::get('/teachers/{teacherId}', [TeacherController::class, 'editTeacher'])->middleware('teacher.get');
    Route::put('/teachers/{teacherId}', [TeacherController::class, 'updateTeacher'])->middleware('teacher.get');
    Route::delete('/teachers/{teacherId}', [TeacherController::class, 'deleteTeacher'])->middleware('teacher.get');

    Route::get('/catalogs', [CatalogController::class, 'getCatalogsList']);
    Route::post('/catalogs', [CatalogController::class, 'saveCatalog']);
    Route::put('/catalogs/{catalogId}', [CatalogController::class, 'updateCatalog'])->middleware('catalog.get'); // TODO check middleware

    Route::post('/catalogs/{catalogId}/topics', [CatalogController::class, 'saveCatalogTopic'])->middleware('catalog.get');  // TODO check middleware
    Route::put('/catalogs/{catalogId}/topics/{topicId}', [CatalogController::class, 'updateCatalogTopic'])->middleware('catalog.get')->middleware('topic.get');  // TODO check middleware

    Route::post('/catalog/{catalogId}/topic/{topicId}/send-request', [CatalogController::class, 'sendRequestTopic'])->middleware('catalog.get')->middleware('topic.get'); // TODO check middleware
});

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::put('/university/{universityId}', [AdminUniversityController::class, 'updateUniversity'])->middleware('university.get');
    Route::get('/universities', [AdminOverviewController::class, 'getUniversities']);
});


Route::get('/api/topic/{topicId}/topic-requests', [CatalogController::class, 'getTopicRequests'])->middleware('topic.get');
Route::post('/api/topic-requests/{requestId}/approve', [CatalogController::class, 'approveRequest'])->middleware('topicRequest.get');
Route::post('/api/topic-requests/{requestId}/reject', [CatalogController::class, 'rejectRequest'])->middleware('topicRequest.get');
