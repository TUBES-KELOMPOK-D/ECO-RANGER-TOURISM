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
            ->paginate($perPage);
            
        $leaderboardData = [];
        $offset = ($users->currentPage() - 1) * $perPage;
        
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
                'rank' => $offset + $index + 1
            ];
        }

        return new LengthAwarePaginator(
            $leaderboardData,
            $users->total(),
            $perPage,
            $users->currentPage(),
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    /**
     * Ambil Top 3 user untuk podium
     */
    public function getTopThree(): array
    {
        $topUsers = User::where(function($query) {
                $query->where('role', 'user')->orWhereNull('role');
            })
            ->where('eco_points', '>', 0)
            ->orderBy('eco_points', 'desc')
            ->take(3)
            ->get();
            
        $topThree = [];
        foreach ($topUsers as $index => $user) {
            $points = (int) $user->eco_points;
            $topThree[] = (object) [
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
        if ($user->eco_points <= 0) return null;

        $higherScoreCount = User::where(function($query) {
                $query->where('role', 'user')->orWhereNull('role');
            })
            ->where('eco_points', '>', $user->eco_points)
            ->count();
            
        return $higherScoreCount + 1;
    }

    /**
     * Dapatkan posisi user beserta poinnya
     */
    public function getUserPosition(User $user): ?object
    {
        if ($user->eco_points <= 0) return null;

        $rank = $this->getUserRank($user);
        $points = (int) $user->eco_points;
        
        return (object) [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->photo ?? null, 
            'total_points' => $points,
            'level' => $user->eco_level ?? $this->getLevel($points),
            'role' => $user->role ?? 'user',
            'rank' => $rank
        ];
    }

    /**
     * Search user di leaderboard
     */
    public function searchLeaderboard(string $keyword, int $perPage = 10): LengthAwarePaginator
    {
        $users = User::where(function($query) {
                $query->where('role', 'user')->orWhereNull('role');
            })
            ->where('name', 'like', "%{$keyword}%")
            ->orderBy('eco_points', 'desc')
            ->paginate($perPage);
            
        $leaderboardData = [];
        
        foreach ($users as $user) {
            $points = (int) $user->eco_points;
            $leaderboardData[] = (object) [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->photo ?? null, 
                'total_points' => $points,
                'level' => $user->eco_level ?? $this->getLevel($points),
                'role' => $user->role ?? 'user',
                'rank' => $this->getUserRank($user) ?? '-'
            ];
        }

        return new LengthAwarePaginator(
            $leaderboardData,
            $users->total(),
            $perPage,
            $users->currentPage(),
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}
