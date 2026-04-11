<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Summary eco points, aktivitas terakhir, dan voucher user yang sedang login.
     *
     * GET /api/user/summary (auth:sanctum, role:user)
     */
    public function summary(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // Ambil data poin terbaru (aktivitas terakhir)
        $lastPoin    = $user->poin()->latest()->first();
        $totalPoints = (int) $user->poin()->sum('points');

        // Data voucher user
        $voucherCount       = $user->userVouchers()->count();
        $activeVoucherCount = $user->userVouchers()
            ->where('status', 'menunggu')
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Summary user berhasil diambil.',
            'data'    => [
                'user'  => $user->only(['id', 'name', 'email', 'role']),
                'stats' => [
                    'total_points'         => $totalPoints,
                    'last_activity'        => $lastPoin?->created_at,
                    'last_activity_source' => $lastPoin?->source,
                    'voucher_count'        => $voucherCount,
                    'active_voucher_count' => $activeVoucherCount,
                ],
            ],
        ], 200);
    }

    /**
     * Profil lengkap user yang sedang login.
     *
     * GET /api/user/profile (auth:sanctum, role:user)
     */
    public function profile(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        // Riwayat poin (10 terakhir)
        $poinHistory = $user->poin()
            ->latest()
            ->take(10)
            ->get(['id', 'points', 'source', 'created_at']);

        return response()->json([
            'success' => true,
            'message' => 'Profil user berhasil diambil.',
            'data'    => [
                'user'         => $user->only(['id', 'name', 'email', 'role', 'created_at']),
                'poin_history' => $poinHistory,
            ],
        ], 200);
    }
}
