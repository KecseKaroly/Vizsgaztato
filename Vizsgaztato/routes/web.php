<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
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
Auth::routes();
