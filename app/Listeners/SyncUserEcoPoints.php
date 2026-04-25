<?php

namespace App\Listeners;

use App\Services\PointCalculationService;
use App\Models\User;

class SyncUserEcoPoints
{
    protected $pointService;

    public function __construct(PointCalculationService $pointService)
    {
        $this->pointService = $pointService;
    }

    /**
     * Sinkronkan kolom eco_points dengan perhitungan real
     * Panggil method ini setelah event create/update pada:
     * - Report
     * - EventParticipant
     * - ForumDiskusi
     * - UserProgress
     * - SharedContent
     */
    public function handle($event)
    {
        $user = $event->user ?? null;
        
        if ($user) {
            $totalPoints = $this->pointService->calculateTotalPoints($user);
            $user->eco_points = $totalPoints;
            $user->saveQuietly(); // save tanpa triggering event lagi
        }
    }
}
