@if ($errors->any())
    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-800">
        <ul class="list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $formQuestions = old('questions', $questions);
@endphp

<div class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="mb-5 text-lg font-bold text-slate-800">Informasi Kuis</h2>
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Judul Kuis</label>
                <input type="text" name="title" value="{{ old('title', $kuis->title ?? '') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" required>
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Materi Terkait</label>
                <select name="artikel_id" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" required>
                    <option value="">Pilih materi</option>
                    @foreach($artikels as $artikel)
                        <option value="{{ $artikel->id }}" {{ (string) old('artikel_id', $kuis->artikel_id ?? '') === (string) $artikel->id ? 'selected' : '' }}>
                            {{ $artikel->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    @foreach($formQuestions as $index => $question)
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between gap-4">
                <h3 class="text-lg font-bold text-slate-800">Soal {{ $index + 1 }}</h3>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-500">4 opsi</span>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Pertanyaan</label>
                    <textarea name="questions[{{ $index }}][question]" rows="3" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" required>{{ $question['question'] ?? '' }}</textarea>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    @for($optionIndex = 0; $optionIndex < 4; $optionIndex++)
                        <div>
                            <label class="mb-1.5 block text-sm font-semibold text-slate-700">Opsi {{ $optionIndex + 1 }}</label>
                            <input type="text" name="questions[{{ $index }}][options][{{ $optionIndex }}]" value="{{ $question['options'][$optionIndex] ?? '' }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" required>
                        </div>
                    @endfor
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Jawaban Benar</label>
                    <select name="questions[{{ $index }}][answer_index]" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" required>
                        @for($optionIndex = 0; $optionIndex < 4; $optionIndex++)
                            <option value="{{ $optionIndex }}" {{ (string) ($question['answer_index'] ?? 0) === (string) $optionIndex ? 'selected' : '' }}>
                                Opsi {{ $optionIndex + 1 }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
    @endforeach

    <div class="flex flex-col gap-3 sm:flex-row">
        <button type="submit" class="flex-1 rounded-xl bg-emerald-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-100 transition hover:bg-emerald-700">
            Simpan Kuis
        </button>
        <a href="{{ route('admin.academy.quizzes.index') }}" class="rounded-xl border border-slate-200 px-6 py-3.5 text-center text-sm font-bold text-slate-600 transition hover:bg-slate-50">
            Batal
        </a>
    </div>
</div>
