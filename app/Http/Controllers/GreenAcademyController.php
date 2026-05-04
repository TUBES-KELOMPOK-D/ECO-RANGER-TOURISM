<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Artikel;
use App\Models\Marker;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Services\RankingService;

class GreenAcademyController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Artikel::query();

        if ($user) {
            $query->with([
                'progresses' => fn ($query) => $query->where('user_id', $user->id),
            ]);
        }

        $artikels = $query
            ->orderBy('created_at')
            ->orderBy('id')
            ->get()
            ->map(function (Artikel $artikel) {
                $progress = $artikel->relationLoaded('progresses')
                    ? $artikel->progresses->first()
                    : null;

                $artikel->is_completed = (bool) optional($progress)->completed;
                $artikel->user_score   = optional($progress)->score;

                return $artikel;
            });

        $totalModules      = $artikels->count();
        $completedModules  = $artikels->where('is_completed', true)->count();
        $progressPercentage = $totalModules > 0
            ? (int) round(($completedModules / $totalModules) * 100)
            : 0;

        $recommendedDestinations = Marker::query()
            ->whereNotNull('eco_health_score')
            ->orderByDesc('eco_health_score')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        if ($recommendedDestinations->count() < 3) {
            // Fallback saat belum ada cukup marker dengan skor aktif.
            $fallbackDestinations = Marker::query()
                ->whereNotIn('id', $recommendedDestinations->pluck('id'))
                ->latest()
                ->take(3 - $recommendedDestinations->count())
                ->get();

            $recommendedDestinations = $recommendedDestinations->concat($fallbackDestinations);
        }

        $recommendedDestinations = $recommendedDestinations
            ->take(3)
            ->values()
            ->each(function (Marker $marker) {
                $marker->recommendation_image_url = $marker->image_path && Storage::disk('public')->exists($marker->image_path)
                    ? asset('storage/' . $marker->image_path)
                    : null;
            });

        return view('green-academy.index', compact(
            'artikels',
            'totalModules',
            'completedModules',
            'progressPercentage',
            'recommendedDestinations'
        ));
    }

    public function show(int $id)
    {
        $artikel  = Artikel::with('quiz')->findOrFail($id);
        $progress = $this->userProgressForArticle($artikel->id);

        return view('green-academy.show', [
            'artikel'     => $artikel,
            'progress'    => $progress,
            'isCompleted' => (bool) optional($progress)->completed,
        ]);
    }

    /**
     * Halaman kuis – sekarang selalu bisa diakses (reattempt diizinkan).
     * Jika sudah pernah completed, tampilkan pesan kecil di view.
     */
    public function quiz(int $id)
    {
        $artikel  = Artikel::with('quiz')->findOrFail($id);
        $progress = $this->userProgressForArticle($artikel->id);

        if (!$artikel->quiz || empty($artikel->quiz->questions)) {
            return redirect()
                ->route('academy.show', $artikel->id)
                ->with('error', 'Kuis untuk materi ini belum tersedia.');
        }

        $questions    = $artikel->quiz->questions;
        $isReattempt  = (bool) optional($progress)->completed;

        return view('green-academy.quiz', compact('artikel', 'questions', 'isReattempt'));
    }

    /**
     * Submit kuis dengan logika poin sekali seumur hidup.
     *
     * - Poin diberikan hanya jika belum pernah completed AND skor >= 80%.
     * - Jika sudah completed sebelumnya: update score saja, completed tetap 1.
     * - Jika belum completed dan skor < 80%: completed = 0, tidak ada poin.
     */
    public function submitQuiz(Request $request, int $id)
    {
        $artikel = Artikel::with('quiz')->findOrFail($id);
        $user    = Auth::user();

        if (!$artikel->quiz || empty($artikel->quiz->questions)) {
            return redirect()
                ->route('academy.show', $artikel->id)
                ->with('error', 'Kuis untuk materi ini belum tersedia.');
        }

        $questions      = $artikel->quiz->questions;
        $totalQuestions = count($questions);
        $answers        = $request->input('answers', []);

        if (count($answers) !== $totalQuestions) {
            return back()
                ->withErrors(['answers' => 'Semua pertanyaan wajib dijawab sebelum mengirim kuis.'])
                ->withInput();
        }

        // Hitung skor
        $score = 0;
        foreach ($questions as $index => $question) {
            $selectedAnswer = $answers[$index] ?? null;
            $correctAnswer  = $question['answer'] ?? null;

            if ($selectedAnswer !== null && $selectedAnswer === $correctAnswer) {
                $score++;
            }
        }

        $minimumPassingScore = $this->minimumPassingScore($totalQuestions);

        // Cek apakah user sudah pernah completed sebelumnya
        $existingProgress  = $this->userProgressForArticle($artikel->id);
        $alreadyCompleted  = (bool) optional($existingProgress)->completed;
        $passedThisAttempt = $score >= $minimumPassingScore;

        // Tentukan logika poin dan completed
        $pointsAwarded = 0;
        $earnedPoints  = false;

        if ($alreadyCompleted) {
            // Sudah pernah lulus: update skor saja, tidak beri poin
            DB::transaction(function () use ($artikel, $user, $score) {
                UserProgress::updateOrCreate(
                    [
                        'user_id'    => $user->id,
                        'artikel_id' => $artikel->id,
                    ],
                    [
                        'completed' => true,   // tetap 1
                        'score'     => $score,
                    ]
                );
            });

            $earnedPoints = false;

        } elseif ($passedThisAttempt) {
            // Pertama kali lulus: beri poin
            $pointsAwarded = (int) $artikel->points;
            $earnedPoints  = true;

            DB::transaction(function () use ($artikel, $user, $score, $pointsAwarded) {
                UserProgress::updateOrCreate(
                    [
                        'user_id'    => $user->id,
                        'artikel_id' => $artikel->id,
                    ],
                    [
                        'completed' => true,
                        'score'     => $score,
                    ]
                );

                RankingService::addPoints($user, 'quiz', $pointsAwarded, 'Lulus kuis materi: ' . $artikel->title);

                Action::create([
                    'user_id'     => $user->id,
                    'action_name' => 'quiz_completed',
                    'points'      => $pointsAwarded,
                    'status'      => 'selesai',
                    'joined_date' => now()->toDateString(),
                ]);
            });

        } else {
            // Belum pernah lulus dan skor di bawah passing grade
            DB::transaction(function () use ($artikel, $user, $score) {
                UserProgress::updateOrCreate(
                    [
                        'user_id'    => $user->id,
                        'artikel_id' => $artikel->id,
                    ],
                    [
                        'completed' => false,
                        'score'     => $score,
                    ]
                );
            });

            $earnedPoints = false;
        }
        
        // Check and award badges
        $newBadges = app(\App\Services\BadgeCheckerService::class)->checkAndAwardBadges($user);
        
        $successMsg = 'Kuis berhasil dikirim.';
        if (!empty($newBadges)) {
            $badgeNames = collect($newBadges)->pluck('name')->implode(', ');
            $successMsg .= ' Selamat! Anda mendapatkan Badge Baru: ' . $badgeNames . ' 🏆';
        }

        return redirect()
            ->route('academy.result', $artikel->id)
            ->with([
                'success'        => $successMsg,
                'quiz_score'     => $score,
                'quiz_total'     => $totalQuestions,
                'quiz_passed'    => $passedThisAttempt,
                'earned_points'  => $earnedPoints,
                'points_awarded' => $pointsAwarded,
                'was_reattempt'  => $alreadyCompleted,
            ]);
    }

    /**
     * Halaman hasil kuis.
     * Mendukung data sesi dari submitQuiz maupun akses langsung dari show.
     */
    public function result(int $id)
    {
        $artikel  = Artikel::with('quiz')->findOrFail($id);
        $progress = $this->userProgressForArticle($artikel->id);

        $totalQuestions      = count($artikel->quiz?->questions ?? []);
        $minimumPassingScore = $this->minimumPassingScore($totalQuestions);

        // Ambil skor tertinggi yang pernah dicapai user untuk materi ini
        $highestScore = Auth::check()
            ? UserProgress::where('user_id', Auth::id())
                ->where('artikel_id', $artikel->id)
                ->max('score')
            : null;

        // Jika dari redirect submitQuiz, gunakan data sesi; jika akses langsung, hitung dari progress
        if (session()->has('quiz_score')) {
            $score         = (int) session('quiz_score');
            $passed        = (bool) session('quiz_passed');
            $earnedPoints  = (bool) session('earned_points');
            $pointsAwarded = (int) session('points_awarded');
            $wasReattempt  = (bool) session('was_reattempt');
        } else {
            // Akses langsung: hitung dari progress yang sudah tersimpan
            if (!$progress) {
                return redirect()
                    ->route('academy.show', $artikel->id)
                    ->with('error', 'Kuis untuk materi ini belum dikerjakan.');
            }

            $score         = (int) $progress->score;
            $passed        = $score >= $minimumPassingScore;
            $wasReattempt  = false;
            // Pada akses langsung tidak bisa tahu apakah sesi ini mendapat poin,
            // tampilkan saja info netral
            $earnedPoints  = false;
            $pointsAwarded = $passed ? (int) $artikel->points : 0;
        }

        return view('green-academy.result', compact(
            'artikel',
            'progress',
            'score',
            'totalQuestions',
            'pointsAwarded',
            'passed',
            'earnedPoints',
            'wasReattempt',
            'highestScore'
        ));
    }

    // ──────────────────────────────────────────────
    // Helper methods
    // ──────────────────────────────────────────────

    protected function userProgressForArticle(int $artikelId): ?UserProgress
    {
        if (!Auth::check()) {
            return null;
        }

        return UserProgress::where('user_id', Auth::id())
            ->where('artikel_id', $artikelId)
            ->latest('id')
            ->first();
    }

    protected function minimumPassingScore(int $totalQuestions): int
    {
        // Poin hanya diberikan jika semua jawaban benar (100%)
        return $totalQuestions;
    }
}
