<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Kuis – Akademi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen font-sans text-slate-900">

    <x-navbar />

    @php
        $isPassed = $correctCount === $totalQuestions && $totalQuestions > 0;
        $isPartial = $correctCount > 0 && !$isPassed;
    @endphp

    {{-- Result Card: center of the page --}}
    <main class="max-w-3xl mx-auto px-4 sm:px-6 py-12">
        <div class="h-full bg-slate-50 flex flex-col items-center justify-center p-8 text-center">

            {{-- Icon --}}
            <div class="w-32 h-32 rounded-[40px] flex items-center justify-center mb-8 border-4 shadow-inner
                {{ $isPassed ? 'bg-emerald-100 border-emerald-50 text-emerald-600' : ($isPartial ? 'bg-amber-100 border-amber-50 text-amber-600' : 'bg-rose-100 border-rose-50 text-rose-500') }}">
                @if($isPassed)
                    {{-- Trophy --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/>
                        <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/>
                        <path d="M4 22h16"/>
                        <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/>
                        <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/>
                        <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>
                    </svg>
                @elseif($isPartial)
                    {{-- Alert Triangle --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                        <path d="M12 9v4"/><path d="M12 17h.01"/>
                    </svg>
                @else
                    {{-- X --}}
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                @endif
            </div>

            {{-- Heading --}}
            <h2 class="text-3xl font-black text-slate-800 mb-3">
                @if($isPassed)
                    Luar Biasa!
                @elseif($isPartial)
                    Coba Lagi!
                @else
                    Jangan Menyerah!
                @endif
            </h2>

            {{-- Score --}}
            <p class="text-slate-500 text-sm mb-10 leading-relaxed font-medium">
                Kamu menjawab <strong class="text-slate-700">{{ $correctCount }}</strong>
                dari <strong class="text-slate-700">{{ $totalQuestions }}</strong> pertanyaan dengan benar.
                @if($earnedPoints > 0 && !$alreadyCompleted)
                    <br><strong class="text-emerald-600 mt-2 block">+{{ $earnedPoints }} Poin didapatkan!</strong>
                @endif
            </p>

            @if($alreadyCompleted)
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-8">
                    Kamu sudah pernah menyelesaikan materi ini.
                </p>
            @endif

            {{-- Buttons --}}
            <div class="space-y-3 w-full max-w-xs">
                @if($isPassed)
                    <a href="{{ url('/akademi') }}"
                       class="bg-emerald-600 text-white px-10 py-4 rounded-[24px] font-bold w-full shadow-xl shadow-emerald-100 active:scale-95 transition-all decoration-transparent block text-center">
                        Kembali ke Akademi
                    </a>
                @else
                    <a href="{{ url('/akademi/kuis/' . $material->id) }}"
                       class="bg-amber-500 text-white px-10 py-4 rounded-[24px] font-bold w-full shadow-xl shadow-amber-100 active:scale-95 transition-all decoration-transparent block text-center">
                        Ulangi Kuis
                    </a>
                    <a href="{{ url('/akademi') }}"
                       class="bg-slate-200 text-slate-700 px-10 py-4 rounded-[24px] font-bold w-full active:scale-95 transition-all decoration-transparent block text-center">
                        Kembali
                    </a>
                @endif
            </div>

        </div>
    </main>

</body>
</html>
