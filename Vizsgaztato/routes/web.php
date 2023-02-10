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

Route::get('/test/{id}/groups', [TestsGroupsController::class, 'show'])->middleware('auth')->name('showTestGroups');
Route::post('/test/groups', [TestsGroupsController::class, 'update'])->middleware('auth')->name('updateTestGroups');
Route::delete('/test/groups/delete', [TestsGroupsController::class, 'delete'])->middleware('auth')->name('deleteTestGroups');

Route::get('/groups/invites', [GroupInvController::class, 'index'])->middleware('auth')->name('inv_requests');
Route::resource('groups', GroupController::class)->middleware('auth');

Route::post('/submit_join_request', [GroupJoinRequestController::class, 'SubmitRequest'])->middleware('auth')->name('JoinRequestSubmit');
Route::get('/group/{id}/join_requests', [GroupJoinRequestController::class, 'index'])->middleware('auth')->name('join_requests');
Route::post('/accept_join_request', [GroupJoinRequestController::class, 'AcceptRequest'])->middleware('auth')->name('acceptGroupJoinRequest');
Route::delete('/decline_join_request', [GroupJoinRequestController::class, 'RejectRequest'])->middleware('auth')->name('declineGroupJoinRequest');

Route::post('/accept_inv_request', [GroupInvController::class, 'AcceptRequest'])->middleware('auth')->name('declineGroupInvRequest');
Route::delete('/decline_inv_request', [GroupInvController::class, 'RejectRequest'])->middleware('auth')->name('declineGroupInvRequest');

Route::delete('/removeUserFromGroup', [GroupsUsersController::class, 'destroy'])->middleware('auth')->name('removeUserFromGroup');
Route::delete('/leaveFromGroup', [GroupsUsersController::class, 'leave'])->middleware('auth')->name('leaveFromGroup');
Route::delete('/deleteTestAttempt', [TestAttemptController::class, 'destroy'])->middleware('auth')->name('deleteTestAttempt');
Auth::routes();
