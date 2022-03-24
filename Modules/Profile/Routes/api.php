<?php

use Illuminate\Support\Facades\Route;
use Modules\Profile\Http\Controllers\ProfileController;
use Modules\Profile\Http\Controllers\UpdatePasswordController;
use Modules\Profile\Http\Controllers\UpdateEmailController;

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

Route::group(['prefix' => 'profile', 'middleware' => 'TokenValidation', 'UpdateLastSeenAtMiddleware'], function () {
    Route::group(['prefix' => 'update'], function () {
        Route::post('firstname', [ProfileController::class, 'updateFirstName']);
        Route::post('lastname', [ProfileController::class, 'updateLastName']);
        Route::post('birthday', [ProfileController::class, 'updateBirthday']);
        Route::post('gender', [ProfileController::class, 'updateGender']);
        Route::group(['prefix' => 'password'], function () {
            Route::post('send-passcode', [UpdatePasswordController::class, 'sendPasscode']);
            Route::post('challenge', [UpdatePasswordController::class, 'validatePasscode']);
            Route::post('new-password', [UpdatePasswordController::class, 'update']);
        });
    });
    Route::post('phone', [ProfileController::class, 'updatePhone']);
    Route::post('country', [ProfileController::class, 'updateCountry']);
    Route::post('province', [ProfileController::class, 'updateProvince']);
    Route::post('address', [ProfileController::class, 'updateAddress']);
    Route::group(['prefix' => 'email'], function () {
        Route::post('send-passcode', [UpdateEmailController::class, 'sendPasscode']);
        Route::post('challenge', [UpdateEmailController::class, 'validatePasscode']);
        Route::post('new-email', [UpdateEmailController::class, 'updateEmail']);
    });
});
