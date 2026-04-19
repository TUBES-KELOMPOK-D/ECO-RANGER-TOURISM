@extends('layouts.app')

@section('title', $artikel->title . ' - Green Academy')

@push('styles')
<style>
    .academy-scrollbar {
        scrollbar-width: none;
    }
    .academy-scrollbar::-webkit-scrollbar {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-8">
        @if(session('success') || session('error') || session('info'))
            <div class="mb-6 space-y-3">
                @if(session('success'))
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('info'))
                    <div class="rounded-2xl border border-sky-200 bg-sky-50 px-5 py-4 text-sm font-semibold text-sky-800">
                        {{ session('info') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-800">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        @endif

        @guest
            <div class="rounded-3xl border border-amber-200 bg-amber-50 p-5 shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-black text-amber-900">Kuis butuh akun</h2>
                        <p class="mt-1 text-sm font-medium text-amber-700">Materi ini bisa dibaca tanpa login, tapi untuk mulai kuis evaluasi dan mendapatkan poin kamu perlu login atau register terlebih dulu.</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('login') }}" class="rounded-2xl bg-amber-500 px-5 py-3 text-sm font-black text-white transition hover:bg-amber-600">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="rounded-2xl bg-white px-5 py-3 text-sm font-black text-amber-700 ring-1 ring-amber-200 transition hover:bg-amber-100">
                            Register
                        </a>
                    </div>
                </div>
            </div>
        @endguest

        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-soft">
            <div class="border-b border-slate-200 pb-6">
                <div class="flex items-center gap-4">
                    <a href="{{ route('academy.index') }}" class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-slate-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900">{{ $artikel->title }}</h1>
                        <p class="mt-2 text-sm text-slate-500">Pelajari materi dengan saksama sebelum mengerjakan kuis evaluasi.</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 space-y-8">
                    @php
                        $sections = collect(preg_split("/\r\n\r\n|\n\n/", trim($artikel->content)))
                            ->filter()
                            ->map(function ($section) {
                                $lines = preg_split("/\r\n|\n|\r/", trim($section), 2);

                                return [
                                    'heading' => $lines[0] ?? '',
                                    'body' => $lines[1] ?? '',
                                ];
                            });

                        $introBody = $sections->first()['body'] ?? $artikel->content;
                    @endphp

                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                        <div class="mb-4 inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                            </svg>
                            Modul Pembelajaran
                        </div>
                        <p class="font-medium leading-relaxed text-slate-600">
                            {{ \Illuminate\Support\Str::limit(trim($introBody), 180) }}
                        </p>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-8 shadow-sm">
                        <div class="space-y-6">
                            @foreach($sections as $section)
                                <div class="space-y-3">
                                    <h4 class="text-lg font-black text-slate-800">{{ $section['heading'] }}</h4>
                                    <div class="whitespace-pre-line text-[15px] font-medium leading-8 text-slate-700">{{ $section['body'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <span class="rounded-full border border-slate-200 bg-slate-100 px-4 py-2 text-xs font-semibold text-slate-700">{{ $artikel->duration ?? 'Estimasi baca belum tersedia' }}</span>
                        <span class="rounded-full bg-amber-50 px-4 py-2 text-xs font-bold text-amber-600 shadow-sm">+{{ $artikel->points }} poin jika lulus</span>
                        @if($isCompleted)
                            <span class="rounded-full bg-emerald-50 px-4 py-2 text-xs font-bold text-emerald-700 shadow-sm">Skor terakhir: {{ $progress?->score ?? 0 }}</span>
                        @endif
                    </div>

                <div class="border-t border-slate-200 pt-6">
                    @auth
                    @if($isCompleted)
                        <a href="{{ route('academy.result', $artikel->id) }}" class="flex w-full items-center justify-center rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                            Lihat Hasil Kuis
                        </a>
                    @else
                        <a href="{{ route('academy.quiz', $artikel->id) }}" class="flex w-full items-center justify-center gap-2 rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.75v3" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 4.5h-7.5A.75.75 0 0 0 7.5 5.25v3.75a4.5 4.5 0 0 0 9 0V5.25a.75.75 0 0 0-.75-.75Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5H4.875A1.875 1.875 0 0 0 3 9.375c0 1.863 1.34 3.412 3.108 3.736" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5h1.125A1.875 1.875 0 0 1 21 9.375c0 1.863-1.34 3.412-3.108 3.736" />
                            </svg>
                            Mulai Kuis Evaluasi
                        </a>
                    @endif
                    @else
                        <div class="space-y-3">
                            <a href="{{ route('login') }}" class="flex w-full items-center justify-center rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                Login untuk Mulai Kuis
                            </a>
                            <a href="{{ route('register') }}" class="flex w-full items-center justify-center rounded-full border border-slate-200 bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                                Register
                            </a>
                        </div>
                    @endauth
                </div>
        </div>
    </div>
</div>
@endsection
