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
        $nextLevel = [
            'label' => $pointService->getLevel($totalPoints + 1), // Ini hanya fallback sementara, logika next level ditangani LeaderboardService
            'current' => 0,
            'required' => 500,
            'remaining' => 500 - $totalPoints
        ];
        
        $progress = min(100, ($totalPoints / 500) * 100);

        $latestReport = $user->ecoReports()
            ->orderByDesc('report_date')
            ->orderByDesc('created_at')
            ->first();

        $reportCount = $user->ecoReports()->count();

        return view('Profile.index', compact('user', 'nextLevel', 'progress', 'latestReport', 'reportCount', 'totalPoints'));
    }

    public function settings()
    {
        return view('Profile.settings', ['user' => auth()->user()]);
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
