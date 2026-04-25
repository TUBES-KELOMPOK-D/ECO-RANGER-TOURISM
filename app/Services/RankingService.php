<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Support\Carbon;

class RankingService
{
    /**
     * Point rules configuration
     */
    public const POINT_RULES = [
        'report_issue' => 10,           // Lapor isu lingkungan
        'community_action' => 50,       // Ikut aksi komunitas
        'verify_report' => 5,           // Verifikasi laporan
        'forum_discussion' => 15,       // Diskusi forum
        'share_content' => 20,          // Bagikan konten
    ];

    /**
     * Achievement definitions
     */
    public const ACHIEVEMENTS = [
        'plastic_hunter' => [
            'name' => 'Plastic Hunter',
            'key' => 'plastic_hunter',
            'description' => 'Lapor 10 tumpukan sampah plastik',
            'icon' => '💪',
            'target' => 10,
            'type' => 'reports',
        ],
        'tree_hugger' => [
            'name' => 'Tree Hugger',
            'key' => 'tree_hugger',
            'description' => 'Ikut 5 aksi tanam pohon',
            'icon' => '🌳',
            'target' => 5,
            'type' => 'tree_actions',
        ],
        'green_warrior' => [
            'name' => 'Green Warrior',
            'key' => 'green_warrior',
            'description' => 'Ikut 5 aksi komunitas lingkungan',
            'icon' => '🌱',
            'target' => 5,
            'type' => 'actions',
        ],
        'earth_guardian' => [
            'name' => 'Earth Guardian',
            'key' => 'earth_guardian',
            'description' => 'Verifikasi 20 laporan lingkungan',
            'icon' => '🌍',
            'target' => 20,
            'type' => 'verifications',
        ],
        'eco_hero' => [
            'name' => 'Eco-Hero',
            'key' => 'eco_hero',
            'description' => 'Raih 3000 poin total',
            'icon' => '⭐',
            'target' => 3000,
            'type' => 'points',
        ],
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
    public static function addPoints(User $user, $pointType, $amount = null)
    {
        $points = $amount ?? self::POINT_RULES[$pointType] ?? 0;
        
        if ($points > 0) {
            $user->addEcoPoints($points);
        }

        // Update achievements
        self::updateUserAchievements($user);

        return $user;
    }

    /**
     * Initialize user achievements
     */
    public static function initializeUserAchievements(User $user)
    {
        foreach (self::ACHIEVEMENTS as $key => $achievement) {
            UserAchievement::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'achievement_key' => $achievement['key'],
                ],
                [
                    'achievement_name' => $achievement['name'],
                    'description' => $achievement['description'],
                    'icon' => $achievement['icon'],
                    'target' => $achievement['target'],
                    'current' => 0,
                ]
            );
        }
    }

    /**
     * Update user achievements based on their activity
     */
    public static function updateUserAchievements(User $user)
    {
        $achievements = self::ACHIEVEMENTS;

        foreach ($achievements as $key => $achievement) {
            $userAchievement = UserAchievement::where('user_id', $user->id)
                ->where('achievement_key', $achievement['key'])
                ->first();

            if (!$userAchievement) {
                $userAchievement = UserAchievement::create([
                    'user_id' => $user->id,
                    'achievement_key' => $achievement['key'],
                    'achievement_name' => $achievement['name'],
                    'description' => $achievement['description'],
                    'icon' => $achievement['icon'],
                    'target' => $achievement['target'],
                    'current' => 0,
                ]);
            }

            // Calculate current progress based on type
            $current = self::calculateAchievementProgress($user, $achievement['type']);

            // Update progress
            $userAchievement->current = $current;
            
            // Check if completed
            if ($current >= $achievement['target'] && !$userAchievement->is_completed) {
                $userAchievement->is_completed = true;
                $userAchievement->completed_at = now();
            }

            $userAchievement->save();
        }
    }

    /**
     * Calculate achievement progress
     */
    public static function calculateAchievementProgress(User $user, $type)
    {
        switch ($type) {
            case 'reports':
                return $user->reports()->count();
            case 'actions':
                return $user->actions()->where('status', 'completed')->count();
            case 'tree_actions':
                return $user->actions()->where('action_name', 'like', '%tanam%')->where('status', 'completed')->count();
            case 'verifications':
                // Count verified reports by this user (assumes report verification exists)
                return 0; // Implement based on your verification system
            case 'points':
                return $user->eco_points ?? 0;
            default:
                return 0;
        }
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
        return [
            [
                'title' => 'Lapor Isu Lingkungan',
                'description' => 'Buat laporan tentang masalah lingkungan',
                'icon' => '📋',
                'points' => self::POINT_RULES['report_issue'],
                'key' => 'report_issue',
            ],
            [
                'title' => 'Ikut Aksi Komunitas',
                'description' => 'Berpartisipasi dalam kegiatan komunitas',
                'icon' => '👥',
                'points' => self::POINT_RULES['community_action'],
                'key' => 'community_action',
            ],
            [
                'title' => 'Verifikasi Laporan',
                'description' => 'Verifikasi laporan dari pengguna lain',
                'icon' => '✅',
                'points' => self::POINT_RULES['verify_report'],
                'key' => 'verify_report',
            ],
            [
                'title' => 'Diskusi Forum',
                'description' => 'Berbagi wawasan di forum komunitas',
                'icon' => '💬',
                'points' => self::POINT_RULES['forum_discussion'],
                'key' => 'forum_discussion',
            ],
            [
                'title' => 'Bagikan Konten',
                'description' => 'Bagikan foto/video mengenai inisiatif hijau',
                'icon' => '📸',
                'points' => self::POINT_RULES['share_content'],
                'key' => 'share_content',
            ],
        ];
    }
}
