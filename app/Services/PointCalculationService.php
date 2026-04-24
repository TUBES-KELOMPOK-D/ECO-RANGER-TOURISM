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
        $points = 0;

        // 1. Laporan Isu Lingkungan: +10 per laporan
        $points += count($user->reports) * 10;

        // 2. Ikut Aksi Komunitas: +50 per event
        $points += count($user->eventParticipations) * 50;

        // 3. Verifikasi Laporan: +5 per verifikasi
        // Catatan: Menggunakan 'status' = 'diverifikasi' karena tabel tidak memiliki 'verified_by'
        $points += $user->reports()->where('status', 'diverifikasi')->count() * 5;

        // 4. Diskusi Forum: +15 per post
        $points += count($user->forumPosts) * 15;

        // 5. Quiz/Green Academy: poin sesuai score (maks 20 per kuis)
        $quizResults = $user->academyProgress;
        foreach ($quizResults as $result) {
            $points += min($result->score ?? 0, 20); // maks 20 poin per kuis
        }

        // 6. Bagikan Konten: +20 per share
        $points += count($user->sharedContents) * 20;

        return $points;
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
        // Hanya ambil user dengan peran 'user'
        $users = User::where('role', 'user')->get();
        
        return $users->map(function ($user) {
            $points = $this->calculateTotalPoints($user);
            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->photo ?? null, // dari 'photo' di DB
                'total_points' => $points,
                'level' => $this->calculateLevel($points),
                'progress' => $this->calculateProgress($points)
            ];
        })->sortByDesc('total_points')->values();
    }
}
