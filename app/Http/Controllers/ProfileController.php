<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $pointService = app(\App\Services\LeaderboardService::class);
        $totalPoints = $pointService->calculateUserPoints($user);
        
        $badgeService = app(\App\Services\BadgeCheckerService::class);
        // Ensure badges are up to date
        $badgeService->checkAndAwardBadges($user);
        $badges = $badgeService->getAllBadgesWithProgress($user)->take(5);

        $nextLevel = $this->nextLevelInfo($totalPoints);
        $progress = min(100, ($totalPoints / $nextLevel['required']) * 100);

        $latestReport = $user->ecoReports()
            ->orderByDesc('report_date')
            ->orderByDesc('created_at')
            ->first();

        $reportCount = $user->ecoReports()->count();

        // Ambil laporan Eco Reporter milik user
        $ecoReports = \App\Models\EcoReportSubmission::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        // Ambil event yang diikuti user (Aksi yang Diikuti)
        $participatedEvents = $user->participatedEvents()
            ->orderByDesc('event_date')
            ->get();

        // Ambil voucher milik user
        $userVouchers = $user->vouchers()
            ->get();

        return view('profile.index', compact('user', 'nextLevel', 'progress', 'ecoReports', 'latestReport', 'reportCount', 'totalPoints', 'participatedEvents', 'badges', 'userVouchers'));
    }

    public function settings()
    {
        $user = auth()->user();
        $pointService = app(\App\Services\LeaderboardService::class);
        $totalPoints = $pointService->calculateUserPoints($user);
        $participatedEventsCount = $user->participatedEvents()->count();
        return view('profile.settings', [
            'user' => $user,
            'totalPoints' => $totalPoints,
            'participatedEventsCount' => $participatedEventsCount,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $user = auth()->user();
        $user->name = $request->name;

        if ($request->hasFile('photo')) {
            $this->deleteOldPhoto($user);
            $path = $request->file('photo')->store('profiles', 'public');
            $user->photo = $path;
        }

        $user->save();

        return redirect()->route('profile.settings')->with('success', 'Profil berhasil disimpan.');
    }

    public function deletePhoto(Request $request)
    {
        $user = auth()->user();
        $this->deleteOldPhoto($user);
        $user->photo = null;
        $user->save();

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }

    private function deleteOldPhoto($user): void
    {
        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }
    }

    protected function nextLevelInfo(int $points): array
    {
        $levels = [
            ['name' => 'Eco-Newbie', 'min' => 0, 'max' => 499],
            ['name' => 'Eco-Ranger', 'min' => 500, 'max' => 1499],
            ['name' => 'Eco-Warrior', 'min' => 1500, 'max' => 2999],
            ['name' => 'Eco-Guardian', 'min' => 3000, 'max' => 999999],
        ];

        foreach ($levels as $level) {
            if ($points <= $level['max']) {
                return [
                    'label' => $level['name'],
                    'current' => $level['min'],
                    'required' => $level['max'] + 1,
                    'remaining' => max(0, ($level['max'] + 1) - $points),
                ];
            }
        }

        return ['label' => 'Eco-Guardian', 'current' => 3000, 'required' => $points, 'remaining' => 0];
    }
}
