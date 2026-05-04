@extends('layouts.app')

@section('title', 'Aksi Event - Eco Ranger Tourism')

@push('styles')
<style>
    /* ── Animations ── */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes shimmer {
        0%   { background-position: -200% center; }
        100% { background-position:  200% center; }
    }
    .animate-fadeinup { animation: fadeInUp 0.5s ease both; }
    .animate-delay-100 { animation-delay: 0.1s; }
    .animate-delay-200 { animation-delay: 0.2s; }
    .animate-delay-300 { animation-delay: 0.3s; }

    /* ── Card hover lift ── */
    .event-card {
        transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s ease;
    }
    .event-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 24px 60px rgba(15,23,42,0.13);
    }

    /* ── Hero gradient ── */
    .hero-gradient {
        background: linear-gradient(135deg, #064e3b 0%, #065f46 40%, #047857 70%, #10b981 100%);
    }

    /* ── Badge pill ── */
    .badge-month { background: rgba(255,255,255,0.18); backdrop-filter: blur(8px); }

    /* ── Overlay / Modal ── */
    .modal-overlay {
        display: none;
        position: fixed; inset: 0; z-index: 999;
        background: rgba(15,23,42,0.55);
        backdrop-filter: blur(4px);
        align-items: center; justify-content: center;
    }
    .modal-overlay.active { display: flex; }
    .modal-box {
        background: #fff;
        border-radius: 1.5rem;
        padding: 2rem;
        width: 100%;
        max-width: 540px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 32px 80px rgba(15,23,42,0.2);
        animation: fadeInUp 0.3s ease both;
    }

    /* ── Event image placeholder ── */
    .event-img {
        width: 100%; height: 180px;
        object-fit: cover; border-radius: 1rem;
        background: linear-gradient(135deg,#d1fae5,#a7f3d0);
    }
    .event-img-placeholder {
        width: 100%; height: 180px;
        border-radius: 1rem;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        font-size: 3rem;
    }

    /* ── Members list ── */
    .member-avatar {
        width: 2.25rem; height: 2.25rem;
        border-radius: 9999px;
        background: linear-gradient(135deg, #10b981, #065f46);
        color: #fff; font-weight: 700; font-size: 0.75rem;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    /* ── Input focus ring ── */
    input:focus, textarea:focus, select:focus {
        outline: none;
        ring: 2px;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16,185,129,0.15);
    }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════════════
     HERO SECTION
══════════════════════════════════════════════════════════════ --}}
<div class="hero-gradient text-white py-16 px-4 sm:px-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between animate-fadeinup">
            <div>
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest badge-month mb-4">
                    Halaman Aksi
                </span>
                <h1 class="text-4xl sm:text-5xl font-black tracking-tight leading-tight">
                    Event <span class="text-emerald-300">Lingkungan</span>
                </h1>
                <p class="mt-3 text-emerald-100 text-base max-w-lg">
                    Bergabunglah dalam kegiatan pelestarian alam bersama komunitas Eco Ranger Tourism.
                </p>
            </div>

            {{-- Stat badges --}}
            <div class="flex flex-wrap gap-3 animate-fadeinup animate-delay-100">
                <div class="flex items-center gap-2 bg-white/10 backdrop-blur rounded-2xl px-4 py-3">
                    <span class="text-2xl font-black text-white">{{ $events->count() }}</span>
                    <span class="text-xs text-emerald-200 font-semibold">Event<br>Tersedia</span>
                </div>
                @auth
                    @php $joinedCount = $events->where('is_joined', true)->count(); @endphp
                    @if(auth()->user()->role !== 'admin')
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur rounded-2xl px-4 py-3">
                        <span class="text-2xl font-black text-emerald-300">{{ $joinedCount }}</span>
                        <span class="text-xs text-emerald-200 font-semibold">Sudah<br>Diikuti</span>
                    </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     FLASH MESSAGES
══════════════════════════════════════════════════════════════ --}}
@if(session('success') || session('error') || $errors->any())
<div class="max-w-6xl mx-auto px-4 sm:px-6 mt-6">
    @if(session('success'))
    <div class="flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-200 px-5 py-4 text-emerald-800 text-sm font-semibold shadow-sm mb-4">
        <span class="text-lg">✅</span> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 rounded-2xl bg-rose-50 border border-rose-200 px-5 py-4 text-rose-800 text-sm font-semibold shadow-sm mb-4">
        <span class="text-lg">⚠️</span> {{ session('error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="flex flex-col gap-2 rounded-2xl bg-rose-50 border border-rose-200 px-5 py-4 text-rose-800 text-sm font-semibold shadow-sm mb-4">
        <div class="flex items-center gap-2">
            <span class="text-lg">⚠️</span> <span>Terdapat kesalahan input:</span>
        </div>
        <ul class="list-disc list-inside ml-7">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endif

{{-- ═══════════════════════════════════════════════════════════
     TOOLBAR: Filter (User) | Tambah Event (Admin)
══════════════════════════════════════════════════════════════ --}}
<div class="max-w-6xl mx-auto px-4 sm:px-6 mt-8">
    {{-- ── Fitur Pencarian ── --}}
    <form method="GET" action="{{ route('aksi.index') }}" class="mb-6 relative">
        @if(request('month'))
            <input type="hidden" name="month" value="{{ request('month') }}">
        @endif
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari event berdasarkan nama, lokasi, atau deskripsi..." class="w-full pl-12 pr-12 py-3.5 rounded-2xl border border-slate-200 bg-white shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-all text-sm font-semibold text-slate-700 outline-none">
        <svg class="absolute left-4 top-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        @if(request('search'))
            <a href="{{ route('aksi.index', ['month' => request('month')]) }}" class="absolute right-4 top-3.5 text-slate-400 hover:text-rose-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </a>
        @endif
        <button type="submit" class="hidden"></button>
    </form>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

        @auth

            {{-- ── ADMIN: Tombol Tambah Event ── --}}
            @if(auth()->user()->role === 'admin')
            <div>
                <button
                    onclick="openModal('modal-add-event')"
                    class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-lg hover:bg-emerald-700 hover:-translate-y-0.5 transition-all active:translate-y-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Event
                </button>
            </div>
            <div class="flex items-center gap-2 text-sm font-semibold text-slate-500">
                <span class="px-3 py-1.5 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">👑 Mode Admin</span>
                <span>{{ $events->count() }} Event Terdaftar</span>
            </div>

            {{-- ── REGULAR USER: Filter Bulan ── --}}
            @else
            <div class="flex items-center gap-3 flex-wrap">
                <span class="text-sm font-bold text-slate-700">Filter Bulan:</span>
                <button
                    onclick="toggleMonthFilter()"
                    id="btn-month-filter"
                    class="inline-flex items-center gap-2 border border-slate-200 bg-white px-4 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    {{ $month ? \Carbon\Carbon::create()->month((int)$month)->translatedFormat('F') : 'Semua Bulan' }}
                    <svg id="filter-caret" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </button>

                {{-- Month picker dropdown --}}
                <div id="month-dropdown" class="hidden absolute mt-2 z-50">
                    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-3 grid grid-cols-3 gap-2 w-64">
                        <a href="{{ route('aksi.index', ['search' => request('search')]) }}"
                           class="text-center px-2 py-2 rounded-xl text-xs font-semibold {{ !$month ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-50' }} transition">
                            Semua
                        </a>
                        @foreach(range(1,12) as $m)
                        <a href="{{ route('aksi.index', ['month' => $m, 'search' => request('search')]) }}"
                           class="text-center px-2 py-2 rounded-xl text-xs font-semibold {{ $month == $m ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-50' }} transition">
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('M') }}
                        </a>
                        @endforeach
                    </div>
                </div>

                @if($month)
                <a href="{{ route('aksi.index', ['search' => request('search')]) }}" class="text-xs text-slate-400 hover:text-slate-700 underline transition">Reset</a>
                @endif
            </div>
            <span class="text-sm text-slate-500 font-semibold">{{ $events->count() }} Event Ditemukan</span>
            @endif

        @else
            {{-- Guest: tampilkan saja hitungan --}}
            <p class="text-sm text-slate-600 font-semibold">{{ $events->count() }} Event tersedia. <a href="{{ route('login') }}" class="text-emerald-600 underline">Login</a> untuk bergabung.</p>
        @endauth

    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     EVENT CARDS GRID
══════════════════════════════════════════════════════════════ --}}
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
    @if($events->isEmpty())
        <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50 py-20 text-center">
            <div class="text-5xl mb-4"></div>
            <p class="text-lg font-bold text-slate-700">Belum ada event tersedia</p>
            <p class="mt-2 text-sm text-slate-400">
                @if($month)
                    Tidak ada event di bulan ini. <a href="{{ route('aksi.index') }}" class="text-emerald-600 underline">Lihat semua bulan</a>
                @else
                    @auth @if(auth()->user()->role === 'admin') Tambahkan event pertama menggunakan tombol di atas! @endif @endauth
                @endif
            </p>
        </div>
    @else
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($events as $index => $event)
        <div class="event-card rounded-3xl bg-white border border-slate-100 overflow-hidden shadow-sm animate-fadeinup"
             style="animation-delay: {{ $index * 0.07 }}s">

            {{-- Wrap content in a clickable area for detail view --}}
            <div class="cursor-pointer group relative" onclick="openDetailModal({{ $event->id }})">
                {{-- Event Image --}}
                <div class="relative overflow-hidden rounded-t-3xl">
                    @if($event->image_path)
                        <img src="{{ asset('storage/' . $event->image_path) }}"
                             alt="{{ $event->name }}" class="event-img group-hover:scale-105 transition-transform duration-500" />
                    @else
                        <div class="event-img-placeholder group-hover:scale-105 transition-transform duration-500">🌿</div>
                    @endif
                    
                    {{-- Gamification Badge: Points/Reward Indicator --}}
                    <div class="absolute top-3 right-3 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-full shadow-lg border border-amber-200 flex items-center gap-1.5 z-10 group-hover:-translate-y-0.5 transition-all">
                        <span class="text-amber-500 text-sm">🌟</span>
                        <span class="text-xs font-black text-amber-700 tracking-wide">+50 Poin</span>
                    </div>
                </div>

                <div class="p-5 pb-0">
                    {{-- Header --}}
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <h2 class="text-base font-extrabold text-slate-900 leading-snug truncate group-hover:text-emerald-600 transition-colors">
                                {{ $event->name }}
                            </h2>
                            <p class="text-xs text-emerald-700 font-semibold mt-0.5">
                                {{ $event->organizer ?? 'Eco Ranger Tourism' }}
                            </p>
                        </div>
                        {{-- Joined badge --}}
                        @auth
                            @if(auth()->user()->role !== 'admin' && $event->is_joined)
                            <span class="flex-shrink-0 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">✓ Joined</span>
                            @endif
                        @endauth
                    </div>

                    {{-- Meta Info --}}
                    <div class="mt-4 space-y-2">
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            <span>{{ $event->event_date ? $event->event_date->isoFormat('D MMMM YYYY') : '-' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span class="truncate">{{ $event->location ?? 'Lokasi belum ditentukan' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <span>{{ $event->participants_count }} Peserta</span>
                        </div>
                    </div>

                    {{-- Description --}}
                    @if($event->description)
                    <p class="mt-3 text-xs text-slate-500 line-clamp-2 leading-relaxed">
                        {{ $event->description }}
                    </p>
                    @endif
                </div>
            </div>

            <div class="p-5 pt-2">
                @auth
                    {{-- ═══ ADMIN CTA ═══ --}}
                    @if(auth()->user()->role === 'admin')
                    <div class="mt-5 flex flex-wrap gap-2">
                        {{-- Chat --}}
                        <a href="{{ route('aksi.chat', $event) }}"
                           class="flex-1 inline-flex justify-center items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            Chat
                        </a>
                        {{-- Edit --}}
                        <button
                            onclick="openEditModal({{ $event->id }}, '{{ addslashes($event->name) }}', '{{ addslashes($event->description) }}', '{{ $event->event_date?->format('Y-m-d') }}', '{{ addslashes($event->location) }}', '{{ addslashes($event->organizer) }}')"
                            class="flex-1 inline-flex justify-center items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-sky-50 text-sky-700 hover:bg-sky-100 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            Edit
                        </button>
                        {{-- Anggota --}}
                        <button
                            onclick="openMembersModal({{ $event->id }}, '{{ addslashes($event->name) }}')"
                            class="flex-1 inline-flex justify-center items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-violet-50 text-violet-700 hover:bg-violet-100 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            Anggota
                        </button>
                        {{-- Hapus --}}
                        <button
                            onclick="confirmDelete({{ $event->id }}, '{{ addslashes($event->name) }}')"
                            class="flex-none inline-flex justify-center items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 hover:bg-rose-100 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                            Hapus
                        </button>
                    </div>

                    {{-- ═══ USER CTA ═══ --}}
                    @else
                    <div class="mt-5 flex gap-2">
                        @if($event->is_joined)
                            {{-- Chat grup --}}
                            <a href="{{ route('aksi.chat', $event) }}"
                               class="flex-1 inline-flex justify-center items-center gap-2 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-700 transition-all shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                Chat Grup
                            </a>
                            {{-- Batal ikut --}}
                            <form method="POST" action="{{ route('aksi.leave', $event) }}">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Batal ikut event ini?')"
                                    class="flex-none inline-flex justify-center items-center gap-1.5 px-3 py-2.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-rose-50 hover:text-rose-700 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    Batal Ikut
                                </button>
                            </form>
                        @else
                            {{-- Join --}}
                            <form method="POST" action="{{ route('aksi.join', $event) }}" class="flex-1">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center gap-2 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-700 transition-all shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                                    Join Event
                                </button>
                            </form>
                        @endif
                    </div>
                    @endif

                @else
                    {{-- Guest: prompt login --}}
                    <a href="{{ route('login') }}"
                       class="mt-5 block text-center py-2.5 rounded-xl text-xs font-bold border border-emerald-200 text-emerald-700 hover:bg-emerald-50 transition-all">
                        Login untuk Bergabung
                    </a>
                @endauth

            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>


{{-- ═══════════════════════════════════════════════════════════
     MODAL: TAMBAH EVENT (Admin)
══════════════════════════════════════════════════════════════ --}}
@auth
@if(auth()->user()->role === 'admin')
<div id="modal-add-event" class="modal-overlay" onclick="closeOnBackdrop(event, 'modal-add-event')">
    <div class="modal-box">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-extrabold text-slate-900">Tambah Event Baru</h3>
                <p class="text-xs text-slate-400 mt-1">Isi detail event yang akan ditambahkan</p>
            </div>
            <button onclick="closeModal('modal-add-event')" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form method="POST" action="{{ route('aksi.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Event <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="contoh: Tanam Pohon di Pantai Kuta"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Event <span class="text-rose-500">*</span></label>
                    <input type="date" name="event_date" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Lokasi</label>
                    <input type="text" name="location" placeholder="contoh: Pantai Kuta, Bali"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Penyelenggara</label>
                <input type="text" name="organizer" placeholder="contoh: Tim Eco Ranger"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3" placeholder="Ceritakan detail event ini..."
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition resize-none"></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Foto Event</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-500 file:mr-3 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-add-event')"
                    class="flex-1 py-2.5 rounded-xl text-sm font-bold border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl text-sm font-bold bg-emerald-600 text-white hover:bg-emerald-700 transition shadow-md">
                    Simpan Event
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     MODAL: EDIT EVENT (Admin)
══════════════════════════════════════════════════════════════ --}}
<div id="modal-edit-event" class="modal-overlay" onclick="closeOnBackdrop(event, 'modal-edit-event')">
    <div class="modal-box">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-extrabold text-slate-900">Edit Event</h3>
                <p class="text-xs text-slate-400 mt-1">Perbarui informasi event</p>
            </div>
            <button onclick="closeModal('modal-edit-event')" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form id="form-edit-event" method="POST" action="" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Event <span class="text-rose-500">*</span></label>
                <input type="text" id="edit-name" name="name" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Event <span class="text-rose-500">*</span></label>
                    <input type="date" id="edit-date" name="event_date" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Lokasi</label>
                    <input type="text" id="edit-location" name="location"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Penyelenggara</label>
                <input type="text" id="edit-organizer" name="organizer"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi</label>
                <textarea id="edit-description" name="description" rows="3"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition resize-none"></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Ganti Foto Event (opsional)</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-500 file:mr-3 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 transition">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-edit-event')"
                    class="flex-1 py-2.5 rounded-xl text-sm font-bold border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl text-sm font-bold bg-sky-600 text-white hover:bg-sky-700 transition shadow-md">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     MODAL: HAPUS EVENT (Admin) — Konfirmasi
══════════════════════════════════════════════════════════════ --}}
<div id="modal-delete-event" class="modal-overlay" onclick="closeOnBackdrop(event, 'modal-delete-event')">
    <div class="modal-box" style="max-width:400px">
        <div class="text-center">
            <div class="w-14 h-14 rounded-full bg-rose-100 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#e11d48" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
            </div>
            <h3 class="text-lg font-extrabold text-slate-900">Hapus Event?</h3>
            <p class="mt-2 text-sm text-slate-500">Event "<span id="delete-event-name" class="font-semibold text-slate-700"></span>" akan dihapus permanen beserta semua datanya.</p>
        </div>
        <form id="form-delete-event" method="POST" action="" class="flex gap-3 mt-6">
            @csrf
            <button type="button" onclick="closeModal('modal-delete-event')"
                class="flex-1 py-2.5 rounded-xl text-sm font-bold border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                Batal
            </button>
            <button type="submit"
                class="flex-1 py-2.5 rounded-xl text-sm font-bold bg-rose-600 text-white hover:bg-rose-700 transition shadow-md">
                Ya, Hapus
            </button>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════
     MODAL: KELOLA ANGGOTA (Admin)
