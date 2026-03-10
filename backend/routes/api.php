<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PropertyTypeController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\BookingController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('property-types', PropertyTypeController::class);
    Route::apiResource('properties', PropertyController::class);
    Route::apiResource('bookings', BookingController::class);
});
