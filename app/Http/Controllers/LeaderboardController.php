<?php

namespace App\Http\Controllers;

use App\Services\LeaderboardService;
use App\Models\Report;
use App\Models\Event;
use App\Models\ForumDiskusi;
use App\Models\SharedContent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    protected $leaderboardService;

    public function __construct(LeaderboardService $leaderboardService)
    {
        $this->leaderboardService = $leaderboardService;
    }

    /**
     * Tampilkan halaman leaderboard utama
     */
    public function index(Request $request)
    {
        // Ambil data leaderboard dengan pagination
        $perPage = $request->get('per_page', 10);
        
        // Cek apakah ada pencarian
        if ($request->has('search') && $request->search != '') {
            $leaderboard = $this->leaderboardService->searchLeaderboard($request->search, $perPage);
        } else {
            $leaderboard = $this->leaderboardService->getLeaderboard($perPage);
        }
        
        // Top 3 untuk podium
        $topThree = $this->leaderboardService->getTopThree();
        
        // Posisi user saat ini
        $currentUser = Auth::user();
        $userPosition = null;
        $userPoints = 0;
        
        if ($currentUser) {
            $position = $this->leaderboardService->getUserPosition($currentUser);
            if ($position) {
                $userPosition = $position->rank;
                $userPoints = $position->total_points;
            }
        }
        
        $badges = collect();
        if ($currentUser) {
            $badgeService = app(\App\Services\BadgeCheckerService::class);
            // Ensure badges are up to date
            $badgeService->checkAndAwardBadges($currentUser);
            $badges = $badgeService->getAllBadgesWithProgress($currentUser)->take(5);
        }
        
        // Mengambil data aturan poin dari RankingService dan database (jika ada)
        $pointRules = \App\Services\RankingService::getPointRules();
        
        // Mengambil voucher secara dinamis dari database untuk Top 3
        $vouchers = \App\Models\Voucher::orderBy('id', 'asc')->take(3)->get();
        
        $rewards = [];
        if ($vouchers->count() >= 3) {
            $rewards = [
                1 => ['title' => 'Juara 1', 'reward' => $vouchers[0]->name],
                2 => ['title' => 'Juara 2', 'reward' => $vouchers[1]->name],
                3 => ['title' => 'Juara 3', 'reward' => $vouchers[2]->name],
            ];
        } else {
            // Fallback default jika tabel belum diisi penuh
            $rewards = [
                1 => ['title' => 'Juara 1', 'reward' => 'Voucher Wisata Rp 500.000'],
                2 => ['title' => 'Juara 2', 'reward' => 'Voucher Wisata Rp 250.000'],
                3 => ['title' => 'Juara 3', 'reward' => 'Voucher Wisata Rp 100.000'],
            ];
        }
        
        $rankingTips = \App\Models\RankingTip::all();

        return view('leaderboard.index', compact(
            'leaderboard',
            'topThree',
            'userPosition',
            'userPoints',
            'pointRules',
            'rewards',
            'badges',
            'rankingTips',
            'perPage'
        ));
    }
    
    // Metode getUserBadges dihapus karena sudah memakai BadgeCheckerService langsung
    
    /**
     * API endpoint untuk mengambil data leaderboard (AJAX)
     */
    public function apiLeaderboard(Request $request)
    {
        $perPage = $request->get('per_page', 25);
        
        if ($request->has('search')) {
            $leaderboard = $this->leaderboardService->searchLeaderboard($request->search, $perPage);
        } else {
            $leaderboard = $this->leaderboardService->getLeaderboard($perPage);
        }
        
        return response()->json([
            'success' => true,
            'data' => $leaderboard->items(),
            'pagination' => [
                'current_page' => $leaderboard->currentPage(),
                'last_page' => $leaderboard->lastPage(),
                'per_page' => $leaderboard->perPage(),
                'total' => $leaderboard->total(),
            ],
            'top_three' => $this->leaderboardService->getTopThree(),
        ]);
    }

    // ========================================================
    // ADMIN: MANAJEMEN SISTEM POIN & REWARD (PBI-26)
    // ========================================================

    public function managePointRules()
    {
        $rules = \App\Models\PointRule::all();
        return view('leaderboard.admin_point_rules', compact('rules'));
    }

    public function storePointRule(Request $request)
    {
        $validated = $request->validate([
            'action_name' => 'required|string|max:255',
            'points_reward' => 'required|integer|min:0',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $validated['action_key'] = \Illuminate\Support\Str::slug($request->action_name, '_');

        \App\Models\PointRule::create($validated);
        return redirect()->route('leaderboard.admin.point_rules')->with('success', 'Aturan Poin berhasil ditambahkan.');
    }

    public function updatePointRule(Request $request, \App\Models\PointRule $rule)
    {
        $validated = $request->validate([
            'action_name' => 'required|string|max:255',
            'points_reward' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $rule->update($validated);
        return redirect()->route('leaderboard.admin.point_rules')->with('success', 'Aturan Poin berhasil diperbarui.');
    }

    public function destroyPointRule(\App\Models\PointRule $rule)
    {
        $rule->delete();
        return redirect()->route('leaderboard.admin.point_rules')->with('success', 'Aturan Poin berhasil dihapus.');
    }

    public function manageBadges()
    {
        $badges = \App\Models\Badge::all();
        return view('leaderboard.admin_badges', compact('badges'));
    }

    public function storeBadge(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:badges|max:255',
            'icon' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'target' => 'required|integer|min:1',
            'target_column' => 'required|string|max:255',
            'target_condition' => 'required|string|max:255',
            'points_reward' => 'required|integer|min:0',
            'level' => 'required|integer|min:1',
        ]);

        \App\Models\Badge::create($validated);
        return redirect()->route('leaderboard.admin.badges')->with('success', 'Lencana berhasil ditambahkan.');
    }

    public function updateBadge(Request $request, \App\Models\Badge $badge)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'target' => 'required|integer|min:1',
            'points_reward' => 'required|integer|min:0',
            'level' => 'required|integer|min:1',
        ]);

        $badge->update($validated);
        return redirect()->route('leaderboard.admin.badges')->with('success', 'Lencana berhasil diperbarui.');
    }

    public function destroyBadge(\App\Models\Badge $badge)
    {
        $badge->delete();
        return redirect()->route('leaderboard.admin.badges')->with('success', 'Lencana berhasil dihapus.');
    }

    // ========================================================
    // ADMIN: MANAJEMEN VOUCHER & HADIAH
    // ========================================================

    public function manageVouchers()
    {
        $vouchers = \App\Models\Voucher::all();
        return view('leaderboard.admin_vouchers', compact('vouchers'));
    }

    public function storeVoucher(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'poin_required' => 'required|integer|min:0',
        ]);

        $validated['kategori'] = 'lainnya';

        \App\Models\Voucher::create($validated);
        return redirect()->route('leaderboard.admin.vouchers')->with('success', 'Voucher berhasil ditambahkan.');
    }

    public function updateVoucher(Request $request, \App\Models\Voucher $voucher)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'poin_required' => 'required|integer|min:0',
        ]);

        $voucher->update($validated);
        return redirect()->route('leaderboard.admin.vouchers')->with('success', 'Voucher berhasil diperbarui.');
    }

    public function destroyVoucher(\App\Models\Voucher $voucher)
    {
        if ($voucher->image_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($voucher->image_path);
        }
        $voucher->delete();
        return redirect()->route('leaderboard.admin.vouchers')->with('success', 'Voucher berhasil dihapus.');
    }

    // ========================================================
    // ADMIN: MANAJEMEN TIPS NAIK PERINGKAT
    // ========================================================

    public function manageTips()
    {
        $tips = \App\Models\RankingTip::all();
        return view('leaderboard.admin_tips', compact('tips'));
    }

    public function storeTip(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:50',
        ]);

        \App\Models\RankingTip::create($validated);
        return redirect()->route('leaderboard.admin.tips')->with('success', 'Tips berhasil ditambahkan.');
    }

    public function updateTip(Request $request, \App\Models\RankingTip $tip)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:50',
        ]);

        $tip->update($validated);
        return redirect()->route('leaderboard.admin.tips')->with('success', 'Tips berhasil diperbarui.');
    }

    public function destroyTip(\App\Models\RankingTip $tip)
    {
        $tip->delete();
        return redirect()->route('leaderboard.admin.tips')->with('success', 'Tips berhasil dihapus.');
    }

    public function resetLeaderboard()
    {
        // Reset points for all users
        $users = \App\Models\User::where('role', 'user')->get();
        foreach ($users as $user) {
            $user->eco_points = 0;
            $user->save();
        }
        return redirect()->route('leaderboard')->with('success', 'Leaderboard berhasil di-reset untuk musim baru.');
    }

    public function adjustPoints(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|integer',
            'description' => 'required|string|max:255',
        ]);

        $user = \App\Models\User::findOrFail($validated['user_id']);
        
        \App\Models\PointLedger::create([
            'user_id' => $user->id,
            'points' => $validated['points'],
            'type' => $validated['points'] >= 0 ? 'earning' : 'redemption',
            'description' => 'Admin adjustment: ' . $validated['description'],
        ]);

        $user->addEcoPoints($validated['points']);

        return redirect()->route('leaderboard')->with('success', 'Poin pengguna berhasil disesuaikan.');
    }
}