══════════════════════════════════════════════════════════════ --}}
<div id="modal-members" class="modal-overlay" onclick="closeOnBackdrop(event, 'modal-members')">
    <div class="modal-box" style="max-width:480px">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Kelola Anggota</h3>
                <p id="members-event-name" class="text-xs text-emerald-700 font-semibold mt-0.5"></p>
            </div>
            <button onclick="closeModal('modal-members')" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div id="members-list" class="space-y-2 max-h-72 overflow-y-auto pr-1"></div>
        <p id="members-empty" class="hidden text-center text-sm text-slate-400 py-8">Belum ada anggota yang bergabung.</p>
    </div>
</div>
@endif
@endauth

{{-- ═══════════════════════════════════════════════════════════
     MODAL: DETAIL EVENT (Semua User)
══════════════════════════════════════════════════════════════ --}}
<div id="modal-detail-event" class="modal-overlay" onclick="closeOnBackdrop(event, 'modal-detail-event')">
    <div class="modal-box p-0 overflow-hidden" style="max-width:540px">
        <div id="detail-image-container" class="w-full h-48 flex items-center justify-center relative bg-gradient-to-br from-emerald-50 to-teal-100">
            <button onclick="closeModal('modal-detail-event')" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/70 backdrop-blur-sm text-slate-800 flex items-center justify-center hover:bg-white shadow-sm transition z-10">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            <img id="detail-image" src="" alt="" class="w-full h-full object-cover hidden">
            <div id="detail-placeholder" class="text-5xl hidden">🌿</div>
        </div>
        <div class="p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h3 id="detail-name" class="text-2xl font-black text-slate-900 leading-tight"></h3>
                    <p id="detail-organizer" class="text-sm font-bold text-emerald-600 mt-1"></p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="flex items-start gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="mt-0.5 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Tanggal</p>
                        <p id="detail-date" class="text-xs font-bold text-slate-700"></p>
                    </div>
                </div>
                <div class="flex items-start gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="mt-0.5 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Lokasi</p>
                        <p id="detail-location" class="text-xs font-bold text-slate-700"></p>
                    </div>
                </div>
            </div>

            <div>
                <p class="text-xs font-bold text-slate-800 mb-2">Penyelenggara</p>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <span id="detail-organizer-small" class="text-sm font-semibold text-slate-700"></span>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <p class="text-xs font-bold text-slate-800 mb-2">Tentang Event Ini</p>
                <div id="detail-description" class="text-sm text-slate-600 leading-relaxed max-h-40 overflow-y-auto pr-2"></div>
            </div>
            
            <div class="mt-6 pt-5 border-t border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2 text-sm text-slate-500 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span id="detail-participants-count"></span> Peserta Bergabung
                    </div>
                </div>
                
                {{-- Container for Participant List (Visible to array members/admin) --}}
                <div id="detail-participants-container" class="hidden">
                    <p class="text-xs font-bold text-slate-800 mb-3">Daftar Peserta</p>
                    <div id="detail-participants-list" class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto pr-1">
                        {{-- Injected by JS --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Data event untuk Modals (Detail & Kelola Anggota, dapat diakses publik/user regular untuk detail) --}}
