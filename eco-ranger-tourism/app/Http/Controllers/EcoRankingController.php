<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Report;
use App\Models\ForumDiskusi;
use App\Models\SharedContent;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EcoRankingController extends Controller
{
    public function index()
    {
        // Ambil hanya user biasa (bukan admin) dan hitung poin mereka
        $users = User::where('role', 'user')->get()->map(function ($user) {
            $totalPoints = $this->calculateUserPoints($user);
            $user->total_points = $totalPoints;
            $user->level = $this->calculateLevel($totalPoints);
            return $user;
        });

        // Urutkan berdasarkan poin tertinggi
        $sortedUsers = $users->sortByDesc('total_points')->values();

        // Top 3 untuk podium
        $topThree = $sortedUsers->take(3);

        // Leaderboard (semua user)
        $leaderboard = $sortedUsers;

        // Posisi user saat ini
        $currentUser = Auth::user();
        $currentUserRank = null;
        $currentUserPoints = 0;

        if ($currentUser) {
            $currentUserPoints = $this->calculateUserPoints($currentUser);
            
            foreach ($sortedUsers as $index => $user) {
                if ($user->id === $currentUser->id) {
                    $currentUserRank = $index + 1;
                    break;
                }
            }
        }

        // Badges hanya untuk user yang login
        $badges = $this->calculateBadges($currentUser);

        return view('eco-rankings', compact(
            'topThree', 
            'leaderboard', 
            'currentUserRank', 
            'currentUserPoints', 
            'badges'
        ));
    }

    /**
     * Calculate total points untuk user berdasarkan aktivitas
     */
    private function calculateUserPoints($user)
    {
        $points = 0;

        // Laporan: +10 per laporan (user_id match)
        $reports = Report::where('user_id', $user->id)->count();
        $points += $reports * 10;

        // Event Participants: +50 per event
        $eventParticipants = Event::whereHas('participants', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();
        $points += $eventParticipants * 50;

        // Verifikasi Laporan: +5 per laporan yang status='diverifikasi' (dibuat oleh user)
        $verifiedReports = Report::where('user_id', $user->id)
            ->where('status', 'diverifikasi')
            ->count();
        $points += $verifiedReports * 5;

        // Forum Posts: +15 per post
        $forumPosts = ForumDiskusi::where('user_id', $user->id)->count();
        $points += $forumPosts * 15;

        // Shared Contents: +20 per share
        $sharedContents = SharedContent::where('user_id', $user->id)->count();
        $points += $sharedContents * 20;

        return $points;
    }

    /**
     * Tentukan level berdasarkan total poin
     */
    private function calculateLevel($points)
    {
        if ($points <= 100) {
            return 'Eco-Newbie';
        } elseif ($points <= 500) {
            return 'Eco-Warrior';
        } else {
            return 'Eco-Ranger';
        }
    }

    /**
     * Calculate badges untuk user yang login
     */
    private function calculateBadges($user)
    {
        if (!$user) return [];

        $badges = [];

        // 1. Plastic Hunter: Lapor 10 sampah
        $plasticReports = Report::where('user_id', $user->id)->count();
        $badges[] = [
            'name' => 'Plastic Hunter',
            'icon' => '🪣',
            'target' => 'Lapor 10 tumpukan sampah',
            'current' => $plasticReports,
            'max' => 10,
            'progress' => min(100, ($plasticReports / 10) * 100)
        ];

        // 2. Tree Hugger: Ikut 5 event
        $treeEvents = Event::whereHas('participants', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();
        $badges[] = [
            'name' => 'Tree Hugger',
            'icon' => '🌳',
            'target' => 'Ikut 5 aksi komunitas',
            'current' => $treeEvents,
            'max' => 5,
            'progress' => min(100, ($treeEvents / 5) * 100)
        ];

        // 3. Turtle Saver: Verifikasi 3 laporan (laporan user dengan status diverifikasi)
        $verifiedReports = Report::where('user_id', $user->id)
            ->where('status', 'diverifikasi')
            ->count();
        $badges[] = [
            'name' => 'Turtle Saver',
            'icon' => '🐢',
            'target' => 'Verifikasi 3 laporan',
            'current' => $verifiedReports,
            'max' => 3,
            'progress' => min(100, ($verifiedReports / 3) * 100)
        ];

        // 4. Eco-Speaker: 20 postingan forum
        $forumPosts = ForumDiskusi::where('user_id', $user->id)->count();
        $badges[] = [
            'name' => 'Eco-Speaker',
            'icon' => '💬',
            'target' => '20 postingan forum',
            'current' => $forumPosts,
            'max' => 20,
            'progress' => min(100, ($forumPosts / 20) * 100)
        ];

        // 5. Green Influencer: 10 konten dibagikan
        $sharedContents = SharedContent::where('user_id', $user->id)->count();
        $badges[] = [
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
