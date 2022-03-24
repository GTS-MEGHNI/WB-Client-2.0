<?php

use Illuminate\Support\Facades\Route;
use Modules\Authentication\Http\Controllers\RegisterController;
use Modules\Authentication\Http\Controllers\LoginController;
use Modules\Authentication\Http\Controllers\RecoverController;
use Modules\Authentication\Http\Controllers\QuickAuthController;
use Modules\Authentication\Http\Controllers\SocialController;

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

Route::group(['prefix' => 'auth'], function () {

    Route::post('login', [LoginController::class, 'login']);

    Route::group(['prefix' => 'social-join'], function () {
        Route::post('google', [SocialController::class, 'google']);
        Route::post('facebook', [SocialController::class, 'facebook']);
    });

    Route::group(['prefix' => 'register'], function () {
        Route::post('create-account', [RegisterController::class, 'sendActivationPasscode']);
        Route::group(['middleware' => 'ShortTermTokenValidation'], function () {
            Route::post('resend-passcode', [RegisterController::class, 'resendActivationPasscode']);
            Route::post('challenge', [RegisterController::class, 'validateAccount']);
        });
    });

    Route::group(['prefix' => 'recover'], function () {
        Route::post('submit-email', [RecoverController::class, 'sendRecoverPasscode']);
        Route::group(['middleware' => 'ShortTermTokenValidation'], function () {
            Route::post('challenge', [RecoverController::class, 'challenge']);
            Route::post('resend-passcode', [RecoverController::class, 'resendRecoverPasscode']);
            Route::post('reset-password', [RecoverController::class, 'resetPassword'])
                ->middleware('RecoverPasscodeSubmissionMiddleware');
        });
    });
});

Route::group(['prefix' => 'quick-auth'], function () {
    Route::post('account', [QuickAuthController::class, 'sendPasscode']);
    Route::group(['middleware' => 'ShortTermTokenValidation'], function () {
        Route::post('challenge', [QuickAuthController::class, 'validateAccount'])
            ->name('quick-auth-challenge');
        Route::post('resend-passcode', [QuickAuthController::class, 'resendPasscode']);
        Route::post('create-password', [QuickAuthController::class, 'createPassword'])
            ->middleware('SignPasscodeSubmissionMiddleware');
    });
});







