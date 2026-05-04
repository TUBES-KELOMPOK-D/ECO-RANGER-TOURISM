@extends('layouts.app')

@section('title', 'Akademi - Eco Ranger Tourism')

@push('styles')
<style>
    .academy-card {
        transition: all 0.24s ease;
    }

    .academy-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
        transform: translateY(-2px);
    }

    .academy-card-completed {
        background: linear-gradient(180deg, #f0fdf4 0%, #ecfdf5 100%);
        border-color: #bbf7d0;
    }

    .academy-card-completed:hover {
        border-color: #86efac;
    }

    .academy-card-pending {
        background: #f8fafc;
    }
</style>
@endpush

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-8">
        @if(session('success') || session('error') || session('info'))
        <div class="space-y-3">
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
        <div class="rounded-3xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-black text-emerald-900">Masuk untuk lanjut belajar</h2>
                    <p class="mt-1 text-sm font-medium text-emerald-700">Guest tetap bisa melihat materi, tapi progres, kuis, dan poin baru aktif setelah login.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('login') }}" class="rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-black text-white transition hover:bg-emerald-700">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="rounded-2xl bg-white px-5 py-3 text-sm font-black text-emerald-700 ring-1 ring-emerald-200 transition hover:bg-emerald-100">
                        Register
                    </a>
                </div>
            </div>
        </div>
        @endguest

        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-soft">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900">Akademi</h1>
                    <p class="mt-2 text-sm text-slate-500">Jelajahi modul Green Academy dan bangun kebiasaan yang lebih ramah lingkungan.</p>
                </div>
            </div>

            <div class="mt-8">
                <div class="relative overflow-hidden rounded-3xl bg-slate-900 p-6 text-white shadow-xl md:p-8">
                    <div class="relative z-10">
                        <h3 class="text-lg font-black">Progress Belajar</h3>
                        <p class="mb-6 mt-1 text-xs font-medium text-slate-400">{{ auth()->check() ? 'Selesaikan modul untuk mendapatkan poin.' : 'Login atau register terlebih dahulu untuk menyimpan progres belajar.' }}</p>
                        <div class="mb-2 h-3 w-full overflow-hidden rounded-full bg-white/10 shadow-inner">
                            <div class="h-full rounded-full bg-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.5)] transition-all duration-1000" style="width: {{ $progressPercentage }}%"></div>
                        </div>
                        <div class="flex justify-between text-[10px] font-bold uppercase tracking-widest text-slate-400">
                            <span>{{ $progressPercentage }}% selesai</span>
                            <span class="text-white">{{ $completedModules }}/{{ $totalModules }} modul</span>
                        </div>
                    </div>
                    <div class="pointer-events-none absolute right-[-10px] top-1/2 -translate-y-1/2 rotate-12 text-white/5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[120px] w-[120px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-extrabold text-slate-900">Rekomendasi Destinasi Wisata</h2>
                        <p class="mt-2 text-sm text-slate-500">Destinasi pilihan berdasarkan skor wisata hijau tertinggi.</p>
                    </div>
                </div>

                <div class="mt-8 grid gap-5 md:grid-cols-3">
                    @forelse($recommendedDestinations->take(3) as $marker)
                    @php
                        $statusLabel = [
                            'green' => 'Sangat Terjaga',
                            'yellow' => 'Terjaga',
                            'red' => 'Perlu Perhatian',
                        ][$marker->status] ?? ($marker->category ?? 'Destinasi');

                        $statusClass = match($marker->status) {
                            'yellow' => 'bg-amber-50 text-amber-700 ring-amber-100',
                            'red' => 'bg-rose-50 text-rose-700 ring-rose-100',
                            default => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
                        };
                    @endphp
                    <a href="{{ route('markers.show', ['marker' => $marker->id, 'from' => 'academy']) }}" class="academy-card group overflow-hidden rounded-3xl border border-slate-200 bg-slate-50 shadow-sm">
                        <div class="h-36 overflow-hidden bg-emerald-50">
                            @if($marker->recommendation_image_url)
                            <img src="{{ $marker->recommendation_image_url }}" alt="{{ $marker->title ?? $marker->location_name ?? 'Destinasi wisata' }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            @else
                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-emerald-100 via-slate-50 to-amber-50 text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0Z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <div class="mb-4 flex items-center justify-between gap-3">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-wide ring-1 {{ $statusClass }}">
                                    {{ $marker->category ?? $statusLabel }}
                                </span>
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-3 py-1 text-xs font-black text-amber-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 0 0-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 0 0 .951-.69l1.07-3.292Z" />
                                    </svg>
                                    {{ $marker->eco_health_score !== null ? number_format($marker->eco_health_score, 1) : 'Baru' }}
                                </span>
                            </div>
                            <h3 class="line-clamp-2 text-lg font-extrabold text-slate-900">{{ $marker->title ?? 'Destinasi Wisata' }}</h3>
                            <p class="mt-1 text-sm font-semibold text-emerald-700">{{ $marker->location_name ?? 'Lokasi belum tersedia' }}</p>
                            <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($marker->description ?? 'Belum ada deskripsi untuk destinasi ini.', 110) }}</p>
                            <div class="mt-5 inline-flex items-center gap-2 text-sm font-black text-slate-900 group-hover:text-emerald-700">
                                Lihat Detail
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" />
                                </svg>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center text-sm text-slate-500 md:col-span-3">
                        Belum ada rekomendasi destinasi yang tersedia.
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-extrabold text-slate-900">Materi Tersedia</h2>
                        <p class="mt-2 text-sm text-slate-500">Pilih materi yang ingin dipelajari lebih dulu.</p>
                    </div>
                </div>
                <div class="mt-8 space-y-5">
                    @forelse($artikels as $artikel)
                    <a href="{{ route('academy.show', $artikel->id) }}" class="academy-card {{ $artikel->is_completed ? 'academy-card-completed' : 'academy-card-pending' }} group flex items-center gap-5 rounded-3xl border border-slate-200 p-6 shadow-sm">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl shadow-inner ring-1 {{ $artikel->is_completed ? 'bg-emerald-600 ring-emerald-600 text-white' : 'bg-white ring-slate-200 text-slate-400 group-hover:text-emerald-500' }}">
                            @if($artikel->is_completed)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0Z" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                            </svg>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="mb-2 text-xl font-semibold text-slate-900">{{ $artikel->title }}</h4>
                            <p class="text-sm text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($artikel->content), 120) }}</p>
                        </div>
                        <div class="shrink-0 text-right">
                            <div class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-4 py-2 text-xs font-semibold text-amber-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 0 0-1.176 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 0 0 .951-.69l1.07-3.292Z" />
                                </svg>
                                +{{ $artikel->points }}
                            </div>
                            @if($artikel->is_completed)
                            <div class="mt-3 text-xs font-semibold text-emerald-700">
                                Sudah selesai
                            </div>
                            @else
                            <div class="mt-3 text-xs font-semibold text-slate-500">
                                Belum selesai
                            </div>
                            @endif
                        </div>
                    </a>
                    @empty
                    <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center text-sm text-slate-500">
                        Materi Green Academy belum tersedia.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
