<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BadgeController extends Controller
{
    public function index(\App\Services\BadgeCheckerService $badgeService)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Ensure user's badges are up to date before displaying
        $badgeService->checkAndAwardBadges($user);

        $badges = $badgeService->getAllBadgesWithProgress($user);
        
        $totalBadges = $badges->count();
        $achievedCount = $badges->where('is_achieved', true)->count();
        $bronzeCount = $badges->where('is_achieved', true)->where('level', 'bronze')->count();
        $silverCount = $badges->where('is_achieved', true)->where('level', 'silver')->count();
        $goldCount = $badges->where('is_achieved', true)->where('level', 'gold')->count();
        
        $completionPercentage = $totalBadges > 0 ? round(($achievedCount / $totalBadges) * 100) : 0;

        return view('badges.index', compact(
            'badges', 
            'totalBadges', 
            'achievedCount', 
            'bronzeCount', 
            'silverCount', 
            'goldCount',
            'completionPercentage'
        ));
    }

    public function apiUserBadges()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        return response()->json([
            'badges' => $user->latest_badges
        ]);
    }
}
