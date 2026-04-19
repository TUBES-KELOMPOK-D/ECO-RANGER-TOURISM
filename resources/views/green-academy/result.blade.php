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

            <h2 class="mb-3 text-3xl font-black text-slate-800">{{ $passed ? 'Luar Biasa!' : 'Coba Lagi!' }}</h2>
            <p class="mb-10 text-sm font-medium leading-relaxed text-slate-500">
                Kamu menjawab {{ $score }} dari {{ $totalQuestions }} pertanyaan dengan benar.
                @if($passed)
                    <strong class="mt-2 block text-emerald-600">+{{ $pointsAwarded }} Poin didapatkan!</strong>
                @endif
            </p>

            <div class="mx-auto w-full max-w-xs space-y-3">
                <a href="{{ route('academy.index') }}" class="block w-full rounded-[24px] {{ $passed ? 'bg-emerald-600 text-white shadow-xl shadow-emerald-100 hover:bg-emerald-700' : 'bg-slate-200 text-slate-700 hover:bg-slate-300' }} px-10 py-4 font-bold transition">
                    {{ $passed ? 'Kembali ke Akademi' : 'Kembali' }}
                </a>
                <a href="{{ route('academy.show', $artikel->id) }}" class="block w-full rounded-[24px] bg-white px-10 py-4 font-bold text-slate-700 ring-1 ring-slate-200 transition hover:bg-slate-50">
                    Lihat Materi
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
