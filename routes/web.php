<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\GreenAcademyController;
use App\Http\Controllers\MarkerDetailController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EcoReporterController;
use App\Http\Middleware\AdminMiddleware;

// ========================================================PUBLIK BISA AKSES=============================================================================

// --Home--
Route::get('/', [MapController::class, 'index']);

// --Auth--
Route::middleware('guest')->group(function () { 
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// --Detail Lokasi--
Route::get('/markers/{marker}', [MarkerDetailController::class, 'show'])->name('markers.show');

// --Rankings (PBI-17)--
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
Route::get('/api/leaderboard', [LeaderboardController::class, 'apiLeaderboard'])->name('api.leaderboard');

// Redirect halaman leaderboard lama ke yang baru
Route::redirect('/peringkat', '/leaderboard', 301);
Route::redirect('/eco-rankings', '/leaderboard', 301);

// --Reporter--
Route::prefix('pelaporan')->group(function () {
    Route::get('/', [EcoReporterController::class, 'create'])->name('reports.create');
    Route::post('/', [EcoReporterController::class, 'store'])->name('reports.store');
    Route::get('/berhasil', [EcoReporterController::class, 'success'])->name('reports.success');
});

// --Akademi--
Route::prefix('academy')->group(function () {
    Route::get('/', [GreenAcademyController::class, 'index'])->name('academy.index');
    Route::get('/materi/{id}', [GreenAcademyController::class, 'show'])->name('academy.show');
});

// ========================================================HARUS LOGIN=============================================================================

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // -- Profile --
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/settings', [ProfileController::class, 'settings'])->name('profile.settings');
        Route::post('/settings', [ProfileController::class, 'update'])->name('profile.settings.update');
        Route::post('/photo/delete', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
    });

    // --Reporter--
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // -- Akademi --
    Route::prefix('academy')->group(function () {
        Route::get('/kuis/{id}', [GreenAcademyController::class, 'quiz'])->name('academy.quiz');
        Route::post('/kuis/{id}', [GreenAcademyController::class, 'submitQuiz'])->name('academy.submit');
        Route::get('/hasil/{id}', [GreenAcademyController::class, 'result'])->name('academy.result');
    });
 
    // -- Aksi / Event --
    Route::prefix('aksi')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('aksi.index');
        Route::post('/{event}/join',  [EventController::class, 'join'])->name('aksi.join');
        Route::post('/{event}/leave', [EventController::class, 'leave'])->name('aksi.leave');
        Route::get('/{event}/chat', [EventController::class, 'chat'])->name('aksi.chat');
        Route::post('/{event}/chat/send', [EventController::class, 'sendMessage'])->name('aksi.chat.send');
    });

    // --Achievements--
    Route::get('/pencapaian/{user}', [RankingController::class, 'achievements'])->name('pencapaian.index');
    Route::get('/api/user/pencapaian/{user}', [RankingController::class, 'getUserAchievementsJson'])->name('user.pencapaian.json');


    // -- ADMIN TARUH SINI --
    Route::prefix('admin')->middleware(AdminMiddleware::class)->group(function () {

        // -- Markers --
        Route::get('/markers', [MapController::class, 'adminIndex'])->name('markers.index');
        Route::get('/markers/create', [MapController::class, 'create'])->name('markers.create');
        Route::post('/markers', [MapController::class, 'store'])->name('markers.store');
        Route::get('/markers/{marker}/edit', [MapController::class, 'edit'])->name('markers.edit');
        Route::post('/markers/{marker}/update', [MapController::class, 'update'])->name('markers.update');
        Route::post('/markers/{marker}/delete', [MapController::class, 'destroy'])->name('markers.destroy');

        // -- Aksi / Event (CRUD & kelola anggota) --
        Route::post('/aksi', [EventController::class, 'store'])->name('aksi.store');
        Route::post('/aksi/{event}/update', [EventController::class, 'update'])->name('aksi.update');
        Route::post('/aksi/{event}/delete', [EventController::class, 'destroy'])->name('aksi.destroy');
        Route::post('/aksi/{event}/members/{user_id}/remove', [EventController::class, 'removeMember'])->name('aksi.removeMember');
        Route::post('/aksi/{event}/chat/{message}/delete', [EventController::class, 'deleteMessage'])->name('aksi.chat.delete');

        // -- Reports (Admin CRUD) --
        Route::get('/reports', [ReportController::class, 'adminIndex'])->name('admin.reports.index');
        Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('admin.reports.edit');
        Route::post('/reports/{report}/update', [ReportController::class, 'update'])->name('admin.reports.update');
        Route::delete('/reports/{report}/delete', [ReportController::class, 'destroy'])->name('admin.reports.delete');
    });

    
});
