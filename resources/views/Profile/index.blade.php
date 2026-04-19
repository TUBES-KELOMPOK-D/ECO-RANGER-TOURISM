@extends('layouts.app')

@section('title', 'Profil Saya - GreenTour')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-8">
        <div class="rounded-3xl bg-white p-8 shadow-soft border border-slate-200">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900">Profil Saya</h1>
                    <p class="mt-2 text-sm text-slate-500">Ringkasan poin eco dan status aksi yang sedang diikuti.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('profile.settings') }}" class="rounded-full border border-emerald-100 bg-emerald-50 px-5 py-3 text-sm font-semibold text-emerald-700 hover:bg-emerald-100">Pengaturan Profil</a>
                    <a href="{{ route('reports.index') }}" class="rounded-full border border-slate-200 bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Lihat Laporan</a>
                </div>
            </div>

            <div class="mt-10 overflow-hidden rounded-[2rem] bg-gradient-to-r from-emerald-600 to-toscagreen p-8 text-white shadow-xl sm:p-10">
                <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-emerald-100/80">Total Poin Eco</p>
                        <p class="mt-3 text-4xl font-black">{{ number_format($user->eco_points) }}</p>
                    </div>
                    <div class="flex items-center justify-between gap-4 rounded-3xl bg-white/10 p-4 shadow-inner shadow-slate-950/10 md:w-[320px]">
                        <div>
                            <p class="text-sm uppercase tracking-[0.2em] text-emerald-100/90">Level Saat Ini</p>
                            <p class="mt-1 text-lg font-semibold">{{ $user->eco_level }}</p>
                        </div>
                        <div class="rounded-3xl bg-white/20 px-4 py-3 text-sm font-bold uppercase tracking-[0.2em] text-white">{{ $user->eco_level }}</div>
                    </div>
                </div>

                <div class="mt-8 rounded-[2rem] bg-white/10 p-6">
                    <div class="flex items-center justify-between gap-4 text-sm font-semibold text-white/90">
                        <span>Progress ke level berikutnya</span>
                        <span>{{ number_format($progress) }}%</span>
                    </div>
                    <div class="mt-4 h-3 overflow-hidden rounded-full bg-white/20">
                        <div class="h-full rounded-full bg-white shadow-lg shadow-white/20" style="width: {{ max(0, min(100, $progress)) }}%"></div>
                    </div>
                    <p class="mt-3 text-xs text-white/80">{{ $user->eco_points }} poin terkumpul. {{ $nextLevel['remaining'] }} poin lagi ke level berikutnya.</p>
                </div>
            </div>
        </div>

        <div class="rounded-3xl bg-white p-8 shadow-soft border border-slate-200">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900">Aksi yang Diikuti</h2>
                    <p class="mt-2 text-sm text-slate-500">Tidak ada aksi aktif yang diikuti saat ini.</p>
                </div>
                <a href="/aksi" class="rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700">Cari Aksi Sekarang</a>
            </div>

            <div class="mt-8 rounded-3xl border border-slate-200 bg-slate-50 p-8 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-100 text-2xl text-emerald-700">
                    🌿
                </div>
                <p class="mt-6 text-sm font-semibold text-slate-900">Belum ada aksi yang diikuti.</p>
                <p class="mt-2 text-sm text-slate-500">Jelajahi aksi lingkungan dan kumpulkan poin eco untuk naik level.</p>
            </div>
        </div>
    </div>
</div>
@endsection
