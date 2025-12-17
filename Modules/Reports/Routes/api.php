<?php

use Illuminate\Http\Request;
use Modules\Reports\Http\Controllers\ReportsController;

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
Route::prefix('reports')->middleware(['tenant','auth:sanctum'])->group(function () {
    Route::post('generate',[ReportsController::class, 'generateReport']);
});
