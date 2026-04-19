@extends('layouts.app')

@section('title', 'Kuis: ' . $artikel->title)

@push('styles')
<style>
    .quiz-question[hidden] {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-soft">
        <div class="border-b border-slate-200 pb-6">
                <div class="flex items-center gap-4">
                    <a href="{{ route('academy.show', $artikel->id) }}" class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-slate-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </a>
                    <div>
                        <h3 class="font-black leading-tight text-slate-800">Kuis: {{ $artikel->title }}</h3>
                        <p id="quiz-progress-text" class="text-[10px] font-bold uppercase tracking-widest text-emerald-600">Pertanyaan 1 dari {{ count($questions) }}</p>
                    </div>
                </div>
        </div>

        <form id="academy-quiz-form" method="POST" action="{{ route('academy.submit', $artikel->id) }}">
            @csrf

            <div class="mt-8 space-y-8">
                @if($errors->has('answers'))
                    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-800">
                        {{ $errors->first('answers') }}
                    </div>
                @endif

                @foreach($questions as $index => $question)
                    <div class="quiz-question rounded-3xl border border-slate-200 bg-slate-50 p-8 shadow-sm" data-question-index="{{ $index }}" {{ $index > 0 ? 'hidden' : '' }}>
                        <div class="mb-2 text-[10px] font-bold uppercase tracking-widest text-emerald-600">Pertanyaan {{ $index + 1 }}</div>
                        <h4 class="mb-8 text-xl font-extrabold leading-relaxed text-slate-900">{{ $question['question'] }}</h4>
                        <div class="space-y-3">
                            @foreach($question['options'] as $option)
                                <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-5 font-semibold text-slate-700 transition-all hover:bg-slate-50">
                                    <input
                                        type="radio"
                                        name="answers[{{ $index }}]"
                                        value="{{ $option }}"
                                        class="quiz-answer mt-1 h-4 w-4 border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                        data-question-index="{{ $index }}"
                                        {{ old("answers.$index") === $option ? 'checked' : '' }}
                                    >
                                    <span class="text-base">{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 border-t border-slate-200 pt-6">
                <button id="quiz-next-button" type="button" class="w-full rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50 disabled:shadow-none">
                    Pertanyaan Selanjutnya
                </button>
            </div>
        </form>
    </div>
 </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('academy-quiz-form');
        const nextButton = document.getElementById('quiz-next-button');
        const progressText = document.getElementById('quiz-progress-text');
        const questions = Array.from(document.querySelectorAll('.quiz-question'));
        const answers = Array.from(document.querySelectorAll('.quiz-answer'));

        if (!form || !nextButton || !progressText || questions.length === 0) {
            return;
        }

        let currentIndex = 0;

        const hasAnswer = (index) => {
            return answers.some((input) => Number(input.dataset.questionIndex) === index && input.checked);
        };

        const updateView = () => {
            questions.forEach((question, index) => {
                question.hidden = index !== currentIndex;
            });

            progressText.textContent = `Pertanyaan ${currentIndex + 1} dari ${questions.length}`;
            nextButton.textContent = currentIndex === questions.length - 1 ? 'Selesai Kuis' : 'Pertanyaan Selanjutnya';
            nextButton.disabled = !hasAnswer(currentIndex);
        };

        answers.forEach((input) => {
            input.addEventListener('change', updateView);
        });

        nextButton.addEventListener('click', function () {
            if (!hasAnswer(currentIndex)) {
                return;
            }

            if (currentIndex === questions.length - 1) {
                form.submit();
                return;
            }

            currentIndex += 1;
            updateView();
        });

        updateView();
    });
</script>
@endpush
