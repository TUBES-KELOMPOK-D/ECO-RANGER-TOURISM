<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\UserVoucher;
use App\Services\LeaderboardService;

class VoucherController extends Controller
{
    public function index(LeaderboardService $leaderboardService)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $userRank = $leaderboardService->getUserRank($user);
        $vouchers = Voucher::all();
        $userVouchers = UserVoucher::where('user_id', $user->id)->get()->keyBy('voucher_id');

        return view('vouchers.index', compact('vouchers', 'userVouchers', 'userRank'));
    }

    public function claim(Request $request, Voucher $voucher, LeaderboardService $leaderboardService)
    {
        $user = auth()->user();
        
        $userRank = $leaderboardService->getUserRank($user);

        // Validasi Rank vs Voucher
        if ($voucher->id == 1 && $userRank !== 1) {
            return back()->with('error', 'Voucher senilai Rp 500.000 ini hanya khusus untuk Rank 1.');
        }
        if ($voucher->id == 2 && $userRank !== 2) {
            return back()->with('error', 'Voucher senilai Rp 250.000 ini hanya khusus untuk Rank 2.');
        }
        if ($voucher->id == 3 && $userRank !== 3) {
            return back()->with('error', 'Voucher senilai Rp 100.000 ini hanya khusus untuk Rank 3.');
        }

        // Cek apakah sudah pernah claim
        $alreadyClaimed = UserVoucher::where('user_id', $user->id)
                                    ->where('voucher_id', $voucher->id)
                                    ->exists();

        if ($alreadyClaimed) {
            return back()->with('error', 'Anda sudah mengklaim voucher ini sebelumnya.');
        }

        if ($user->eco_points < $voucher->poin_required) {
            return back()->with('error', 'Poin Anda tidak mencukupi untuk mengklaim voucher ini.');
        }

        // Jika butuh dikurangi:
        // $user->eco_points -= $voucher->poin_required;
        // $user->save();

        UserVoucher::create([
            'user_id' => $user->id,
            'voucher_id' => $voucher->id,
            'status' => 'menunggu'
        ]);

        return back()->with('success', 'Voucher berhasil diklaim! Silakan cek di bagian voucher Anda.');
    }

    public function useVoucher(Request $request, Voucher $voucher)
    {
        $user = auth()->user();

        $userVoucher = UserVoucher::where('user_id', $user->id)
                                    ->where('voucher_id', $voucher->id)
                                    ->first();

        if (!$userVoucher) {
            return back()->with('error', 'Anda belum mengklaim voucher ini.');
        }

        if ($userVoucher->status === 'digunakan') {
            return back()->with('error', 'Voucher ini sudah digunakan.');
        }

        $userVoucher->status = 'digunakan';
        $userVoucher->redeemed_at = now();
        $userVoucher->save();

        return back()->with('success', 'Voucher berhasil digunakan!');
    }
}
