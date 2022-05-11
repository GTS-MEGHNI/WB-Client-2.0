<?php

use Illuminate\Support\Facades\Route;
use Modules\Food\Http\Controllers\FoodController;
use Modules\Food\Http\Controllers\IngredientController;
use Modules\Food\Http\Controllers\RecipeController;
use Modules\Food\Http\Controllers\MeasurementController;
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

Route::group(['prefix' => 'foods', 'middleware' => 'TokenValidation', 'UpdateLastSeenAtMiddleware'], function () {
    Route::get('{foodId}', [FoodController::class, 'get']);
    Route::post('search', [FoodController::class, 'search'])->name('foodSearch');
    Route::group(['prefix' => 'ingredient'], function () {
        Route::post('create', [IngredientController::class, 'create']);
        Route::post('get', [IngredientController::class, 'get']);
        Route::post('update', [IngredientController::class, 'update']);
        Route::post('delete', [IngredientController::class, 'delete']);
        Route::post('list', [IngredientController::class, 'list']);
    });
    Route::group(['prefix' => 'recipe'], function () {
        Route::post('create', [RecipeController::class, 'create']);
        Route::post('get', [RecipeController::class, 'get']);
        Route::post('update', [RecipeController::class, 'update']);
        Route::post('delete', [RecipeController::class, 'delete']);
        Route::post('list', [RecipeController::class, 'list']);
    });
    Route::group(['prefix' => 'measurement'], function() {
        Route::post('create', [MeasurementController::class, 'create']);
        Route::post('read', [MeasurementController::class, 'read']);
        Route::post('update', [MeasurementController::class, 'update']);
        Route::post('delete', [MeasurementController::class, 'delete']);
    });


});
