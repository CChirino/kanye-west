<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\KanyeWestController;
use App\Http\Controllers\API\FavoriteQuotesController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::post('logout', 'logout');
    });

    Route::middleware('auth:sanctum')->prefix('profile')->group(function () {
        Route::controller(ProfileController::class)->group(function () {
            Route::get('show', 'show');
            Route::post('update', 'update');
        });
    });
    Route::middleware('auth:sanctum', 'throttle:30,1')->prefix('quote')->group(function () {
        Route::controller(KanyeWestController::class)->group(function () {
            Route::get('show', 'getRandomQuotes');
            Route::get('number-quotes', 'getNumberRandomQuotes');

        });
    });

    Route::middleware('auth:sanctum')->prefix('favorite')->group(function () {
        Route::controller(FavoriteQuotesController::class)->group(function () {
            Route::get('index', 'index');
            Route::post('store', 'store');
            Route::delete('delete/{id}', 'delete');

        });
    });
});
