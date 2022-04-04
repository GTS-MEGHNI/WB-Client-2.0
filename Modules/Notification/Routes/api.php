<?php

use Illuminate\Support\Facades\Route;
use Modules\Notification\Http\Controllers\NotificationController;

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

Route::group(['prefix' => 'notifications', 'middleware' => 'TokenValidation'], function () {
    Route::get('', [NotificationController::class, 'list']);
    Route::group(['prefix' => '{notificationId}'], function () {
        Route::PATCH('mark-as-read', [NotificationController::class, 'check'])
            ->middleware('UpdateLastSeenAtMiddleware');
    });
    Route::PATCH('mark-as-read', [NotificationController::class, 'checkAll'])
        ->middleware('UpdateLastSeenAtMiddleware');
});
