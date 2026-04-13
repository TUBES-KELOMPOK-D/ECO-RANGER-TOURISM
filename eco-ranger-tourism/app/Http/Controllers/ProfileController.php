<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $nextLevel = $this->nextLevelInfo($user->eco_points);
        $progress = $nextLevel['required'] > $nextLevel['current']
            ? round((($user->eco_points - $nextLevel['current']) / ($nextLevel['required'] - $nextLevel['current'])) * 100)
            : 100;

        return view('Profile.index', compact('user', 'nextLevel', 'progress'));
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
            'mode_admin' => ['nullable', 'boolean'],
        ]);

        $user = auth()->user();
        $user->name = $request->name;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('profiles', 'public');
            $user->photo = $path;
        }

        $user->save();
        session(['admin_mode' => $request->boolean('mode_admin')]);

        return redirect()->route('profile.settings')->with('success', 'Profil berhasil disimpan.');
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate(['photo' => ['required', 'image', 'max:2048']]);

        $path = $request->file('photo')->store('profiles', 'public');
        $user = auth()->user();
        $user->photo = $path;
        $user->save();

        return back()->with('success', 'Foto profil berhasil diunggah.');
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
