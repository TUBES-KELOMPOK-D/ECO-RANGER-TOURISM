<?php

namespace App\Services;

use App\Models\User;
use App\Models\Report;
use App\Models\Event;
use App\Models\ForumDiskusi;
use App\Models\UserProgress;
use App\Models\SharedContent;

class PointCalculationService
{
    /**
     * Hitung total poin user dari SEMUA aktivitas
     * Method ini adalah SATU-SATUNYA sumber kebenaran untuk poin
     */
    public function calculateTotalPoints(User $user): int
    {
        return (int) $user->eco_points;
    }

    /**
     * Hitung level user berdasarkan total poin
     */
    public function calculateLevel(int $totalPoints): string
    {
        if ($totalPoints <= 100) {
            return 'Eco-Newbie';
        } elseif ($totalPoints <= 500) {
            return 'Eco-Warrior';
        } else {
            return 'Eco-Ranger';
        }
    }

    /**
     * Hitung progress ke level berikutnya (dalam persen)
     */
    public function calculateProgress(int $totalPoints): array
    {
        if ($totalPoints <= 100) {
            $current = $totalPoints;
            $target = 100;
            $nextLevel = 'Eco-Warrior';
        } elseif ($totalPoints <= 500) {
            $current = $totalPoints - 100;
            $target = 400; // dari 100 ke 500 = 400 poin
            $nextLevel = 'Eco-Ranger';
        } else {
            return [
                'percentage' => 100,
                'current' => $totalPoints,
                'target' => $totalPoints,
                'nextLevel' => 'Max Level',
                'pointsNeeded' => 0
            ];
        }

        $percentage = min(100, (int) round(($current / $target) * 100));
        $pointsNeeded = $target - $current;

        return [
            'percentage' => $percentage,
            'current' => $totalPoints,
            'target' => ($totalPoints <= 100 ? 100 : 500),
            'nextLevel' => $nextLevel,
            'pointsNeeded' => $pointsNeeded
        ];
    }

    /**
     * Hitung semua data user untuk leaderboard sekaligus (optimasi)
     */
    public function getAllUsersWithPoints(): \Illuminate\Support\Collection
    {
        // Hanya ambil user dengan peran 'user', urutkan berdasarkan eco_points
        $users = User::where('role', 'user')->orderBy('eco_points', 'desc')->get();
        
        return $users->map(function ($user) {
            $points = (int) $user->eco_points;
            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->photo ?? null, 
                'total_points' => $points,
                'level' => $user->eco_level ?? $this->calculateLevel($points),
                'progress' => $this->calculateProgress($points)
            ];
        });
    }
}
