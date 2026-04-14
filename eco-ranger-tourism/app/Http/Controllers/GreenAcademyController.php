<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Quiz;
use App\Models\UserMaterialProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GreenAcademyController extends Controller
{
    /**
     * Daftar materi Green Academy.
     */
    public function index()
    {
        $materials = Material::all();

        // Ambil progress user yang login (jika ada)
        $completedIds = [];
        if (Auth::check()) {
            $completedIds = UserMaterialProgress::where('user_id', Auth::id())
                ->where('is_completed', true)
                ->pluck('material_id')
                ->toArray();
        }

        return view('akademi.index', compact('materials', 'completedIds'));
    }

    /**
     * Detail materi berdasarkan slug.
     */
    public function showMaterial($slug)
    {
        $material = Material::where('slug', $slug)->firstOrFail();

        return view('akademi.show', compact('material'));
    }

    /**
     * Form kuis untuk materi tertentu.
     */
    public function showQuiz($material_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('info', 'Silakan login terlebih dahulu untuk mengikuti kuis.');
        }

        $material = Material::findOrFail($material_id);
        $quizzes  = Quiz::where('material_id', $material_id)->get();

        if ($quizzes->isEmpty()) {
            return back()->with('error', 'Kuis untuk materi ini belum tersedia.');
        }

        return view('akademi.quiz', compact('material', 'quizzes'));
    }

    /**
     * Proses jawaban kuis.
     */
    public function submitQuiz(Request $request, $material_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('info', 'Silakan login terlebih dahulu untuk mengikuti kuis.');
        }

        $material = Material::findOrFail($material_id);
        $quizzes  = Quiz::where('material_id', $material_id)->get();

        $totalQuestions = $quizzes->count();
        $correctCount   = 0;

        foreach ($quizzes as $quiz) {
            $userAnswer = $request->input('answer_' . $quiz->id);
            if ($userAnswer && strtolower($userAnswer) === strtolower($quiz->correct_answer)) {
                $correctCount++;
            }
        }

        // Hitung poin: proporsional berdasarkan jawaban benar
        $earnedPoints = 0;
        if ($totalQuestions > 0) {
            $earnedPoints = (int) round(($correctCount / $totalQuestions) * $material->points);
        }

        // Simpan atau update progress, hanya jika user belum pernah selesaikan
        $alreadyCompleted = UserMaterialProgress::where('user_id', Auth::id())
            ->where('material_id', $material_id)
            ->where('is_completed', true)
            ->exists();

        if (!$alreadyCompleted) {
            UserMaterialProgress::updateOrCreate(
                ['user_id' => Auth::id(), 'material_id' => $material_id],
                [
                    'is_completed'  => true,
                    'completed_at'  => now(),
                    'earned_points' => $earnedPoints,
                ]
            );
        }

        return view('akademi.result', compact(
            'material', 'correctCount', 'totalQuestions', 'earnedPoints', 'alreadyCompleted'
        ));
    }
}
