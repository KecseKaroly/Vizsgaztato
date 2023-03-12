<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\CoursesGroupsController;
use App\Http\Controllers\CoursesUsersController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupJoinRequestController;
use App\Http\Controllers\GroupInvController;
use App\Http\Controllers\GroupsUsersController;
use App\Http\Controllers\TestAttemptController;
use App\Http\Controllers\TestsGroupsController;
use Illuminate\Support\Facades\Auth;

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

Auth::routes();
Route::middleware(['auth'])->group(function() {

    Route::get('/', function () { return view('layouts.app'); });
    Route::get('/home', function () { return view('layouts.home'); })->name('home');

    Route::get('courses/{course}/attempt/{attempt}/result', [TestAttemptController::class, 'show'])->name('testAttempts.show');
    Route::delete('/attempt/delete', [TestAttemptController::class, 'destroy'])->name('testAttempts.delete');
    Route::get('courses/{course}/test/{test}/result/', [TestAttemptController::class, 'index'])->name('testAttempts.index');

    Route::get('courses/{course}/exams', [TestController::class, 'index'])->name('test.index');
    Route::get('courses/{course}/exam/create', [TestController::class, 'create'])->name('test.create');
    Route::get('courses/{course}/test/{test}/info/', [TestController::class, 'testInfo'])->name('checkTestInfo');
    Route::get('courses/{course}/test/{test}/edit', [TestController::class, 'edit'])->name('test.edit');
    Route::get('/test/{test}/show', [TestController::class, 'show'])->name('test.show');
    Route::resource('test', TestController::class)->only(['update', 'destroy']);

    Route::put('/test/group/update', [TestsGroupsController::class, 'update'])->name('updateTestGroup');

    Route::post('/join_request/submit', [GroupJoinRequestController::class, 'SubmitRequest'])->name('JoinRequestSubmit');
    Route::post('/join_request/accept', [GroupJoinRequestController::class, 'AcceptRequest'])->name('acceptGroupJoinRequest');
    Route::delete('/join_request/decline', [GroupJoinRequestController::class, 'RejectRequest'])->name('declineGroupJoinRequest');
    Route::get('/group/{id}/join_requests', [GroupJoinRequestController::class, 'index'])->name('join_requests');

    Route::get('/groups/invites', [GroupInvController::class, 'index'])->name('inv_requests');
    Route::post('/inv_request/accept', [GroupInvController::class, 'AcceptRequest'])->name('declineGroupInvRequest');
    Route::delete('/inv_request/decline', [GroupInvController::class, 'RejectRequest'])->name('declineGroupInvRequest');

    Route::delete('/group/user/{id}/remove', [GroupsUsersController::class, 'destroy'])->name('deleteUserFromGroup');
    Route::delete('/group/user/{id}', [GroupsUsersController::class, 'leave'])->name('leaveUserFromGroup');

    Route::resource('groups', GroupController::class);


    Route::get('/courses/{course}/members/', [CourseController::class, 'members'])->name('courses.members');
    Route::get('/courses/{course}/tests/', [CourseController::class, 'members'])->name('courses.tests');
    Route::delete('/course/user/{course_user}/remove', [CoursesUsersController::class, 'destroy'])->name('removeUserFromCourse');
    Route::delete('/course/user/{id}', [CoursesUsersController::class, 'leave'])->name('leaveFromCourse');
    Route::delete('/course/group/{course_group}/remove', [CoursesGroupsController::class, 'destroy'])->name('removeGroupFromCourse');
    Route::resource('courses', CourseController::class);

    Route::get('/courses/{course}/modules/', [ModuleController::class, 'index'])->name('courses.modules');
    Route::get('/courses/modules/create/{course}', [ModuleController::class, 'create'])->name('modules.create');
    Route::resource('modules', ModuleController::class)->except(['create']);

    Route::get('/courses/{course}/quizzes/', [QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/coursese/{course}/modules/{module}/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
    Route::delete('/quizzes/{test}/delete', [QuizController::class, 'destroy'])->name('quizzes.destroy');
    Route::get('/courses/{course}/quizzes/{test}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
    Route::get('/quizzes/{test}/show', [QuizController::class, 'show'])->name('quizzes.show');

    Route::post('media/image/store', [MediaController::class, 'storeImage'])->name('media.image.store');
});
