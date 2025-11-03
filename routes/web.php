<?php
use Illuminate\Support\Facades\Route;
use App\Filament\Pages\ArchivePage;

Route::get('/', \App\Livewire\Home::class)->name('home');

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::get('/information', App\Livewire\Articles\Information::class)->name('articles.information');

Route::get('/{article_type}', App\Livewire\Articles\Index::class)
    ->whereIn('article_type', ['news','gallery','page'])
    ->name('articles.index');

Route::get('/{article_type}/{slug}', App\Livewire\Articles\Show::class)
    ->whereIn('article_type', ['news','gallery','page','information'])
    ->name('articles.show');

//VirtualTour
Route::get('/virtualtour', \App\Livewire\VirtualTour::class)->name('virtualtour');

Route::get('/register', \App\Livewire\Training\Register::class)->name('register');

Route::get('/training/detail/{id_diklat}/{slug?}', \App\Livewire\Training\Detail::class)->name('training.detail');

Route::get('/register/training/{id_diklat}/{slug?}', \App\Livewire\Training\Registration::class)->name('training.register');

Route::get('/training/presence/{id_diklat}/{slug?}', \App\Livewire\Training\Presence::class)->name('training.presence');

// Archive export route ///filament perlu cek di bawah ini karena ekspos URL
Route::get('/archive/export', [ArchivePage::class, 'exportToExcel'])->name('archive.export');

// Rute untuk Laporan Gratifikasi
Route::get('/gratification', App\Livewire\Gratification\Gratification::class)->name('gratification');


/*
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CompetencyController;
use App\Http\Controllers\CoreController;
use App\Http\Controllers\GratificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IbizaController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\WBSController;
use App\Models\Information;

Route::get('/', HomeController::class)->name('home');

Route::get('/archive/posted/{year}/{month}', [ArchiveController::class, 'posted'])->name('archive.posted');

//News
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/category/{categoryId}/{categorySlug}', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/post/{year}/{month}/{category}/{news}/{title}', [NewsController::class, 'show'])->name('news.post');

//Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/category/{categoryId}/{categorySlug}', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/post/{year}/{month}/{category}/{blog}/{title}', [BlogController::class, 'show'])->name('blog.post');

//Information
Route::get('/information', [InformationController::class, 'home'])->name('information.home');
Route::get('/information/post/{id}/{slug}', [InformationController::class, 'show'])->name('information.post');
Route::get('information/core/{slug}', [InformationController::class, 'showCore'])->name('information.core');
Route::get('/information/question', [QuestionController::class, 'question'])->name('information.question');
Route::get('/information/procedure/{type?}', [InformationController::class, 'procedure'])->name('information.procedure');
Route::get('/information/request', function() {
    return view('information.request');
});
Route::get('/information/provision', function() {
    return view('information.provision');
});

//Booking
Route::get('/seminar', [BookingController::class, 'index'])->name('booking.index');
Route::get('/seminar/{id}/{title}', [BookingController::class, 'show'])->name('booking.post');
Route::get('/seminar/{id}/{title}/checkout', [BookingController::class, 'detail'])->middleware('auth')->name('booking.detail');
Route::post('/seminar/{seminar}/peserta', [ParticipantController::class, 'store'])->middleware('auth')->name('participant.store');

// Page
Route::get('/page/post/{slug}', [PageController::class, 'show'])->name('page.post');

// Program Kerja
Route::get('/training', [TrainingController::class, 'index'])->name('training.index');
Route::get('/ibiza', [IbizaController::class, 'index'])->name('ibiza.index');
Route::get('/competency', [CompetencyController::class, 'index'])->name('competency.index');

// Zona Integritas
Route::get('/gratification', [GratificationController::class, 'index'])->name('gratification.index');
Route::get('/wbs', [WBSController::class, 'index'])->name('wbs.index');
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
*/
