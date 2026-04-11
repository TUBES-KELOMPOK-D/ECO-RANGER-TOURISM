<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — ECO-RANGER-TOURISM
|--------------------------------------------------------------------------
|
| PBI-01 — Keamanan Autentikasi Login & Register
| Semua endpoint API dilindungi dengan Sanctum dan/atau RoleMiddleware.
|
*/

// ==========================================================================
// Public Routes — Tidak memerlukan autentikasi
// throttle:5,1 = maksimal 5 request per 1 menit (brute-force protection)
// ==========================================================================

Route::post('/register', [AuthController::class, 'register'])
    ->middleware('throttle:5,1')
    ->name('auth.register');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('auth.login');

// ==========================================================================
// Authenticated Routes — Memerlukan Bearer Token (auth:sanctum)
// ==========================================================================

Route::middleware('auth:sanctum')->group(function (): void {

    // --- Auth ---
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('auth.logout');

    Route::post('/logout-all', [AuthController::class, 'logoutAll'])
        ->name('auth.logout-all');

    // -----------------------------------------------------------------------
    // User Routes — Hanya untuk role 'user'
    // -----------------------------------------------------------------------
    Route::middleware('role:user')->prefix('user')->name('user.')->group(function (): void {
        Route::get('/summary', [UserController::class, 'summary'])->name('summary');
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    });

    // -----------------------------------------------------------------------
    // Admin Routes — Hanya untuk role 'admin'
    // -----------------------------------------------------------------------
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function (): void {
        // Daftar user (dengan search, filter role, pagination)
        Route::get('/users', [AdminController::class, 'index'])->name('users.index');
        // Detail user
        Route::get('/users/{id}', [AdminController::class, 'show'])->name('users.show');
        // Update role user
        Route::patch('/users/{id}/role', [AdminController::class, 'updateRole'])->name('users.update-role');
    });

});
