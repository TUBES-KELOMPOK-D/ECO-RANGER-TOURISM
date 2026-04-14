<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuis: {{ $material->title }} – Akademi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen font-sans text-slate-900 flex flex-col">

    <x-navbar />

    {{-- Header Bar --}}
    <div class="bg-white px-6 py-5 border-b border-slate-100 flex items-center gap-4 shadow-sm z-10">
        <a href="{{ url('/akademi/materi/' . $material->slug) }}"
           class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-200 transition-colors decoration-transparent flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.5"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6"/>
            </svg>
        </a>
        <div>
            <h3 class="font-black text-slate-800 leading-tight">Kuis: {{ $material->title }}</h3>
            <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest">
                Pertanyaan 1 dari {{ $quizzes->count() }}
            </p>
        </div>
    </div>

    {{-- Quiz Form --}}
    <main class="flex-1 overflow-y-auto">
        <form id="quiz-form"
              action="{{ url('/akademi/kuis/' . $material->id) }}"
              method="POST"
              class="max-w-3xl mx-auto px-4 sm:px-6 py-6 space-y-6 pb-32">
            @csrf

            @foreach($quizzes as $index => $quiz)
                <div class="bg-white p-6 md:p-8 rounded-[32px] shadow-sm border border-slate-100">

                    @if($quizzes->count() > 1)
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">
                            Pertanyaan {{ $index + 1 }} dari {{ $quizzes->count() }}
                        </p>
                    @endif

                    <h4 class="text-xl font-black text-slate-800 mb-8 leading-relaxed">
                        {{ $quiz->question }}
                    </h4>

                    <div class="space-y-3">
                        @foreach(['a' => $quiz->option_a, 'b' => $quiz->option_b, 'c' => $quiz->option_c, 'd' => $quiz->option_d] as $key => $label)
                            <label class="option-label flex items-center gap-4 w-full text-left p-5 rounded-2xl border-2 border-slate-100 bg-white font-bold cursor-pointer transition-all hover:border-slate-200 group">
                                <input type="radio"
                                       name="answer_{{ $quiz->id }}"
                                       value="{{ $key }}"
                                       class="hidden quiz-radio"
                                       data-quiz="{{ $quiz->id }}">
                                <span class="text-slate-600 group-hover:text-slate-800 text-sm leading-snug">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

        </form>
    </main>

    {{-- Fixed Footer Button --}}
    <div class="bg-white border-t border-slate-100 px-4 sm:px-6 py-4 fixed bottom-0 left-0 right-0 z-50">
        <div class="max-w-3xl mx-auto">
            <button type="submit"
                    form="quiz-form"
                    id="btn-selesai"
                    class="w-full bg-emerald-600 text-white py-4 rounded-[20px] font-black shadow-lg shadow-emerald-100
                           hover:bg-emerald-700 transition-all active:scale-95 text-base">
                Selesai Kuis
            </button>
        </div>
    </div>

    <script>
        document.querySelectorAll('.quiz-radio').forEach(function(radio) {
            radio.addEventListener('change', function() {
                const quizId = this.getAttribute('data-quiz');
                // Reset all labels in same group
                document.querySelectorAll('input[name="answer_' + quizId + '"]').forEach(function(r) {
                    r.closest('label').classList.remove('border-emerald-500', 'bg-emerald-50', 'text-emerald-700');
                    r.closest('label').classList.add('border-slate-100', 'bg-white');
                    r.closest('label').querySelector('span').classList.remove('text-emerald-700');
                    r.closest('label').querySelector('span').classList.add('text-slate-600');
                });
                // Highlight selected
                if (this.checked) {
                    this.closest('label').classList.remove('border-slate-100', 'bg-white');
                    this.closest('label').classList.add('border-emerald-500', 'bg-emerald-50');
                    this.closest('label').querySelector('span').classList.remove('text-slate-600');
                    this.closest('label').querySelector('span').classList.add('text-emerald-700');
                }
            });
        });
    </script>

</body>
</html>
