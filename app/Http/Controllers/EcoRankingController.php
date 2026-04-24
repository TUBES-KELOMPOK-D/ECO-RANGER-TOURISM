<?php

namespace App\Http\Controllers;

use App\Services\PointCalculationService;
use App\Models\User;
use App\Models\Report;
use App\Models\Event;
use App\Models\ForumDiskusi;
use App\Models\SharedContent;
use Illuminate\Support\Facades\Auth;

class EcoRankingController extends Controller
{
    protected $pointService;

    public function __construct(PointCalculationService $pointService)
    {
        $this->pointService = $pointService;
    }

    public function index()
    {
        // Ambil semua user dengan poin dari service (SATU-SATUNYA sumber)
        $usersWithPoints = $this->pointService->getAllUsersWithPoints();
        
        // Top 3 untuk podium
        $topThree = $usersWithPoints->take(3);
        
        // Leaderboard (semua user)
        $leaderboard = $usersWithPoints;
        
        // Posisi user saat ini
        $currentUser = Auth::user();
        $currentUserRank = null;
        $currentUserData = null;
        
        if ($currentUser) {
            foreach ($usersWithPoints as $index => $userData) {
                if ($userData->id === $currentUser->id) {
                    $currentUserRank = $index + 1;
                    $currentUserData = $userData;
                    break;
                }
            }
        }
        
        // Data untuk user yang login (badges, dll)
        $badges = [];
        $currentUserPoints = 0;
        if ($currentUser) {
            $badges = $this->getUserBadges($currentUser);
            if ($currentUserData) {
                $currentUserPoints = $currentUserData->total_points;
            }
        }
        
        return view('eco-rankings', compact(
            'topThree', 
            'leaderboard', 
            'currentUserRank', 
            'currentUserPoints',
            'badges'
        ));
    }
    
    /**
     * API endpoint untuk ambil data leaderboard (jika perlu AJAX)
     */
    public function getLeaderboardData()
    {
        $usersWithPoints = $this->pointService->getAllUsersWithPoints();
        
        return response()->json([
            'success' => true,
            'data' => $usersWithPoints
        ]);
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
}
