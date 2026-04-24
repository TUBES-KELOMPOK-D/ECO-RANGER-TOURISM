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
        
        
        $badges = [];
        if ($currentUser) {
            $badges = $this->getUserBadges($currentUser);
        }
        
        // Data aturan poin untuk ditampilkan
        $pointRules = [
            ['activity' => 'Lapor Isu Lingkungan', 'points' => 10, 'icon' => '📋'],
            ['activity' => 'Ikut Aksi Komunitas', 'points' => 50, 'icon' => '🤝'],
            ['activity' => 'Verifikasi Laporan', 'points' => 5, 'icon' => '✅'],
            ['activity' => 'Diskusi Forum', 'points' => 15, 'icon' => '💬'],
            ['activity' => 'Bagikan Konten', 'points' => 20, 'icon' => '📤'],
        ];
        
        // Reward untuk top 3
        $rewards = [
            1 => ['title' => 'Rank #1', 'reward' => 'Voucher Wisata Rp500.000', 'icon' => '🥇'],
            2 => ['title' => 'Rank #2', 'reward' => 'Voucher Wisata Rp250.000', 'icon' => '🥈'],
            3 => ['title' => 'Rank #3', 'reward' => 'Voucher Wisata Rp100.000', 'icon' => '🥉'],
        ];
        
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
    
    /**
     * Hitung badge progress user
     */
    private function getUserBadges($user)
    {
        $badges = [];
        
        // Plastic Hunter: 10 laporan sampah
        $plasticReports = Report::where('user_id', $user->id)->count();
        $badges['plastic_hunter'] = [
            'name' => 'Plastic Hunter',
            'icon' => '🪣',
            'target' => 'Lapor 10 tumpukan sampah',
            'current' => $plasticReports,
            'max' => 10,
            'progress' => min(100, ($plasticReports / 10) * 100)
        ];
        
        // Tree Hugger: 5 event
        $treeEvents = Event::whereHas('participants', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();
        $badges['tree_hugger'] = [
            'name' => 'Tree Hugger',
            'icon' => '🌳',
            'target' => 'Ikut 5 aksi komunitas',
            'current' => $treeEvents,
            'max' => 5,
            'progress' => min(100, ($treeEvents / 5) * 100)
        ];
        
        // Turtle Saver: laporan yang diverifikasi
        $verifiedReports = Report::where('user_id', $user->id)
            ->where('status', 'diverifikasi')
            ->count();
        $badges['turtle_saver'] = [
            'name' => 'Turtle Saver',
            'icon' => '🐢',
            'target' => 'Verifikasi 3 laporan',
            'current' => $verifiedReports,
            'max' => 3,
            'progress' => min(100, ($verifiedReports / 3) * 100)
        ];
        
        // Eco-Speaker: 20 postingan forum
        $forumPosts = ForumDiskusi::where('user_id', $user->id)->count();
        $badges['eco_speaker'] = [
            'name' => 'Eco-Speaker',
            'icon' => '💬',
            'target' => '20 postingan forum',
            'current' => $forumPosts,
            'max' => 20,
            'progress' => min(100, ($forumPosts / 20) * 100)
        ];
        
        // Green Influencer: 10 konten dibagikan
        $sharedContents = SharedContent::where('user_id', $user->id)->count();
        $badges['green_influencer'] = [
            'name' => 'Green Influencer',
            'icon' => '📸',
            'target' => '10 konten dibagikan',
            'current' => $sharedContents,
            'max' => 10,
            'progress' => min(100, ($sharedContents / 10) * 100)
        ];
        
        return $badges;
    }
    
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
