<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GoogleMapsProxyController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PotholeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['api', 'auth:sanctum'], 'prefix' => 'v1'], function () {
    
    Route::get('/user', [UserController::class, 'index']);
    Route::apiResource('potholes', PotholeController::class);
    Route::post('/potholes/{pothole}/predict', [PotholeController::class, 'predict']);

    Route::post('/logout', [AuthController::class, 'logout']);

});



Route::group(['middleware' => 'api', 'prefix' => 'v1'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::prefix('google-maps-proxy')->group(function () {
        Route::get('/js', [GoogleMapsProxyController::class, 'js']);
        Route::get('/geocode/{endpoint}', [GoogleMapsProxyController::class, 'geocode'])->where('endpoint', '.*');
        Route::get('/place/{endpoint}', [GoogleMapsProxyController::class, 'place'])->where('endpoint', '.*');
    });
});
