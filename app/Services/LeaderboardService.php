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
        return (int) $user->eco_points;
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
        $users = User::where(function($query) {
                $query->where('role', 'user')->orWhereNull('role');
            })
            ->orderBy('eco_points', 'desc')
            ->get();
            
        $leaderboardData = [];
        foreach ($users as $index => $user) {
            $points = (int) $user->eco_points;
            $leaderboardData[] = (object) [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->photo ?? null, 
                'total_points' => $points,
                'level' => $user->eco_level ?? $this->getLevel($points),
                'role' => $user->role ?? 'user',
                'rank' => $index + 1
            ];
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
