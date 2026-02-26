<?php
use Illuminate\Support\Facades\Route;
use App\Filament\Pages\ArchivePage;
use App\Http\Controllers\CompetencyDownloadController;
use App\Livewire\Competency\DataTable as CompetencyDataTable;
use App\Livewire\Competency\Index as CompetencyIndex;
use App\Livewire\Competency\LspDetail;
use App\Livewire\Competency\SkkniDetail;
use App\Livewire\Training\Index as TrainingIndex;
use App\Models\InformationRequest;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Controllers\DownloadController;


use App\Livewire\Auth\Login as ParticipantLogin;
use App\Livewire\Auth\Register as ParticipantRegister;
use App\Livewire\Participant\Dashboard as ParticipantDashboard;
use App\Livewire\Participant\Enrolled as ParticipantEnrolled;
use App\Livewire\Participant\Completed as ParticipantCompleted;
use App\Livewire\Participant\Profile as ParticipantProfile;

Route::get('/', \App\Livewire\Home::class)->name('home');

// Participant Auth Routes
Route::prefix('participant')->name('participant.')->group(function () {
    Route::get('/login', ParticipantLogin::class)->name('login');
    Route::get('/register', ParticipantRegister::class)->name('register');
    Route::get('/ktp-login', \App\Livewire\Auth\KtpLogin::class)->name('ktp.login');
    
    Route::middleware('auth:participant')->group(function () {
        Route::get('/dashboard', ParticipantDashboard::class)->name('dashboard');
        Route::get('/enrolled', ParticipantEnrolled::class)->name('enrolled');
        Route::get('/completed', ParticipantCompleted::class)->name('completed');
        Route::get('/profile', ParticipantProfile::class)->name('profile');
    });
});

Route::prefix('competency')->name('competency.')->group(function () {
    Route::get('/', CompetencyIndex::class)->name('index');

    Route::get('/skkni/download/{skkniId}/{slug?}', CompetencyDownloadController::class)
        ->whereUuid('skkniId')
        ->name('skkni.download');

    Route::get('/skkni/{skkniId}/{slug?}', SkkniDetail::class)
        ->whereUuid('skkniId')
        ->name('skkni.show');

    Route::get('/lsp/{lspId}/{slug?}', LspDetail::class)
        ->whereNumber('lspId')
        ->name('lsp.show');

    Route::get('/lsp/{tab}/{lspId}/{slug?}', function (string $tab, int $lspId, ?string $slug = null) {
        return redirect()->route('competency.lsp.show', array_filter([
            'lspId' => $lspId,
            'slug' => $slug,
            'tab' => $tab,
        ], static fn ($value) => !is_null($value)));
    })->whereIn('tab', ['assessor', 'tuk', 'scheme'])
        ->whereNumber('lspId')
        ->name('lsp.legacy-tab');

    Route::get('/lsp/unit/{lspId}/{schemeId}/{slug?}', function (int $lspId, int $schemeId, ?string $slug = null) {
        return redirect()->route('competency.lsp.show', array_filter([
            'lspId' => $lspId,
            'slug' => $slug,
            'tab' => 'scheme',
            'scheme' => $schemeId,
        ], static fn ($value) => !is_null($value)));
    })->whereNumber('lspId')
        ->whereNumber('schemeId')
        ->name('lsp.legacy-unit');

    Route::get('/{section}', CompetencyDataTable::class)
        ->whereIn('section', ['skkni', 'lsp', 'assessor', 'tuk', 'scheme'])
        ->name('section');
});

Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

Route::get('/information', App\Livewire\Articles\Information::class)->name('articles.information');
Route::get('/information/question', App\Livewire\Information\Question::class)->name('information.question');
Route::get('/information/question/status', App\Livewire\Information\Question::class)->name('information.question.status');
Route::get('/information/question/response', App\Livewire\Information\QuestionResponse::class)->name('information.question.response');
Route::get('/information/request', App\Livewire\Information\Request::class)->name('information.request');
Route::get('/information/request/status', App\Livewire\Information\Request::class)->name('information.request.status');
Route::get('/information/request/response', App\Livewire\Information\RequestResponse::class)->name('information.request.response');
Route::get('/information/question/response/{registration_code}', App\Livewire\Information\QuestionResponse::class)->name('information.question.response.code');
Route::get('/information/request/response/{registration_code}', App\Livewire\Information\RequestResponse::class)->name('information.request.response.code');
Route::get('/information/provision', function() {
    abort(404);
})->name('information.provision');

Route::get('/information/procedure/{type?}', [\App\Http\Controllers\InformationController::class, 'procedure'])->name('information.procedure');

Route::get('/information/answer', \App\Livewire\Information\Answer::class)->name('information.answer');
Route::get('/information/answer/detail/{registration_code}', \App\Livewire\Information\AnswerDetail::class)->name('information.answer.detail');

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

Route::get('/register/training/sdma-option/{id_diklat}/{slug?}', \App\Livewire\Training\SdmaOption::class)->name('training.sdma-option');

Route::get('/register/training/kemenperin/{id_diklat}/{slug?}', \App\Livewire\Training\KemenperinRegistration::class)->name('training.kemenperin-register');

Route::get('/training', TrainingIndex::class)->name('training.index');
Route::get('/pnbp', \App\Livewire\Pnbp\Index::class)->name('pnbp.index');
Route::get('/pnbp/detail/{id_diklat}/{slug?}', \App\Livewire\Pnbp\Detail::class)->name('pnbp.detail');
Route::get('/pnbp/register/{id_diklat}/{slug?}', \App\Livewire\Pnbp\Register::class)->name('training.pnbp.register');
Route::get('/training/presence/{id_diklat}/{slug?}', \App\Livewire\Training\Presence::class)->name('training.presence');
Route::get('/training/{page?}', function ($page = 'index') {
    // prevent directory traversal from page name
    $page = str_replace('..', '', $page);
    if (view()->exists("training.{$page}")) {
        return view("training.{$page}");
    }
    abort(404);
})->where('page', '[a-zA-Z0-9_\-]+')->name('training.page');

// Archive export route ///filament perlu cek di bawah ini karena ekspos URL
Route::get('/archive/export', [ArchivePage::class, 'exportToExcel'])->name('archive.export');

// Rute untuk Laporan Gratifikasi
Route::get('/gratification', App\Livewire\Gratification\Gratification::class)->name('gratification');
Route::get('/gratification/form', App\Livewire\Gratification\Gratification::class)->name('gratification.form');
Route::get('/gratification/status', App\Livewire\Gratification\Gratification::class)->name('gratification.status');
Route::get('/gratification/report', App\Livewire\Gratification\Gratification::class)->name('gratification.report');
Route::get('/gratification/response', App\Livewire\Gratification\Response::class)->name('gratification.response');

// Rute untuk Laporan WBS
Route::get('/wbs', App\Livewire\Wbs\Wbs::class)->name('wbs');
Route::get('/wbs/form', App\Livewire\Wbs\Wbs::class)->name('wbs.form');
Route::get('/wbs/status', App\Livewire\Wbs\Wbs::class)->name('wbs.status');
Route::get('/wbs/report', App\Livewire\Wbs\Wbs::class)->name('wbs.report');
Route::get('/wbs/response', App\Livewire\Wbs\Response::class)->name('wbs.response');

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
Route::get('/ibiza', App\Livewire\Ibiza\Index::class)->name('ibiza');

Route::get('/download', [DownloadController::class, 'download'])->name('download');
