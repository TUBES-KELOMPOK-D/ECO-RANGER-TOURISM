<?php

namespace App\Http\Controllers;

use App\Services\LeaderboardService;
use App\Models\Report;
use App\Models\Event;
use App\Models\ForumDiskusi;
use App\Models\SharedContent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    protected $leaderboardService;

    public function __construct(LeaderboardService $leaderboardService)
    {
        $this->leaderboardService = $leaderboardService;
    }

    /**
     * Tampilkan halaman leaderboard utama
     */
    public function index(Request $request)
    {
        // Ambil data leaderboard dengan pagination
        $perPage = $request->get('per_page', 10);
        
        // Cek apakah ada pencarian
        if ($request->has('search') && $request->search != '') {
            $leaderboard = $this->leaderboardService->searchLeaderboard($request->search, $perPage);
        } else {
            $leaderboard = $this->leaderboardService->getLeaderboard($perPage);
        }
        
        // Top 3 untuk podium
        $topThree = $this->leaderboardService->getTopThree();
        
        // Posisi user saat ini
        $currentUser = Auth::user();
        $userPosition = null;
        $userPoints = 0;
        
        if ($currentUser) {
            $position = $this->leaderboardService->getUserPosition($currentUser);
            if ($position) {
                $userPosition = $position->rank;
                $userPoints = $position->total_points;
            }
        }
        
        $badges = collect();
        if ($currentUser) {
            $badgeService = app(\App\Services\BadgeCheckerService::class);
            // Ensure badges are up to date
            $badgeService->checkAndAwardBadges($currentUser);
            $badges = $badgeService->getAllBadgesWithProgress($currentUser)->take(5);
        }
        
        // Mengambil data aturan poin dari RankingService dan database (jika ada)
        $pointRules = [
            ['activity' => 'Lapor Isu Lingkungan', 'points' => \App\Services\RankingService::POINT_RULES['report_issue'] ?? 10],
            ['activity' => 'Ikuti event Lingkungan', 'points' => \App\Services\RankingService::POINT_RULES['community_action'] ?? 50],
            ['activity' => 'Laporan Diverifikasi', 'points' => \App\Services\RankingService::POINT_RULES['verify_report'] ?? 5],
            // Untuk modul akademi, ini diatur langsung dari poin di tabel artikels per modul (default 20)
            ['activity' => 'Menyelesaikan Modul Akademi', 'points' => \App\Models\Artikel::first()->points ?? 20],
        ];
        
        // Mengambil voucher secara dinamis dari database untuk Top 3
        $vouchers = \App\Models\Voucher::orderBy('id', 'asc')->take(3)->get();
        
        $rewards = [];
        if ($vouchers->count() >= 3) {
            $rewards = [
                1 => ['title' => 'Juara 1', 'reward' => $vouchers[0]->name],
                2 => ['title' => 'Juara 2', 'reward' => $vouchers[1]->name],
                3 => ['title' => 'Juara 3', 'reward' => $vouchers[2]->name],
            ];
        } else {
            // Fallback default jika tabel belum diisi penuh
            $rewards = [
                1 => ['title' => 'Juara 1', 'reward' => 'Voucher Wisata Rp 500.000'],
                2 => ['title' => 'Juara 2', 'reward' => 'Voucher Wisata Rp 250.000'],
                3 => ['title' => 'Juara 3', 'reward' => 'Voucher Wisata Rp 100.000'],
            ];
        }
        
        return view('leaderboard.index', compact(
            'leaderboard',
            'topThree',
            'userPosition',
            'userPoints',
            'pointRules',
            'rewards',
            'badges',
            'perPage'
        ));
    }
    
    // Metode getUserBadges dihapus karena sudah memakai BadgeCheckerService langsung
    
    /**
     * API endpoint untuk mengambil data leaderboard (AJAX)
     */
    public function apiLeaderboard(Request $request)
    {
        $perPage = $request->get('per_page', 25);
        
        if ($request->has('search')) {
            $leaderboard = $this->leaderboardService->searchLeaderboard($request->search, $perPage);
        } else {
            $leaderboard = $this->leaderboardService->getLeaderboard($perPage);
        }
        
        return response()->json([
            'success' => true,
            'data' => $leaderboard->items(),
            'pagination' => [
                'current_page' => $leaderboard->currentPage(),
                'last_page' => $leaderboard->lastPage(),
                'per_page' => $leaderboard->perPage(),
                'total' => $leaderboard->total(),
            ],
            'top_three' => $this->leaderboardService->getTopThree(),
        ]);
    }
}
