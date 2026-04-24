<?php

namespace App\Services;

use App\Models\User;
use App\Models\Report;
use App\Models\ForumDiskusi;
use App\Models\UserProgress;
use App\Models\SharedContent;
use Illuminate\Pagination\LengthAwarePaginator;

class LeaderboardService
{
    /**
     * Hitung total poin user dari SEMUA aktivitas
     */
    public function calculateUserPoints(User $user): int
    {
        $points = 0;

        // Laporan: +10 per laporan
        $points += $user->reports()->count() * 10;

        // Event: +50 per partisipasi (melalui tabel pivot participant_events)
        $points += $user->eventParticipations()->count() * 50;

        // Verifikasi: +5 per verifikasi (karena 'verified_by' mungkin tidak ada, gunakan status = diverifikasi sebagai ganti sementara)
        $points += $user->reports()->where('status', 'diverifikasi')->count() * 5;

        // Forum: +15 per post
        $points += $user->forumPosts()->count() * 15;

        // Quiz: sesuai score (maks 20 per quiz)
        $quizResults = $user->academyProgress;
        foreach ($quizResults as $result) {
            $points += min($result->score ?? 0, 20);
        }

        // Share: +20 per konten
        $points += $user->sharedContents()->count() * 20;

        return $points;
    }

    /**
     * Tentukan level user berdasarkan poin
     */
    public function getLevel(int $points): string
    {
        if ($points <= 100) return 'Eco-Newbie';
        if ($points <= 500) return 'Eco-Warrior';
        return 'Eco-Ranger';
    }

    /**
     * Ambil data leaderboard lengkap dengan pagination
     */
    public function getLeaderboard(int $perPage = 10): LengthAwarePaginator
    {
        // Ambil semua user dengan role user
        $users = User::where('role', 'user')->orWhereNull('role')->get();
        if ($users->isEmpty()) {
            $users = User::all();
        }
        
        // Hitung poin untuk setiap user
        $leaderboardData = [];
        foreach ($users as $user) {
            $points = $this->calculateUserPoints($user);
            $leaderboardData[] = (object) [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->photo ?? null, // Menggunakan kolom photo karena avatar tidak ada
                'total_points' => $points,
                'level' => $this->getLevel($points),
                'role' => $user->role ?? 'user'
            ];
        }

        // Urutkan berdasarkan poin tertinggi
        usort($leaderboardData, function($a, $b) {
            return $b->total_points <=> $a->total_points;
        });

        // Tambahkan rank ke setiap user
        foreach ($leaderboardData as $index => $user) {
            $user->rank = $index + 1;
        }

        // Konversi ke pagination
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedData = array_slice($leaderboardData, $offset, $perPage);
        
        return new LengthAwarePaginator(
            $paginatedData,
            count($leaderboardData),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    /**
     * Ambil Top 3 user untuk podium
     */
    public function getTopThree(): array
    {
        $leaderboard = $this->getLeaderboard(100); // ambil banyak dulu
        $topThree = collect($leaderboard->items())->take(3)->values()->toArray();
        
        // Kosongkan jika kurang dari 3
        while (count($topThree) < 3) {
            $topThree[] = null;
        }
        
        return $topThree;
    }

    /**
     * Dapatkan rank user tertentu
     */
    public function getUserRank(User $user): ?int
    {
        $leaderboard = $this->getLeaderboard(1000);
        foreach ($leaderboard->items() as $item) {
            if ($item->id === $user->id) {
                return $item->rank;
            }
        }
        return null;
    }

    /**
     * Dapatkan posisi user beserta poinnya
     */
    public function getUserPosition(User $user): ?object
    {
        $leaderboard = $this->getLeaderboard(1000);
        foreach ($leaderboard->items() as $item) {
            if ($item->id === $user->id) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Search user di leaderboard
     */
    public function searchLeaderboard(string $keyword, int $perPage = 10): LengthAwarePaginator
    {
        $allData = $this->getLeaderboard(1000);
        
        // Filter berdasarkan keyword
        $filtered = array_filter($allData->items(), function($user) use ($keyword) {
            return stripos($user->name, $keyword) !== false ||
                   stripos($user->level, $keyword) !== false;
        });
        
        // Mengembalikan array index untuk pagination
        $filtered = array_values($filtered);
        
        // Konversi ke pagination
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedData = array_slice($filtered, $offset, $perPage);
        
        return new LengthAwarePaginator(
            $paginatedData,
            count($filtered),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}
