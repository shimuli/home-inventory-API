<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\User\UserController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->name('api.v1.')->group(function () {

    // test api.
    Route::get('/status', function () {
        return response()->json([
            'status' => 'Ok',
            'Message' => 'Let\'s make it work',
        ]);
    })->name('status');

    // user routes
    Route:: resource('user', UserController::class);
    Route::post('oauth/token', 'Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
    Route::post('login', [LoginController::class, 'login']);

    Route::post('logout', [LoginController::class, 'logout']);

    // categories


    Route::resource('category', CategoryController::class);


});
