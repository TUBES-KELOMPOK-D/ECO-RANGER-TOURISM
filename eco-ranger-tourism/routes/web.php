<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\EcoRankingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Middleware\AdminMiddleware;

// --Home--
Route::get('/', [MapController::class, 'index']);

// --Eco Rankings (Complete Feature)--
Route::get('/eco-rankings', [EcoRankingController::class, 'index'])->name('eco.rankings');
Route::get('/peringkat', [EcoRankingController::class, 'index'])->name('peringkat.index');
Route::get('/peringkat/dashboard', [RankingController::class, 'dashboard'])->name('peringkat.dashboard');
Route::get('/api/peringkat/leaderboard', [RankingController::class, 'getLeaderboardJson'])->name('peringkat.leaderboard.json');
Route::get('/api/peringkat/point-rules', [RankingController::class, 'getPointRulesJson'])->name('peringkat.rules.json');

// --Public--
Route::middleware('guest')->group(function () { 
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// --User--
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::post('/profile/settings', [ProfileController::class, 'update'])->name('profile.settings.update');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // --Achievements--
    Route::get('/pencapaian/{user}', [RankingController::class, 'achievements'])->name('pencapaian.index');
    Route::get('/api/user/pencapaian/{user}', [RankingController::class, 'getUserAchievementsJson'])->name('user.pencapaian.json');

    Route::prefix('admin')->middleware(AdminMiddleware::class)->group(function () {
        Route::post('/markers', [MapController::class, 'store'])->name('markers.store');
    });

    Route::post('/profile/photo/delete', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
});