<?php

use Illuminate\Support\Facades\Route;
use Modules\DietPlan\Http\Controllers\DietPlanController;

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

Route::group(['prefix' => 'coaching/diet-plan', 'middleware' => 'TokenValidation', 'UpdateLastSeenAtMiddleware'], function () {
    Route::get('', [DietPlanController::class, 'getPlan']);
    Route::group(['prefix' => 'foods/{foodId}'], function () {
        Route::get('', [DietPlanController::class, 'getFood']);
        Route::patch('mark-as-consumed', [DietPlanController::class, 'markAsConsumed']);
        Route::patch('mark-as-not-consumed', [DietPlanController::class, 'markAsNotConsumed']);
    });
    Route::get('config', [DietPlanController::class, 'getConfig']);
});

Route::get('cache-calendar/{subscription_id}', [DietPlanController::class, 'cache']);
