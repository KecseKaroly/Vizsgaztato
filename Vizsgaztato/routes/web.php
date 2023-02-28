<?php

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

    Route::get('/', function () {
        return view('layouts.app');
    });
    Route::get('/home', function () {
        return view('layouts.home');
    })->name('home');
    Route::get('/attempt/{attempt_id}/result', [TestAttemptController::class, 'show'])->name('testAttempts.show');
    Route::delete('/attempt/delete', [TestAttemptController::class, 'destroy'])->name('testAttempts.delete');
    Route::get('result/{tid}/{gid}/', [TestAttemptController::class, 'index'])->name('testAttempts.index');
    Route::get('result/{tid}/', [TestAttemptController::class, 'indexAllGroups'])->name('testAttempts.index.all');
    Route::get('/test/{id}/info/', [TestController::class, 'testInfo'])->name('checkTestInfo');
    Route::get('/test/{id}/group/{gid}/show', [TestController::class, 'show'])->name('test.show');
    Route::put('/test/group/update', [TestsGroupsController::class, 'update'])->name('updateTestGroup');
    Route::resource('test', TestController::class)->only(['index', 'create', 'edit', 'update', 'destroy']);

    Route::get('/groups/invites', [GroupInvController::class, 'index'])->name('inv_requests');

    Route::post('/join_request/submit', [GroupJoinRequestController::class, 'SubmitRequest'])->name('JoinRequestSubmit');
    Route::post('/join_request/accept', [GroupJoinRequestController::class, 'AcceptRequest'])->name('acceptGroupJoinRequest');
    Route::delete('/join_request/decline', [GroupJoinRequestController::class, 'RejectRequest'])->name('declineGroupJoinRequest');
    Route::get('/group/{id}/join_requests', [GroupJoinRequestController::class, 'index'])->name('join_requests');

    Route::post('/inv_request/accept', [GroupInvController::class, 'AcceptRequest'])->name('declineGroupInvRequest');
    Route::delete('/inv_request/decline', [GroupInvController::class, 'RejectRequest'])->name('declineGroupInvRequest');

    Route::delete('/group/user/{id}/remove', [GroupsUsersController::class, 'destroy'])->name('deleteUserFromGroup');
    Route::delete('/group/user/{id}', [GroupsUsersController::class, 'leave'])->name('leaveUserFromGroup');
    Route::resource('groups', GroupController::class);
});
