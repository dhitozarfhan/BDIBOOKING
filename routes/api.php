<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SlideshowController;
use App\Http\Controllers\Api\InformationController;
use App\Http\Controllers\Api\GratificationController;
use App\Http\Controllers\Api\WbsController;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    
    // Authentication Routes
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
    });

    // Article Routes
    Route::prefix('articles')->group(function () {
        Route::get('/', [ArticleController::class, 'index']);
        Route::get('/{id}', [ArticleController::class, 'show'])->where('id', '[0-9]+');
        Route::get('/slug/{slug}', [ArticleController::class, 'showBySlug']);
    });

    // Category Routes
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{id}', [CategoryController::class, 'show']);
    });

    // Slideshow Routes
    Route::get('/slideshows', [SlideshowController::class, 'index']);

    // Information Routes
    Route::prefix('information')->group(function () {
        // Questions
        Route::post('/questions', [InformationController::class, 'submitQuestion']);
        Route::get('/questions/{registration_code}', [InformationController::class, 'checkQuestion']);
        
        // Information Requests
        Route::post('/requests', [InformationController::class, 'submitRequest']);
        Route::get('/requests/{registration_code}', [InformationController::class, 'checkRequest']);
    });

    // Gratification Routes
    Route::prefix('gratification')->group(function () {
        Route::post('/reports', [GratificationController::class, 'submitReport']);
        Route::get('/reports/{report_code}', [GratificationController::class, 'checkReport']);
        Route::get('/reports', [GratificationController::class, 'index'])->middleware('auth:sanctum');
    });

    // WBS Routes
    Route::prefix('wbs')->group(function () {
        Route::post('/reports', [WbsController::class, 'submitReport']);
        Route::get('/reports/{report_code}', [WbsController::class, 'checkReport']);
        Route::get('/reports', [WbsController::class, 'index'])->middleware('auth:sanctum');
    });

});

// Legacy route for backward compatibility
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
