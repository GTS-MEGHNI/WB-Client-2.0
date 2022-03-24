<?php

use Modules\Plans\Http\Controllers\PlansController;


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

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'coaching/plans'], function () {
    Route::post('create', [PlansController::class, 'create']);
    Route::post('update', [PlansController::class, 'update']);
    Route::post('delete', [PlansController::class, 'delete']);
    Route::get('', [PlansController::class, 'list']);
});
