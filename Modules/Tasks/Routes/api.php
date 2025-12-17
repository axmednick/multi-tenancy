<?php

use Illuminate\Http\Request;
use Modules\Tasks\Http\Controllers\CommentController;
use Modules\Tasks\Http\Controllers\TasksController;

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


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tasks', TasksController::class);

    Route::prefix('tasks/{task}')->group(function () {
        Route::get('comments', [CommentController::class, 'index'])->name('tasks.comments.index');
        Route::post('comments', [CommentController::class, 'store'])->name('tasks.comments.store');
        Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('tasks.comments.destroy');
    });
});

