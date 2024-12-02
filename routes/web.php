<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CompetencyController;
use App\Http\Controllers\CoreController;
use App\Http\Controllers\GratificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IbizaController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\WBSController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

//News
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/post/{year}/{month}/{category}/{news}/{title}', [NewsController::class, 'show'])->name('news.post');

//Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/post/{year}/{month}/{category}/{blog}/{title}', [BlogController::class, 'show'])->name('blog.post');

//Core
Route::get('/information', [CoreController::class, 'index'])->name('information.home');

// Profil
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');
Route::get('/information/post/{id}/{slug}', [InformationController::class, 'show'])->name('information.post');

// Program Kerja
Route::get('/training', [TrainingController::class, 'index'])->name('training.index');
Route::get('/ibiza', [IbizaController::class, 'index'])->name('ibiza.index');
Route::get('/competency', [CompetencyController::class, 'index'])->name('competency.index');

// Zona Integritas
Route::get('/gratification', [GratificationController::class, 'index'])->name('gratification.index');
Route::get('/wbs', [WBSController::class, 'index'])->name('wbs.index');
Route::get('/information/question', [QuestionController::class, 'question'])->name('information.question');
Route::post('/submit-form', [QuestionController::class, 'submit'])->name('question.submit');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');
});
