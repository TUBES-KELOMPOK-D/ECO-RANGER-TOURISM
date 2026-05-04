<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BadgeCheckerService
{
    /**
     * Check and award badges to the user based on their activities and points.
     *
     * @param User $user
     * @return array List of newly awarded badges
     */
    public function checkAndAwardBadges(User $user)
    {
        $newBadges = [];
        $allBadges = Badge::all();
        
        // Get user's current stats based on what columns the badges are targeting
        // Note: For complex counts, it's better to fetch from related tables or use stats columns if they exist.
        // Assuming user has relationships or we can count them:
        $stats = [
            'points' => $user->total_points ?? 0,
            'reports_count' => DB::table('eco_report_submissions')->where('user_id', $user->id)->count(),
            'events_count' => DB::table('participant_events')->where('user_id', $user->id)->count(),
            'verified_reports_count' => DB::table('eco_report_submissions')->where('user_id', $user->id)->whereIn('status', ['selesai', 'diverifikasi', 'diterima'])->count(),
            'academy_modules_count' => DB::table('user_progress')->where('user_id', $user->id)->where('completed', 1)->count(),
        ];

        // Ensure stats has default 0 for missing keys to avoid undefined array key errors
        $userBadges = $user->badges()->pluck('badges.id')->toArray();

        foreach ($allBadges as $badge) {
            // Skip if user already has this badge
            if (in_array($badge->id, $userBadges)) {
                continue;
            }

            $currentProgress = $stats[$badge->target_column] ?? 0;
            $achieved = false;

            if ($badge->target_condition === '>=') {
                $achieved = $currentProgress >= $badge->target;
            } elseif ($badge->target_condition === '=') {
                $achieved = $currentProgress == $badge->target;
            }

            if ($achieved) {
                // Award the badge
                $user->badges()->attach($badge->id, [
                    'progress_at_achievement' => $currentProgress,
                    'achieved_at' => now(),
                ]);
                
                // If there's points reward, award it via RankingService
                if ($badge->points_reward > 0) {
                    \App\Services\RankingService::addPoints(
                        $user, 
                        'badge_achievement', 
                        $badge->points_reward, 
                        'Lencana diraih: ' . $badge->name,
                        false // Set recursive to false to prevent infinite loops
                    );
                }

                $newBadges[] = $badge;
            }
        }

        return $newBadges;
    }

    /**
     * Get user's progress for a specific badge.
     *
     * @param User $user
     * @param Badge $badge
     * @return int
     */
    public function getUserProgress(User $user, Badge $badge)
    {
        // Use the same logic to count
        $stats = [
            'points' => $user->total_points ?? 0,
            'reports_count' => DB::table('eco_report_submissions')->where('user_id', $user->id)->count(),
            'events_count' => DB::table('participant_events')->where('user_id', $user->id)->count(),
            'verified_reports_count' => DB::table('eco_report_submissions')->where('user_id', $user->id)->whereIn('status', ['selesai', 'diverifikasi'])->count(),
            'academy_modules_count' => DB::table('user_progress')->where('user_id', $user->id)->where('completed', 1)->count(),
        ];

        return $stats[$badge->target_column] ?? 0;
    }

    /**
     * Get all badges along with the user's progress.
     *
     * @param User $user
     * @return \Illuminate\Support\Collection
     */
    public function getAllBadgesWithProgress(User $user)
    {
        $badges = Badge::all();
        $userBadgeIds = $user->badges()->pluck('badges.id')->toArray();
        $userBadgeData = $user->badges()->get()->keyBy('id');

        // Pre-calculate stats to avoid N queries
        $stats = [
            'points' => $user->total_points ?? 0,
            'reports_count' => DB::table('eco_report_submissions')->where('user_id', $user->id)->count(),
            'events_count' => DB::table('participant_events')->where('user_id', $user->id)->count(),
            'verified_reports_count' => DB::table('eco_report_submissions')->where('user_id', $user->id)->whereIn('status', ['selesai', 'diverifikasi', 'diterima'])->count(),
            'academy_modules_count' => DB::table('user_progress')->where('user_id', $user->id)->where('completed', 1)->count(),
        ];

        $badges->transform(function ($badge) use ($userBadgeIds, $userBadgeData, $stats) {
            $isAchieved = in_array($badge->id, $userBadgeIds);
            
            $currentProgress = $stats[$badge->target_column] ?? 0;
            
            // Cap progress at target if achieved, unless it's higher (but usually UI shows 100%)
            if ($isAchieved) {
                // If we want to show exact progress at achievement time
                $currentProgress = $userBadgeData[$badge->id]->pivot->progress_at_achievement ?? $currentProgress;
            }

            $badge->is_achieved = $isAchieved;
            $badge->achieved_at = $isAchieved ? $userBadgeData[$badge->id]->pivot->achieved_at : null;
            $badge->current_progress = $currentProgress;
            $badge->progress_percentage = min(100, ($badge->target > 0 ? ($currentProgress / $badge->target) * 100 : 0));
            
            return $badge;
        });

        return $badges;
    }
}