@php
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
    $allEventsJson = $events->map(function ($e) {
        return [
            'id'                 => $e->id,
            'name'               => $e->name,
            'event_date'         => $e->event_date ? $e->event_date->isoFormat('D MMMM YYYY') : '-',
            'location'           => $e->location ?? 'Lokasi belum ditentukan',
            'organizer'          => $e->organizer ?? 'Eco Ranger Tourism',
            'participants_count' => $e->participants_count,
            'description'        => $e->description ?? '',
            'image_url'          => $e->image_path ? asset('storage/' . $e->image_path) : null,
            'is_joined'          => $e->is_joined ?? false,
            'participants'       => $e->participants->map(function ($p) {
                return ['id' => $p->id, 'name' => $p->name];
            })->values(),
        ];
    })->values();
@endphp
<script>
const eventsData = @json($allEventsJson);
const currentUserIsAdmin = @json($isAdmin);
</script>

@endsection

@push('scripts')
<script>
// ── Modal Helpers ──────────────────────────────────────────────
function openModal(id) {
    document.getElementById(id).classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.remove('active');
    document.body.style.overflow = '';
}
function closeOnBackdrop(e, id) {
    if (e.target === e.currentTarget) closeModal(id);
}

// ── User/Admin: Buka Detail Event ──────────────────────────────
function openDetailModal(id) {
    const eventObj = eventsData.find(e => e.id === id);
    if (!eventObj) return;

    document.getElementById('detail-name').textContent = eventObj.name;
    document.getElementById('detail-date').textContent = eventObj.event_date;
    document.getElementById('detail-location').textContent = eventObj.location;
    document.getElementById('detail-organizer').textContent = eventObj.organizer;
    document.getElementById('detail-organizer-small').textContent = eventObj.organizer;
    
    const descEl = document.getElementById('detail-description');
    if (eventObj.description) {
        descEl.innerHTML = eventObj.description.replace(/\n/g, '<br>');
    } else {
        descEl.innerHTML = '<span class="italic text-slate-400">Tidak ada deskripsi</span>';
    }

    document.getElementById('detail-participants-count').textContent = eventObj.participants_count;

    // Handle participants list visibility
    const participantsContainer = document.getElementById('detail-participants-container');
    const participantsList = document.getElementById('detail-participants-list');
    
    if (currentUserIsAdmin || eventObj.is_joined) {
        participantsContainer.classList.remove('hidden');
        participantsList.innerHTML = '';
        if (eventObj.participants && eventObj.participants.length > 0) {
            eventObj.participants.forEach(member => {
                const initials = member.name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();
                participantsList.innerHTML += `
                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 font-bold text-[10px] flex items-center justify-center flex-shrink-0">
                            ${initials}
                        </div>
                        <p class="text-xs font-bold text-slate-800 truncate" title="${member.name}">${member.name}</p>
                    </div>
                `;
            });
        } else {
            participantsList.innerHTML = '<p class="text-xs text-slate-400 italic col-span-2">Belum ada peserta.</p>';
        }
    } else {
        participantsContainer.classList.add('hidden');
    }

    const imgEl = document.getElementById('detail-image');
    const placeholderEl = document.getElementById('detail-placeholder');
    const containerEl = document.getElementById('detail-image-container');
    
    if (eventObj.image_url) {
        imgEl.src = eventObj.image_url;
        imgEl.classList.remove('hidden');
        placeholderEl.classList.add('hidden');
        containerEl.classList.remove('bg-gradient-to-br', 'from-emerald-50', 'to-teal-100');
    } else {
        imgEl.classList.add('hidden');
        imgEl.src = '';
        placeholderEl.classList.remove('hidden');
        containerEl.classList.add('bg-gradient-to-br', 'from-emerald-50', 'to-teal-100');
    }

    openModal('modal-detail-event');
}

