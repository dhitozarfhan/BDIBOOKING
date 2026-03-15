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
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PropertyTypeApkController;
use App\Http\Controllers\Api\PropertyApkController;
use App\Http\Controllers\Api\BookingApkController;
use App\Http\Controllers\Api\RoomApkController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\BookingController;

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


    // Unified Authentication
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/loginAPK', [AuthController::class, 'loginAPK']); // Points to bypass login method

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);

        // Properties
        Route::get('/property-types', [PropertyApkController::class, 'getTypes']);
        Route::get('/properties', [PropertyApkController::class, 'index']);
        Route::post('/properties', [PropertyApkController::class, 'store']);
        Route::get('/properties/{id}', [PropertyApkController::class, 'show']);
        Route::put('/properties/{id}', [PropertyApkController::class, 'update']);
        Route::delete('/properties/{id}', [PropertyApkController::class, 'destroy']);

        // Bookings
        Route::get('/my-bookings', [BookingApkController::class, 'index']);
        Route::get('/bookings', [BookingApkController::class, 'index']);
        Route::post('/bookings', [BookingApkController::class, 'store']);
        Route::get('/bookings/{id}', [BookingApkController::class, 'show']);
        Route::put('/bookings/{id}', [BookingApkController::class, 'update']);
        Route::delete('/bookings/{id}', [BookingApkController::class, 'destroy']);

        // Rooms
        Route::get('/rooms', [RoomApkController::class, 'index']);
        Route::post('/rooms', [RoomApkController::class, 'store']);
        Route::get('/rooms/{id}', [RoomApkController::class, 'show']);
        Route::put('/rooms/{room}', [RoomApkController::class, 'update']);
        Route::delete('/rooms/{room}', [RoomApkController::class, 'destroy']);
    });
});

