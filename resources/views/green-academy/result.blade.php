@extends('layouts.app')

@section('title', 'Hasil Kuis - ' . $artikel->title)

@push('styles')
<style>
</style>
@endpush

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-soft">
        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-10 text-center">

            {{-- Icon utama --}}
            <div class="mx-auto mb-8 flex h-32 w-32 items-center justify-center rounded-3xl border-4 shadow-inner {{ $passed ? 'border-emerald-50 bg-emerald-100 text-emerald-600' : 'border-amber-50 bg-amber-100 text-amber-600' }}">
                @if($passed)
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.75v3" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 4.5h-7.5A.75.75 0 0 0 7.5 5.25v3.75a4.5 4.5 0 0 0 9 0V5.25a.75.75 0 0 0-.75-.75Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5H4.875A1.875 1.875 0 0 0 3 9.375c0 1.863 1.34 3.412 3.108 3.736" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5h1.125A1.875 1.875 0 0 1 21 9.375c0 1.863-1.34 3.412-3.108 3.736" />
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM12 16.5h.008v.008H12V16.5Z" />
                    </svg>
                @endif
            </div>

            {{-- Judul --}}
            <h2 class="mb-3 text-3xl font-black text-slate-800">
                @if($passed)
                    {{ $wasReattempt ? 'Latihan Selesai!' : 'Luar Biasa!' }}
                @else
                    Coba Lagi!
                @endif
            </h2>

            {{-- Skor sesi ini --}}
            <p class="mb-4 text-sm font-medium leading-relaxed text-slate-500">
                Kamu menjawab <strong class="text-slate-700">{{ $score }}</strong> dari <strong class="text-slate-700">{{ $totalQuestions }}</strong> pertanyaan dengan benar.
            </p>

            {{-- Info poin --}}
            @if($earnedPoints)
                <div class="mx-auto mb-6 inline-flex items-center gap-2 rounded-full bg-emerald-50 px-5 py-2.5 text-sm font-bold text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 0 0-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 0 0 .951-.69l1.07-3.292Z" />
                    </svg>
                    +{{ $pointsAwarded }} Poin Didapatkan!
                </div>
            @elseif($wasReattempt)
                <div class="mx-auto mb-6 inline-flex items-center gap-2 rounded-full bg-amber-50 px-5 py-2.5 text-sm font-semibold text-amber-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                    </svg>
                    Latihan ulang — poin sudah didapatkan sebelumnya
                </div>
            @endif

            {{-- Skor tertinggi (jika ada) --}}
            @if(!is_null($highestScore))
                <div class="mx-auto mb-8 flex items-center justify-center gap-6 rounded-2xl border border-slate-200 bg-white px-6 py-4">
                    <div class="text-center">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Skor Kali Ini</p>
                        <p class="mt-1 text-2xl font-black text-slate-800">{{ $score }}<span class="text-base font-semibold text-slate-400">/{{ $totalQuestions }}</span></p>
                    </div>
                    <div class="h-10 w-px bg-slate-200"></div>
                    <div class="text-center">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Skor Tertinggi</p>
                        <p class="mt-1 text-2xl font-black text-emerald-600">{{ $highestScore }}<span class="text-base font-semibold text-slate-400">/{{ $totalQuestions }}</span></p>
                    </div>
                </div>
            @else
                <div class="mb-8"></div>
            @endif

            {{-- Tombol aksi --}}
            <div class="mx-auto w-full max-w-xs space-y-3">
                <a href="{{ route('academy.index') }}" class="block w-full rounded-[24px] {{ $passed ? 'bg-emerald-600 text-white shadow-xl shadow-emerald-100 hover:bg-emerald-700' : 'bg-slate-200 text-slate-700 hover:bg-slate-300' }} px-10 py-4 font-bold transition">
                    {{ $passed ? 'Kembali ke Akademi' : 'Kembali' }}
                </a>
                <a href="{{ route('academy.show', $artikel->id) }}" class="block w-full rounded-[24px] bg-white px-10 py-4 font-bold text-slate-700 ring-1 ring-slate-200 transition hover:bg-slate-50">
                    Lihat Materi
                </a>
                {{-- Tombol latihan lagi --}}
                <a href="{{ route('academy.quiz', $artikel->id) }}" class="block w-full rounded-[24px] border border-dashed border-slate-300 bg-transparent px-10 py-4 font-semibold text-slate-500 transition hover:border-emerald-400 hover:text-emerald-600">
                    Latihan Lagi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