// ── Admin: Edit Event ──────────────────────────────────────────
function openEditModal(id, name, description, date, location, organizer) {
    document.getElementById('edit-name').value        = name;
    document.getElementById('edit-description').value = description;
    document.getElementById('edit-date').value        = date;
    document.getElementById('edit-location').value    = location;
    document.getElementById('edit-organizer').value   = organizer;
    document.getElementById('form-edit-event').action = '/admin/aksi/' + id + '/update';
    openModal('modal-edit-event');
}

// ── Admin: Konfirmasi Hapus Event ──────────────────────────────
function confirmDelete(id, name) {
    document.getElementById('delete-event-name').textContent  = name;
    document.getElementById('form-delete-event').action = '/admin/aksi/' + id + '/delete';
    openModal('modal-delete-event');
}

// ── Admin: Kelola Anggota ──────────────────────────────────────
function openMembersModal(eventId, eventName) {
    document.getElementById('members-event-name').textContent = eventName;
    const listEl  = document.getElementById('members-list');
    const emptyEl = document.getElementById('members-empty');
    listEl.innerHTML = '';

    const eventObj = eventsData.find(e => e.id === eventId);
    const members  = eventObj ? eventObj.participants : [];

    if (members.length === 0) {
        listEl.classList.add('hidden');
        emptyEl.classList.remove('hidden');
    } else {
        listEl.classList.remove('hidden');
        emptyEl.classList.add('hidden');
        members.forEach(member => {
            const initials = member.name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();
            listEl.innerHTML += `
                <div class="flex items-center justify-between gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="member-avatar">${initials}</div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">${member.name}</p>
                        </div>
                    </div>
                    <form method="POST" action="/admin/aksi/${eventId}/members/${member.id}/remove" onsubmit="return confirm('Hapus ${member.name} dari event ini?')">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit"
                            class="px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 hover:bg-rose-100 transition">
                            Hapus
                        </button>
                    </form>
                </div>`;
        });
    }

    openModal('modal-members');
}

// ── User: Month Filter Dropdown ────────────────────────────────
const monthDropdown = document.getElementById('month-dropdown');
const btnMonthFilter = document.getElementById('btn-month-filter');

if (btnMonthFilter) {
    // Place dropdown relative to button
    btnMonthFilter.parentElement.style.position = 'relative';
    monthDropdown.style.top    = '100%';
    monthDropdown.style.left   = '0';
    monthDropdown.style.marginTop = '8px';

    function toggleMonthFilter() {
        monthDropdown.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (btnMonthFilter && !btnMonthFilter.contains(e.target) && !monthDropdown.contains(e.target)) {
            monthDropdown.classList.add('hidden');
        }
    });
}

// Close all modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        ['modal-add-event','modal-edit-event','modal-delete-event','modal-members','modal-detail-event'].forEach(closeModal);
    }
});
</script>
@endpush
