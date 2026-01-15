<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ResultController\TopFiveSelectionResultController;
use App\Http\Controllers\ResultController\TopFiveCandidateResultController;
use App\Http\Controllers\TopFiveSelectionScoreController;
use App\Http\Controllers\TopFiveCandidateController;
use App\Http\Controllers\TopFiveScoreController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Categories Routes
Route::middleware('auth')->group(function () {
    Route::get('/creative-attire', [CandidateController::class, 'creative_attire'])
        ->name('creative_attire');

    Route::get('/casual-wear', [CandidateController::class, 'casual_wear'])
        ->name('casual_wear');

    Route::get('/swim-wear', [CandidateController::class, 'swim_wear'])
        ->name('swim_wear');

    Route::get('/talent', [CandidateController::class, 'talent'])
        ->name('talent');

    Route::get('/gown', [CandidateController::class, 'gown'])
        ->name('gown');

    Route::get('/q-and-a', [CandidateController::class, 'q_and_a'])
        ->name('q_and_a');

    Route::get('/beauty', [CandidateController::class, 'beauty'])
        ->name('beauty');
});


Route::middleware('auth')->group(function () {
    Route::post('/creative-attire/scores', [TopFiveSelectionScoreController::class, 'creative_attire_store'])
        ->name('creative_attire.store');

    Route::post('/casual-wear/scores', [TopFiveSelectionScoreController::class, 'casual_wear_store'])
        ->name('casual_wear.store');

    Route::post('/swim-wear/scores', [TopFiveSelectionScoreController::class, 'swim_wear_store'])
        ->name('swim_wear.store');

    Route::post('/talent/scores', [TopFiveSelectionScoreController::class, 'talent_store'])
        ->name('talent.store');

    Route::post('/gown/scores', [TopFiveSelectionScoreController::class, 'gown_store'])
        ->name('gown.store');

    Route::post('/q-and-a/scores', [TopFiveSelectionScoreController::class, 'q_and_a_store'])
        ->name('q_and_a.store');

    Route::post('/beauty/scores', [TopFiveSelectionScoreController::class, 'beauty_store'])
        ->name('beauty.store');
});


// Admin Results Routes (Top 5 Selection)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/creative-attire', [TopFiveSelectionResultController::class, 'creativeAttireResults'])
        ->name('admin.creative_attire');

    Route::get('/admin/casual-wear', [TopFiveSelectionResultController::class, 'casualWearResults'])
        ->name('admin.casual_wear');

    Route::get('/admin/swim-wear', [TopFiveSelectionResultController::class, 'swimWearResults'])
        ->name('admin.swim_wear');

    Route::get('/admin/talent', [TopFiveSelectionResultController::class, 'talentResults'])
        ->name('admin.talent');

    Route::get('/admin/gown', [TopFiveSelectionResultController::class, 'gownResults'])
        ->name('admin.gown');

    Route::get('/admin/q-and-a', [TopFiveSelectionResultController::class, 'qAndAResults'])
        ->name('admin.q_and_a');

    Route::get('/admin/beauty', [TopFiveSelectionResultController::class, 'beautyResults'])
        ->name('admin.beauty');

    // Optional: Top Five selection summary
    Route::get('/admin/top-five-selection', [TopFiveSelectionResultController::class, 'topFiveSelectionResults'])
        ->name('admin.top_five_selection');
});


// Category Routes (Top 5 Finalists)
Route::middleware('auth')->group(function () {

    // Display Routes
    Route::get('/final_q_and_a', [TopFiveCandidateController::class, 'final_q_and_a'])
        ->name('final_q_and_a');

    // Store Routes
    Route::post('/final-q-and-a/store', [TopFiveScoreController::class, 'finalQAStore'])
        ->name('final_q_and_a.store');
});

// Admin Set Top 5 Candidates Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/top-five', [TopFiveSelectionResultController::class, 'setTopFive'])
        ->name('topFive.set');
});


//Admin Top 5 Candidates Result Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get(
        '/final_q_and_a/results',
        [TopFiveCandidateResultController::class, 'finalQAResults']
    )->name('admin.final_q_and_a');
    Route::get('/admin/total_results', [TopFiveCandidateResultController::class, 'totalResults'])
        ->name('admin.top_five_finalist');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
