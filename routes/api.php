<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Products\ProductsController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\UserProductsController;
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
    Route::resource('user', UserController::class);
    Route::name('verify')->get('user/verify/{token}', [UserController::class, 'verify']);
    Route::name('resend')->get('user/{user}/resend', [UserController::class, 'resend']);

    Route::get('userProfile', [UserController::class, 'userProfile'])->middleware('auth:api');
    Route::name('code')->post('code', [ForgotPasswordController::class, 'code']);
    Route::name('reset_password')->post('reset_password', [ResetPasswordController::class, 'reset_password']);


    //Route::resource('profile', ProfileController::class);
    Route::post('oauth/token', 'Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout']);

    // categories
    Route::resource('category', CategoryController::class);

    //products

    Route::resource('products', ProductsController::class)->only(['index', 'store']);
    //Route::resource('userProducts', OneUserProducts::class);

    Route::resource('user.products', UserProductsController::class);

    ;

});
