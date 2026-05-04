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
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\UserController;
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

// --Laporan Publik--
Route::get('/laporan', [ReportController::class, 'publicIndex'])->name('reports.public');
Route::get('/laporan/{report}', [ReportController::class, 'show'])->name('reports.show');

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
        Route::post('/{event}/chat/{message}/react', [EventController::class, 'react'])->name('aksi.chat.react');
    });

    // --Achievements--
    Route::get('/pencapaian/{user}', [RankingController::class, 'achievements'])->name('pencapaian.index');
    Route::get('/api/user/pencapaian/{user}', [RankingController::class, 'getUserAchievementsJson'])->name('user.pencapaian.json');

    // -- Badges (PBI-18) --
    Route::get('/badges', [\App\Http\Controllers\BadgeController::class, 'index'])->name('badges.index');
    Route::get('/api/user-badges', [\App\Http\Controllers\BadgeController::class, 'apiUserBadges'])->name('api.user.badges');

    // -- Vouchers (PBI-18) --
    Route::get('/vouchers', [\App\Http\Controllers\VoucherController::class, 'index'])->name('vouchers.index');
    Route::post('/vouchers/{voucher}/claim', [\App\Http\Controllers\VoucherController::class, 'claim'])->name('vouchers.claim');
    Route::post('/vouchers/{voucher}/use', [\App\Http\Controllers\VoucherController::class, 'useVoucher'])->name('vouchers.use');

    // -- Review & Ulasan (standalone, tidak terkait laporan) --
    Route::post('/markers/{marker}/reviews', [ReviewController::class, 'store'])->name('markers.reviews.store');

    // -- ADMIN TARUH SINI --
    Route::prefix('admin')->middleware(AdminMiddleware::class)->group(function () {

        // -- Dashboard --
        Route::get('/dashboard', function () {
            return redirect()->route('users.index');
        })->name('admin.dashboard');

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

        // -- PBI-26: Kelola Peringkat (Point Rules, Badges, Leaderboard) --
        Route::get('/leaderboard/admin/point-rules', [\App\Http\Controllers\LeaderboardController::class, 'managePointRules'])->name('leaderboard.admin.point_rules');
        Route::post('/leaderboard/admin/point-rules', [\App\Http\Controllers\LeaderboardController::class, 'storePointRule'])->name('leaderboard.admin.point_rules.store');
        Route::put('/leaderboard/admin/point-rules/{rule}', [\App\Http\Controllers\LeaderboardController::class, 'updatePointRule'])->name('leaderboard.admin.point_rules.update');
        Route::post('/leaderboard/admin/point-rules/{rule}/delete', [\App\Http\Controllers\LeaderboardController::class, 'destroyPointRule'])->name('leaderboard.admin.point_rules.destroy');
        
        Route::get('/leaderboard/admin/badges', [\App\Http\Controllers\LeaderboardController::class, 'manageBadges'])->name('leaderboard.admin.badges');
        Route::post('/leaderboard/admin/badges', [\App\Http\Controllers\LeaderboardController::class, 'storeBadge'])->name('leaderboard.admin.badges.store');
        Route::put('/leaderboard/admin/badges/{badge}', [\App\Http\Controllers\LeaderboardController::class, 'updateBadge'])->name('leaderboard.admin.badges.update');
        Route::post('/leaderboard/admin/badges/{badge}/delete', [\App\Http\Controllers\LeaderboardController::class, 'destroyBadge'])->name('leaderboard.admin.badges.destroy');

        Route::get('/leaderboard/admin/vouchers', [\App\Http\Controllers\LeaderboardController::class, 'manageVouchers'])->name('leaderboard.admin.vouchers');
        Route::post('/leaderboard/admin/vouchers', [\App\Http\Controllers\LeaderboardController::class, 'storeVoucher'])->name('leaderboard.admin.vouchers.store');
        Route::put('/leaderboard/admin/vouchers/{voucher}', [\App\Http\Controllers\LeaderboardController::class, 'updateVoucher'])->name('leaderboard.admin.vouchers.update');
        Route::post('/leaderboard/admin/vouchers/{voucher}/delete', [\App\Http\Controllers\LeaderboardController::class, 'destroyVoucher'])->name('leaderboard.admin.vouchers.destroy');

        Route::get('/leaderboard/admin/tips', [\App\Http\Controllers\LeaderboardController::class, 'manageTips'])->name('leaderboard.admin.tips');
        Route::post('/leaderboard/admin/tips', [\App\Http\Controllers\LeaderboardController::class, 'storeTip'])->name('leaderboard.admin.tips.store');
        Route::put('/leaderboard/admin/tips/{tip}', [\App\Http\Controllers\LeaderboardController::class, 'updateTip'])->name('leaderboard.admin.tips.update');
        Route::post('/leaderboard/admin/tips/{tip}/delete', [\App\Http\Controllers\LeaderboardController::class, 'destroyTip'])->name('leaderboard.admin.tips.destroy');

        Route::post('/leaderboard/admin/reset', [\App\Http\Controllers\LeaderboardController::class, 'resetLeaderboard'])->name('leaderboard.admin.reset');
        Route::post('/leaderboard/admin/adjust', [\App\Http\Controllers\LeaderboardController::class, 'adjustPoints'])->name('leaderboard.admin.adjust');
        // -- Users (Profile Management) --
        Route::resource('users', UserController::class);
    });

    
});
