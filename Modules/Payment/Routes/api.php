<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\PaymentController;

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

Route::group(['prefix' => 'payments', 'middleware' => ['TokenValidation', 'HaveOnGoingSubscription', 'UpdateLastSeenAtMiddleware']], function () {
    Route::group(['prefix' => 'intent'], function() {
        Route::post('ccp', [PaymentController::class, 'ccp']);
        Route::post('baridimob', [PaymentController::class, 'baridimob']);
    });
    Route::group(['prefix' => '{paymentId}'], function() {
        Route::post('', [PaymentController::class, 'get']);
    });
});
