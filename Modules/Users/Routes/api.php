<?php


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

use Modules\Users\Http\Controllers\AuthController;
use Modules\Users\Http\Controllers\UsersController;

Route::middleware('tenant')->group(function () {

    Route::prefix('users/auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::get('me', [AuthController::class, 'me']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('users', UsersController::class);
    });

});
