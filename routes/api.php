<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SlideshowController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\InformationRequestController;
use App\Http\Controllers\Api\GratificationController;
use App\Http\Controllers\Api\WbsController;
use App\Http\Controllers\Api\ViolationController;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

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

    // Question Routes
    Route::prefix('questions')->group(function () {
        Route::post('/', [QuestionController::class, 'submitQuestion']);
        Route::get('/{registration_code}', [QuestionController::class, 'checkQuestion']);
    });

    // Information Request Routes
    Route::prefix('information-requests')->group(function () {
        Route::post('/', [InformationRequestController::class, 'submitRequest']);
        Route::get('/{registration_code}', [InformationRequestController::class, 'checkRequest']);
    });

    // Gratification Routes
    Route::prefix('gratification')->group(function () {
        Route::post('/reports', [GratificationController::class, 'submitReport']);
        Route::get('/reports/{report_code}', [GratificationController::class, 'checkReport']);
    });

    // WBS Routes
    Route::prefix('wbs')->group(function () {
        Route::post('/reports', [WbsController::class, 'submitReport']);
        Route::get('/reports/{report_code}', [WbsController::class, 'checkReport']);
    });

    // Master Data Routes
    Route::get('/violations', [ViolationController::class, 'index']);

    // File Download Route
    Route::get('/download', [\App\Http\Controllers\DownloadController::class, 'download']);

    // ==========================================
    // MOBILE APP ROUTES (BDIBOOKING FRONTEND)
    // ==========================================
    Route::post('/login', [\App\Http\Controllers\Api\MobileAuthController::class, 'login']);

    // Public Mobile Routes
    Route::get('/property-types', [\App\Http\Controllers\Api\MobilePropertyTypeController::class, 'index']);
    Route::get('/property-types/{id}', [\App\Http\Controllers\Api\MobilePropertyTypeController::class, 'show']);
    Route::get('/properties', [\App\Http\Controllers\Api\MobilePropertyController::class, 'index']);
    Route::get('/properties/{id}', [\App\Http\Controllers\Api\MobilePropertyController::class, 'show']);

    // Protected Mobile Routes (Requires Sanctum Token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Api\MobileAuthController::class, 'logout']);
        Route::get('/user', [\App\Http\Controllers\Api\MobileAuthController::class, 'user']);
        
        Route::get('/bookings', [\App\Http\Controllers\Api\MobileBookingController::class, 'index']);
        Route::post('/bookings', [\App\Http\Controllers\Api\MobileBookingController::class, 'store']);
        Route::get('/bookings/{id}', [\App\Http\Controllers\Api\MobileBookingController::class, 'show']);
    });

});
