<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\Kuis;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AcademyAdminController extends Controller
{
    public function index()
    {
        return view('admin.academy.index', [
            'articleCount' => Artikel::count(),
            'quizCount' => Kuis::count(),
            'usersWithProgress' => UserProgress::query()->distinct()->count('user_id'),
            'averageScore' => UserProgress::avg('score'),
            'completedProgressCount' => UserProgress::where('completed', true)->count(),
        ]);
    }

    public function articlesIndex()
    {
        $artikels = Artikel::with('quiz')
            ->withCount('progresses')
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('admin.academy.articles.index', compact('artikels'));
    }

    public function articlesCreate()
    {
        return view('admin.academy.articles.create');
    }

    public function articlesStore(Request $request)
    {
        Artikel::create($this->validateArticle($request));

        return redirect()
            ->route('admin.academy.articles.index')
            ->with('success', 'Materi edukasi berhasil ditambahkan.');
    }

    public function articlesEdit(Artikel $artikel)
    {
        return view('admin.academy.articles.edit', compact('artikel'));
    }

    public function articlesUpdate(Request $request, Artikel $artikel)
    {
        $artikel->update($this->validateArticle($request));

        return redirect()
            ->route('admin.academy.articles.index')
            ->with('success', 'Materi edukasi berhasil diperbarui.');
    }

    public function articlesDestroy(Artikel $artikel)
    {
        DB::transaction(function () use ($artikel) {
            $artikel->quiz()->delete();
            $artikel->progresses()->delete();
            $artikel->delete();
        });

        return redirect()
            ->route('admin.academy.articles.index')
            ->with('success', 'Materi edukasi berhasil dihapus.');
    }

    public function quizzesIndex()
    {
        $kuisList = Kuis::with('artikel')
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('admin.academy.quizzes.index', compact('kuisList'));
    }

    public function quizzesCreate()
    {
        return view('admin.academy.quizzes.create', [
            'artikels' => $this->articleOptions(),
            'questions' => $this->blankQuestions(),
        ]);
    }

    public function quizzesStore(Request $request)
    {
        Kuis::create($this->validateQuiz($request));

        return redirect()
            ->route('admin.academy.quizzes.index')
            ->with('success', 'Kuis berhasil ditambahkan.');
    }

    public function quizzesEdit(Kuis $kuis)
    {
        return view('admin.academy.quizzes.edit', [
            'kuis' => $kuis,
            'artikels' => $this->articleOptions($kuis),
            'questions' => $this->questionsForForm($kuis),
        ]);
    }

    public function quizzesUpdate(Request $request, Kuis $kuis)
    {
        $kuis->update($this->validateQuiz($request, $kuis));

        return redirect()
            ->route('admin.academy.quizzes.index')
            ->with('success', 'Kuis berhasil diperbarui.');
    }

    public function quizzesDestroy(Kuis $kuis)
    {
        $kuis->delete();

        return redirect()
            ->route('admin.academy.quizzes.index')
            ->with('success', 'Kuis berhasil dihapus.');
    }

    public function progress(Request $request)
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');
        $totalArticles = Artikel::count();

        $users = User::query()
            ->where('role', 'user')
            ->withCount([
                'academyProgress as total_progress_count',
                'academyProgress as completed_modules_count' => fn ($query) => $query->where('completed', true),
            ])
            ->withAvg('academyProgress as average_score', 'score')
            ->withMax('academyProgress as highest_score', 'score')
            ->withMax('academyProgress as latest_progress_at', 'updated_at')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($status === 'completed', fn ($query) => $query->whereHas('academyProgress', fn ($progress) => $progress->where('completed', true)))
            ->when($status === 'in_progress', fn ($query) => $query->whereHas('academyProgress')->whereDoesntHave('academyProgress', fn ($progress) => $progress->where('completed', true)))
            ->when($status === 'no_progress', fn ($query) => $query->doesntHave('academyProgress'))
            ->orderByDesc('latest_progress_at')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.academy.progress', compact('users', 'search', 'status', 'totalArticles'));
    }

    protected function validateArticle(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image_path' => ['nullable', 'string', 'max:255'],
            'points' => ['nullable', 'integer', 'min:0', 'max:100000'],
            'duration' => ['nullable', 'string', 'max:50'],
        ]);
    }

    protected function validateQuiz(Request $request, ?Kuis $kuis = null): array
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'artikel_id' => [
                'required',
                'integer',
                'exists:artikels,id',
                Rule::unique('kuis', 'artikel_id')->ignore($kuis?->id),
            ],
            'questions' => ['required', 'array', 'min:1', 'max:10'],
            'questions.*.question' => ['required', 'string', 'max:1000'],
            'questions.*.options' => ['required', 'array', 'size:4'],
            'questions.*.options.*' => ['required', 'string', 'max:255'],
            'questions.*.answer_index' => ['required', 'integer', 'between:0,3'],
        ]);

        $validator->after(function ($validator) use ($request) {
            foreach ($request->input('questions', []) as $index => $question) {
                if (trim((string) ($question['question'] ?? '')) === '') {
                    $validator->errors()->add("questions.{$index}.question", 'Pertanyaan wajib diisi.');
                }

                $options = array_map(fn ($option) => trim((string) $option), $question['options'] ?? []);
                $answerIndex = isset($question['answer_index']) ? (int) $question['answer_index'] : null;

                foreach ($options as $optionIndex => $option) {
                    if ($option === '') {
                        $validator->errors()->add("questions.{$index}.options.{$optionIndex}", 'Pilihan jawaban tidak boleh kosong.');
                    }
                }

                if ($answerIndex === null || !array_key_exists($answerIndex, $options) || $options[$answerIndex] === '') {
                    $validator->errors()->add("questions.{$index}.answer_index", 'Jawaban benar harus dipilih dari opsi yang tersedia.');
                }
            }
        });

        $data = $validator->validate();

        $questions = collect($data['questions'])
            ->map(function (array $question) {
                $options = collect($question['options'])
                    ->map(fn ($option) => trim((string) $option))
                    ->values()
                    ->all();

                return [
                    'question' => trim($question['question']),
                    'options' => $options,
                    'answer' => $options[(int) $question['answer_index']],
                ];
            })
            ->values()
            ->all();

        return [
            'title' => $data['title'],
            'artikel_id' => $data['artikel_id'],
            'questions' => $questions,
        ];
    }

    protected function articleOptions(?Kuis $kuis = null)
    {
        return Artikel::query()
            ->where(function ($query) use ($kuis) {
                $query->whereDoesntHave('quiz');

                if ($kuis) {
                    $query->orWhere('id', $kuis->artikel_id);
                }
            })
            ->orderBy('title')
            ->get(['id', 'title']);
    }

    protected function blankQuestions(): array
    {
        return array_fill(0, 5, [
            'question' => '',
            'options' => ['', '', '', ''],
            'answer_index' => 0,
        ]);
    }

    protected function questionsForForm(Kuis $kuis): array
    {
        $questions = collect($kuis->questions ?? [])
            ->map(function (array $question) {
                $options = array_values($question['options'] ?? []);

                while (count($options) < 4) {
                    $options[] = '';
                }

                $options = array_slice($options, 0, 4);
                $answerIndex = array_search($question['answer'] ?? '', $options, true);

                return [
                    'question' => $question['question'] ?? '',
                    'options' => $options,
                    'answer_index' => $answerIndex === false ? 0 : $answerIndex,
                ];
            })
            ->values()
            ->all();

        return count($questions) > 0 ? $questions : $this->blankQuestions();
    }
}
