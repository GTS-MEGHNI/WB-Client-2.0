<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\DiaryController;
use Modules\Dashboard\Http\Controllers\ProgressController;
use Modules\Dashboard\Http\Controllers\QuoteController;
use Modules\Dashboard\Http\Controllers\DietProgressController;


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

Route::group(['prefix' => 'coaching', 'middleware' => ['TokenValidation', 'UpdateLastSeenAtMiddleware']], function () {

    Route::group(['middleware' => 'HasActivePlan'], function () {

        Route::group(['prefix' => 'diary'], function () {
            Route::get('can-create', [DiaryController::class, 'canCreate']);
            Route::post('create', [DiaryController::class, 'create'])
                ->middleware('AlreadyWriteDiary');
            Route::get('', [DiaryController::class, 'list']);
            Route::group(['prefix' => '{diaryId}'], function() {
                Route::patch('update', [DiaryController::class, 'update']);
                Route::delete('delete', [DiaryController::class, 'delete']);
            });
        });

        Route::group(['prefix' => 'progress'], function () {

            Route::group(['prefix' => 'diary'], function () {
                Route::get('', [DiaryController::class, 'progress']);
            });
            Route::group(['prefix' => 'body'], function () {
                Route::get('can-create', [ProgressController::class, 'canCreate']);
                Route::post('create', [ProgressController::class, 'create'])
                    ->middleware('AlreadyWriteBodyProgress');
                Route::get('', [ProgressController::class, 'list']);
                /*Route::patch('update', [ProgressController::class, 'update']);
                Route::patch('delete', [ProgressController::class, 'delete']);*/
            });
            Route::group(['prefix' => 'diet'], function () {
                Route::get('', [DietProgressController::class, 'list']);
            });
        });
        Route::group(['prefix' => 'quotes'], function () {
            Route::get('', [QuoteController::class, 'list']);
        });
    });
});
