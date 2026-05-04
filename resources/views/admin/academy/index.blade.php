@extends('layouts.app')

@section('title', 'Kelola Edukasi & Kuis')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Kelola Edukasi & Kuis</h1>
            <p class="mt-2 text-sm text-slate-500">Atur materi Green Academy, kuis evaluasi, dan pantau progres belajar user.</p>
        </div>
        <a href="{{ route('academy.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
            Lihat Academy User
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Materi</p>
            <p class="mt-3 text-3xl font-black text-slate-900">{{ $articleCount }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Kuis</p>
            <p class="mt-3 text-3xl font-black text-slate-900">{{ $quizCount }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400">User Berprogres</p>
            <p class="mt-3 text-3xl font-black text-slate-900">{{ $usersWithProgress }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Rata-rata Skor</p>
            <p class="mt-3 text-3xl font-black text-slate-900">{{ $averageScore !== null ? number_format($averageScore, 1) : '-' }}</p>
        </div>
    </div>

    <div class="mt-8 grid gap-5 md:grid-cols-3">
        <a href="{{ route('admin.academy.articles.index') }}" class="rounded-3xl border border-slate-200 bg-white p-7 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-lg">
            <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                </svg>
            </div>
            <h2 class="text-xl font-black text-slate-900">Kelola Materi</h2>
            <p class="mt-2 text-sm leading-6 text-slate-500">Tambah, edit, atau hapus materi edukasi yang tampil di Green Academy.</p>
        </a>

        <a href="{{ route('admin.academy.quizzes.index') }}" class="rounded-3xl border border-slate-200 bg-white p-7 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-lg">
            <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <h2 class="text-xl font-black text-slate-900">Kelola Kuis</h2>
            <p class="mt-2 text-sm leading-6 text-slate-500">Atur pertanyaan, opsi jawaban, dan jawaban benar untuk setiap materi.</p>
        </a>

        <a href="{{ route('admin.academy.progress') }}" class="rounded-3xl border border-slate-200 bg-white p-7 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-lg">
            <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-sky-50 text-sky-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 19v-6" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19v-9" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 19v-4" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 19V8" />
                </svg>
            </div>
            <h2 class="text-xl font-black text-slate-900">Monitoring Progress</h2>
            <p class="mt-2 text-sm leading-6 text-slate-500">{{ $completedProgressCount }} progress selesai tercatat dari aktivitas belajar user.</p>
        </a>
    </div>
</div>
@endsection
