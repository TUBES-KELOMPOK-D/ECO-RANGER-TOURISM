<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAchievement;
use App\Services\RankingService;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    /**
     * Display leaderboard
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $leaderboard = RankingService::getLeaderboard($page, 10);
        
        return view('rankings.index', [
            'leaderboard' => $leaderboard,
            'topRankers' => RankingService::getTopRankers(3),
            'pointRules' => RankingService::getPointRules(),
        ]);
    }

    /**
     * Display dashboard with rankings
     */
    public function dashboard(Request $request)
    {
        $topRankers = RankingService::getTopRankers(5);
        $pointRules = RankingService::getPointRules();
        
        // Get achievements as array
        $achievementsList = [];
        foreach (RankingService::ACHIEVEMENTS as $key => $achievement) {
            $achievementsList[] = $achievement;
        }
        
        return view('rankings.dashboard', [
            'topRankers' => $topRankers,
            'pointRules' => $pointRules,
            'achievements' => $achievementsList,
        ]);
    }

    /**
     * Display user's achievements
     */
    public function achievements(User $user)
    {
        $achievements = $user->achievements()
            ->get()
            ->map(function ($achievement) {
                return [
                    'name' => $achievement->achievement_name,
                    'description' => $achievement->description,
                    'icon' => $achievement->icon,
                    'target' => $achievement->target,
                    'current' => $achievement->current,
                    'progress' => $achievement->getProgressPercentageAttribute(),
                    'is_completed' => $achievement->is_completed,
                    'completed_at' => $achievement->completed_at,
                ];
            });

        return view('rankings.achievements', [
            'user' => $user,
            'achievements' => $achievements,
            'rank' => RankingService::getUserRank($user),
        ]);
    }

    /**
     * Get ranking data as JSON (for API or AJAX)
     */
    public function getLeaderboardJson(Request $request)
    {
        $page = $request->get('page', 1);
        $leaderboard = RankingService::getLeaderboard($page, 10);

        return response()->json($leaderboard);
    }

    /**
     * Get user achievements as JSON
     */
    public function getUserAchievementsJson(User $user)
    {
        $achievements = $user->achievements()
            ->get()
            ->map(function ($achievement) {
                return [
                    'name' => $achievement->achievement_name,
                    'description' => $achievement->description,
                    'icon' => $achievement->icon,
                    'target' => $achievement->target,
                    'current' => $achievement->current,
                    'progress' => $achievement->getProgressPercentageAttribute(),
                    'is_completed' => $achievement->is_completed,
                ];
            });

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'points' => $user->eco_points,
                'level' => $user->eco_level,
                'rank' => RankingService::getUserRank($user),
            ],
            'achievements' => $achievements,
        ]);
    }

    /**
     * Get point rules as JSON
     */
    public function getPointRulesJson()
    {
        return response()->json(RankingService::getPointRules());
    }
}
