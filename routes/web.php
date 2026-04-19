<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
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

// --Feature--
Route::get('/pelaporan', function () {
    return view('eco-reporter');
})->name('eco-reporter');

Route::post('/pelaporan', [ReportController::class, 'store'])->name('pelaporan.store');

// --User--
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::post('/profile/settings', [ProfileController::class, 'update'])->name('profile.settings.update');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
 
    // ── Aksi / Event ──────────────────────────────────────────────
    Route::get('/aksi', [EventController::class, 'index'])->name('aksi.index');

    // Regular user: join & leave event
    Route::post('/aksi/{event}/join',  [EventController::class, 'join'])->name('aksi.join');
    Route::post('/aksi/{event}/leave', [EventController::class, 'leave'])->name('aksi.leave');

    // Chat grup event (hanya setelah join)
    Route::get('/aksi/{event}/chat',       [EventController::class, 'chat'])->name('aksi.chat');
    Route::post('/aksi/{event}/chat/send', [EventController::class, 'sendMessage'])->name('aksi.chat.send');

    // Admin: CRUD event & kelola anggota
    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::post('/aksi',                           [EventController::class, 'store'])->name('aksi.store');
        Route::post('/aksi/{event}/update',            [EventController::class, 'update'])->name('aksi.update');
        Route::post('/aksi/{event}/delete',            [EventController::class, 'destroy'])->name('aksi.destroy');
        Route::post('/aksi/{event}/members/{user_id}/remove', [EventController::class, 'removeMember'])->name('aksi.removeMember');
        // Admin: delete chat message
        Route::post('/aksi/{event}/chat/{message}/delete', [EventController::class, 'deleteMessage'])->name('aksi.chat.delete');
    });
    // ─────────────────────────────────────────────────────────────

    // --Achievements--
    Route::get('/pencapaian/{user}', [RankingController::class, 'achievements'])->name('pencapaian.index');
    Route::get('/api/user/pencapaian/{user}', [RankingController::class, 'getUserAchievementsJson'])->name('user.pencapaian.json');

    Route::prefix('admin')->middleware(AdminMiddleware::class)->group(function () {
        Route::post('/markers', [MapController::class, 'store'])->name('markers.store');
    });

    Route::post('/profile/photo/delete', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
});