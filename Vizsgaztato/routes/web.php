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

Route::get('/', function () {
    return view('layouts.app');
});
Route::get('/home', function () {
    return view('layouts.app');
});
Route::resource('test', TestController::class)->middleware('auth');
Route::get('/test/{test_id}/result/{attempt_id}', [TestController::class, 'showResult'])->middleware('auth')->name('checkAttemptResult');
Route::get('/test/{id}/result/', [TestController::class, 'testResults'])->middleware('auth')->name('checkTestResults');
Route::get('/test/{id}/info/', [TestController::class, 'testInfo'])->middleware('auth')->name('checkTestInfo');

Route::put('/test/group/update', [TestsGroupsController::class, 'update'])->middleware('auth')->name('updateTestGroup');
//Route::delete('/test/groups/delete', [TestsGroupsController::class, 'delete'])->middleware('auth')->name('deleteTestGroups');

Route::get('/groups/invites', [GroupInvController::class, 'index'])->middleware('auth')->name('inv_requests');
Route::resource('groups', GroupController::class)->middleware('auth');

Route::post('/join_request/submit', [GroupJoinRequestController::class, 'SubmitRequest'])->middleware('auth')->name('JoinRequestSubmit');
Route::post('/join_request/accept', [GroupJoinRequestController::class, 'AcceptRequest'])->middleware('auth')->name('acceptGroupJoinRequest');
Route::delete('/join_request/decline', [GroupJoinRequestController::class, 'RejectRequest'])->middleware('auth')->name('declineGroupJoinRequest');
Route::get('/group/{id}/join_requests', [GroupJoinRequestController::class, 'index'])->middleware('auth')->name('join_requests');

Route::post('/inv_request/accept', [GroupInvController::class, 'AcceptRequest'])->middleware('auth')->name('declineGroupInvRequest');
Route::delete('/inv_request/decline', [GroupInvController::class, 'RejectRequest'])->middleware('auth')->name('declineGroupInvRequest');

Route::delete('/group/user/{id}', [GroupsUsersController::class, 'destroy'])->middleware('auth')->name('deleteUserFromGroup');
Route::delete('/group/user/{id}', [GroupsUsersController::class, 'leave'])->middleware('auth')->name('leaveUserFromGroup');
Route::delete('/deleteTestAttempt', [TestAttemptController::class, 'destroy'])->middleware('auth')->name('deleteTestAttempt');
Auth::routes();
