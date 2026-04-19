<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Artikel;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                $artikel->user_score = optional($progress)->score;

                return $artikel;
            });

        $totalModules = $artikels->count();
        $completedModules = $artikels->where('is_completed', true)->count();
        $progressPercentage = $totalModules > 0
            ? (int) round(($completedModules / $totalModules) * 100)
            : 0;

        return view('green-academy.index', compact(
            'artikels',
            'totalModules',
            'completedModules',
            'progressPercentage'
        ));
    }

    public function show(int $id)
    {
        $artikel = Artikel::with('quiz')->findOrFail($id);
        $progress = $this->userProgressForArticle($artikel->id);

        return view('green-academy.show', [
            'artikel' => $artikel,
            'progress' => $progress,
            'isCompleted' => (bool) optional($progress)->completed,
        ]);
    }

    public function quiz(int $id)
    {
        $artikel = Artikel::with('quiz')->findOrFail($id);
        $progress = $this->userProgressForArticle($artikel->id);

        if ($progress?->completed) {
            return redirect()
                ->route('academy.result', $artikel->id)
                ->with('info', 'Kuis untuk materi ini sudah diselesaikan.');
        }

        if (!$artikel->quiz || empty($artikel->quiz->questions)) {
            return redirect()
                ->route('academy.show', $artikel->id)
                ->with('error', 'Kuis untuk materi ini belum tersedia.');
        }

        $questions = $artikel->quiz->questions;

        return view('green-academy.quiz', compact('artikel', 'questions'));
    }

    public function submitQuiz(Request $request, int $id)
    {
        $artikel = Artikel::with('quiz')->findOrFail($id);
        $user = Auth::user();

        if (!$artikel->quiz || empty($artikel->quiz->questions)) {
            return redirect()
                ->route('academy.show', $artikel->id)
                ->with('error', 'Kuis untuk materi ini belum tersedia.');
        }

        $existingProgress = $this->userProgressForArticle($artikel->id);
        if ($existingProgress?->completed) {
            return redirect()
                ->route('academy.result', $artikel->id)
                ->with('info', 'Kuis untuk materi ini sudah diselesaikan.');
        }

        $questions = $artikel->quiz->questions;
        $totalQuestions = count($questions);
        $answers = $request->input('answers', []);

        if (count($answers) !== $totalQuestions) {
            return back()
                ->withErrors(['answers' => 'Semua pertanyaan wajib dijawab sebelum mengirim kuis.'])
                ->withInput();
        }

        $score = 0;
        foreach ($questions as $index => $question) {
            $selectedAnswer = $answers[$index] ?? null;
            $correctAnswer = $question['answer'] ?? null;

            if ($selectedAnswer !== null && $selectedAnswer === $correctAnswer) {
                $score++;
            }
        }

        $minimumPassingScore = $this->minimumPassingScore($totalQuestions);
        $pointsAwarded = $score >= $minimumPassingScore ? (int) $artikel->points : 0;

        DB::transaction(function () use ($artikel, $user, $score, $pointsAwarded) {
            UserProgress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'artikel_id' => $artikel->id,
                ],
                [
                    'completed' => true,
                    'score' => $score,
                ]
            );

            if ($pointsAwarded > 0) {
                $this->updateEcoPoints($user->id, $pointsAwarded);

                DB::table('poin')->insert([
                    'user_id' => $user->id,
                    'points' => $pointsAwarded,
                    'source' => 'activity',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Action::create([
                'user_id' => $user->id,
                'action_name' => 'quiz_completed',
                'points' => $pointsAwarded,
                'status' => 'selesai',
                'joined_date' => now()->toDateString(),
            ]);
        });

        return redirect()
            ->route('academy.result', $artikel->id)
            ->with('success', 'Kuis berhasil dikirim.');
    }

    public function result(int $id)
    {
        $artikel = Artikel::with('quiz')->findOrFail($id);
        $progress = $this->userProgressForArticle($artikel->id);

        if (!$progress || !$progress->completed) {
            return redirect()
                ->route('academy.show', $artikel->id)
                ->with('error', 'Kuis untuk materi ini belum diselesaikan.');
        }

        $totalQuestions = count($artikel->quiz?->questions ?? []);
        $minimumPassingScore = $this->minimumPassingScore($totalQuestions);
        $score = (int) $progress->score;
        $pointsAwarded = $score >= $minimumPassingScore ? (int) $artikel->points : 0;
        $passed = $score >= $minimumPassingScore;

        return view('green-academy.result', compact(
            'artikel',
            'progress',
            'score',
            'totalQuestions',
            'pointsAwarded',
            'passed'
        ));
    }

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
        return (int) ceil($totalQuestions * 0.8);
    }

    protected function updateEcoPoints(int $userId, int $pointsToAdd): void
    {
        $user = Auth::user()?->id === $userId
            ? Auth::user()
            : \App\Models\User::findOrFail($userId);

        $user->eco_points = ((int) $user->eco_points) + $pointsToAdd;
        $user->eco_level = $this->resolveEcoLevel((int) $user->eco_points);
        $user->save();
    }

    protected function resolveEcoLevel(int $totalPoints): string
    {
        if ($totalPoints >= 1500) {
            return 'Eco-Hero';
        }

        if ($totalPoints >= 500) {
            return 'Eco-Ranger';
        }

        return 'Eco-Newbie';
    }
}
