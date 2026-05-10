<?php

namespace App\Services;

use App\Models\User;
use App\Models\PointLedger;
use Illuminate\Support\Carbon;

class RankingService
{
    /**
     * Default point rules configuration (fallback if database is empty)
     */
    public const DEFAULT_POINT_RULES = [
        'report_issue' => 10,           // Lapor isu lingkungan
        'community_action' => 50,       // Ikut aksi komunitas
        'verify_report' => 5,           // Verifikasi laporan
        'forum_discussion' => 15,       // Diskusi forum
        'share_content' => 20,          // Bagikan konten
        'quiz' => 20,                   // Lulus kuis akademi
    ];

    /**
     * Get top ranking users
     */
    public static function getTopRankers($limit = 10)
    {
        $pointService = app(\App\Services\LeaderboardService::class);
        $usersWithPoints = $pointService->getLeaderboard($limit);

        return collect($usersWithPoints->items())->map(function ($userData, $index) {
            $user = User::find($userData->id);
            return [
                'rank' => $index + 1,
                'user' => $user,
                'name' => $userData->name,
                'email' => $userData->email,
                'initials' => $user ? $user->getInitialsAttribute() : '',
                'points' => $userData->total_points,
                'level' => $userData->level,
            ];
        });
    }

    /**
     * Get user ranking
     */
    public static function getUserRank(User $user)
    {
        return $user->rank;
    }

    /**
     * Add points to user
     */
    public static function addPoints(User $user, $pointType, $amount = null, $description = null, $recursive = true)
    {
        $points = $amount;
        
        if ($points === null) {
            $rule = \App\Models\PointRule::where('action_key', $pointType)->first();
            $points = $rule ? $rule->points_reward : (self::DEFAULT_POINT_RULES[$pointType] ?? 0);
        }
        
        if ($points > 0) {
            // Log to ledger
            PointLedger::create([
                'user_id' => $user->id,
                'points' => $points,
                'type' => 'earning',
                'description' => $description ?? 'Earning from ' . $pointType,
            ]);

            // Add points to user
            $user->addEcoPoints($points);
            
            // Sync badges if not in a recursive call (badge reward)
            if ($recursive) {
                app(\App\Services\BadgeCheckerService::class)->checkAndAwardBadges($user);
            }
        }

        return $user;
    }

    /**
     * Get leaderboard with rankings
     */
    public static function getLeaderboard($page = 1, $limit = 10)
    {
        $skip = ($page - 1) * $limit;
        
        $total = User::where('role', 'user')->count();
        $users = self::getTopRankers($total);

        return [
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'users' => $users->slice($skip, $limit)->values(),
        ];
    }

    /**
     * Get points rules
     */
    public static function getPointRules()
    {
        $dbRules = \App\Models\PointRule::all();
        
        if ($dbRules->count() > 0) {
            return $dbRules->map(function($rule) {
                return [
                    'activity' => $rule->action_name,
                    'points' => $rule->points_reward,
                    'icon' => $rule->icon,
                    'key' => $rule->action_key,
                    'description' => $rule->description
                ];
            })->toArray();
        }

        return [
            [
                'activity' => 'Lapor Isu Lingkungan',
                'description' => 'Buat laporan tentang masalah lingkungan',
                'icon' => '📋',
                'points' => self::DEFAULT_POINT_RULES['report_issue'],
                'key' => 'report_issue',
            ],
            [
                'activity' => 'Ikuti event Lingkungan',
                'description' => 'Berpartisipasi dalam kegiatan komunitas',
                'icon' => '👥',
                'points' => self::DEFAULT_POINT_RULES['community_action'],
                'key' => 'community_action',
            ],
            [
                'activity' => 'Laporan Diverifikasi',
                'description' => 'Laporan Anda telah divalidasi oleh tim',
                'icon' => '✅',
                'points' => self::DEFAULT_POINT_RULES['verify_report'],
                'key' => 'verify_report',
            ],
            [
                'activity' => 'Diskusi Forum',
                'description' => 'Berbagi wawasan di forum komunitas',
                'icon' => '💬',
                'points' => self::DEFAULT_POINT_RULES['forum_discussion'],
                'key' => 'forum_discussion',
            ],
            [
                'activity' => 'Bagikan Konten',
                'description' => 'Bagikan foto/video mengenai inisiatif hijau',
                'icon' => '📸',
                'points' => self::DEFAULT_POINT_RULES['share_content'],
                'key' => 'share_content',
            ],
            [
                'activity' => 'Menyelesaikan Modul Akademi',
                'description' => 'Menyelesaikan kuis di Green Academy',
                'icon' => '🎓',
                'points' => self::DEFAULT_POINT_RULES['quiz'] ?? 20,
                'key' => 'quiz',
            ],
        ];
    }
}
