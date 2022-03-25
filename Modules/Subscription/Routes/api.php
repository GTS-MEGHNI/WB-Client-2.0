<?php

use Illuminate\Support\Facades\Route;
use Modules\Subscription\Http\Controllers\SubscriptionController;

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

Route::group(['prefix' => 'coaching/subscription', 'middleware' => 'TokenValidation', 'UpdateLastSeenAtMiddleware'], function () {
    Route::get('can-subscribe', [SubscriptionController::class, 'verify'])
        ->middleware('CanSubscribeMiddleware');
    Route::post('create', [SubscriptionController::class, 'create'])
        ->middleware('CanSubscribeMiddleware');
    Route::delete('delete', [SubscriptionController::class, 'delete'])
        ->middleware('CanDeleteOrder');
    Route::PATCH('cancel', [SubscriptionController::class, 'cancel'])
        ->middleware('CanCancelOrder');
    Route::get('', [SubscriptionController::class, 'get']);
});
