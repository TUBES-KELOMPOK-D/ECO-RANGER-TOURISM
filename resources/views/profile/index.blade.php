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
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.reports.index') }}" class="rounded-full border border-slate-200 bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Lihat Laporan</a>
                    @else
                        <a href="{{ route('reports.index') }}" class="rounded-full border border-slate-200 bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Lihat Laporan</a>
                    @endif
                </div>
            </div>

            <div class="mt-10 overflow-hidden rounded-[2rem] bg-gradient-to-r from-emerald-600 to-toscagreen p-8 text-white shadow-xl sm:p-10">
                <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.3em] text-emerald-100/80">Total Poin Eco</p>
                        <p class="mt-3 text-4xl font-black">{{ number_format($totalPoints) }}</p>
                    </div>
                    <div class="flex items-center justify-between gap-4 rounded-3xl bg-white/10 p-4 shadow-inner shadow-slate-950/10 md:w-[320px]">
                        <div>
                            <p class="text-sm uppercase tracking-[0.2em] text-emerald-100/90">Level Saat Ini</p>
                            <p class="mt-1 text-lg font-semibold">{{ $nextLevel['label'] }}</p>
                        </div>
                        <div class="rounded-3xl bg-white/20 px-4 py-3 text-sm font-bold uppercase tracking-[0.2em] text-white">{{ $nextLevel['label'] }}</div>
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
                    <p class="mt-3 text-xs text-white/80">{{ $totalPoints }} poin terkumpul. {{ $nextLevel['remaining'] }} poin lagi ke level berikutnya.</p>
                </div>
            </div>
        </div>

        <div class="rounded-3xl bg-white p-8 shadow-soft border border-slate-200">
            @php
                $statusLabels = [
                    'menunggu' => 'Baru',
                    'diverifikasi' => 'Diproses',
                    'diterima' => 'Selesai',
                    'ditolak' => 'Ditolak',
                ];
            @endphp
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900">Laporan Terbaru</h2>
                    <p class="mt-2 text-sm text-slate-500">Lihat laporan terakhir yang kamu kirim dan status terkininya.</p>
                </div>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.reports.index') }}" class="rounded-full border border-slate-200 bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Lihat Semua Laporan</a>
                @else
                    <a href="{{ route('reports.index') }}" class="rounded-full border border-slate-200 bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Lihat Semua Laporan</a>
                @endif
            </div>

            @if(isset($latestReport) && $latestReport)
                <div class="mt-8 rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">{{ $latestReport->category }}</p>
                            <p class="mt-3 text-2xl font-bold text-slate-900">{{ $latestReport->title }}</p>
                            <p class="mt-3 text-sm text-slate-600">{{ $latestReport->description ?: 'Tidak ada deskripsi tambahan.' }}</p>
                        </div>
                        <span class="rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] {{ $latestReport->status === 'menunggu' ? 'bg-yellow-100 text-yellow-700' : ($latestReport->status === 'diverifikasi' ? 'bg-sky-100 text-sky-700' : ($latestReport->status === 'diterima' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700')) }}">
                            {{ $statusLabels[$latestReport->status] ?? ucfirst($latestReport->status) }}
                        </span>
                    </div>
                    <div class="mt-5 grid gap-3 sm:grid-cols-3 text-sm text-slate-600">
                        <div class="rounded-3xl bg-white/90 p-4">Tanggal: {{ $latestReport->report_date->format('Y-m-d') }}</div>
                        <div class="rounded-3xl bg-white/90 p-4">Lokasi: {{ number_format($latestReport->latitude, 3) }}, {{ number_format($latestReport->longitude, 3) }}</div>
                        <div class="rounded-3xl bg-white/90 p-4">Jumlah Laporan: {{ $reportCount }}</div>
                    </div>
                </div>
            @else
                <div class="mt-8 rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center text-sm text-slate-500">
                    Belum ada laporan isu. Mulai kirim laporan untuk memantau statusnya di profil ini.
                </div>
            @endif
        </div>

        <div class="rounded-3xl bg-white p-8 shadow-soft border border-slate-200">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900">Aksi yang Diikuti</h2>
                    <p class="mt-2 text-sm text-slate-500">{{ $participatedEvents->count() }} aksi aktif yang sedang diikuti.</p>
                </div>
                <a href="{{ route('aksi.index') }}" class="rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700">Cari Aksi Sekarang</a>
            </div>

            @if($participatedEvents->count() > 0)
                <div class="mt-8 space-y-4">
                    @foreach($participatedEvents as $event)
                        <div class="rounded-3xl border border-emerald-200 bg-gradient-to-r from-emerald-50 to-teal-50 p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-slate-900">{{ $event->name }}</h3>
                                    
                                    <div class="mt-4 grid gap-3 sm:grid-cols-3 text-sm">
                                        <div class="flex items-center gap-2 text-slate-600">
                                            <span class="font-semibold">📍 Lokasi:</span>
                                            <span>{{ $event->location ?? 'Lokasi tidak tersedia' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-slate-600">
                                            <span class="font-semibold">📅 Tanggal:</span>
                                            <span>{{ $event->event_date->format('d M Y') ?? 'Tanggal tidak tersedia' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.2em] text-emerald-700">Sudah Bergabung</span>
                                        </div>
                                    </div>
                                    
                                    @if($event->description)
                                        <p class="mt-3 text-sm text-slate-600 line-clamp-2">{{ $event->description }}</p>
                                    @endif
                                </div>
                                <div class="flex flex-col gap-2 sm:flex-row">
                                    <a href="{{ route('aksi.chat', $event->id) }}" class="rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-100 text-center">
                                        💬 Chat Grup
                                    </a>
                                    <form action="{{ route('aksi.leave', $event->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan keikutsertaan?');">
                                        @csrf
                                        <button type="submit" class="rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-100">
                                            ❌ Batal
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mt-8 rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-slate-100 text-2xl text-emerald-700">
                        🌿
                    </div>
                    <p class="mt-6 text-sm font-semibold text-slate-900">Belum ada aksi yang diikuti.</p>
                    <p class="mt-2 text-sm text-slate-500">Jelajahi aksi lingkungan dan kumpulkan poin eco untuk naik level.</p>
                </div>
            @endif
        </div>

        <div class="rounded-3xl bg-white p-8 shadow-soft border border-slate-200">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900">Lencana & Pencapaian</h2>
                    <p class="mt-2 text-sm text-slate-500">Badge yang berhasil kamu kumpulkan dari aktivitasmu.</p>
                </div>
                <a href="{{ route('badges.index') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 hover:underline">Lihat Semua Lencana &rarr;</a>
            </div>

            @if(count($badges) > 0)
                <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-5">
                    @foreach($badges as $badge)
                        <div class="group relative flex flex-col items-center justify-center rounded-3xl border border-slate-100 bg-slate-50 p-6 text-center transition-all hover:border-emerald-200 hover:bg-white hover:shadow-md">
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white text-3xl shadow-sm transition-transform group-hover:scale-110 {{ $badge->is_achieved ? '' : 'grayscale opacity-40' }}">
                                {{ $badge->icon }}
                            </div>
                            <h3 class="mt-4 text-sm font-bold text-slate-900 line-clamp-1">{{ $badge->name }}</h3>
                            
                            <div class="mt-3 w-full">
                                <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-200">
                                    <div class="h-full bg-emerald-500 transition-all duration-1000" style="width: {{ $badge->progress_percentage }}%"></div>
                                </div>
                                <p class="mt-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">{{ (int)$badge->current_progress }}/{{ $badge->target }}</p>
                            </div>
                            
                            @if($badge->is_achieved)
                                <div class="absolute -right-1 -top-1 rounded-full bg-emerald-500 p-1.5 text-white shadow-lg">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mt-8 rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center text-sm text-slate-500">
                    Belum ada lencana yang tersedia.
                </div>
            @endif
        </div>

        {{-- Voucher Saya Section --}}
        <div class="rounded-3xl bg-white p-8 shadow-soft border border-slate-200">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900">Voucher & Reward</h2>
                    <p class="mt-2 text-sm text-slate-500">Gunakan voucher yang telah kamu klaim untuk menikmati benefit.</p>
                </div>
                <a href="{{ route('vouchers.index') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 hover:underline">Klaim Voucher Baru &rarr;</a>
            </div>

            @if(count($userVouchers) > 0)
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($userVouchers as $uv)
                        <div class="group relative flex items-center gap-4 rounded-3xl border border-slate-100 bg-slate-50 p-4 transition-all hover:border-emerald-200 hover:bg-white hover:shadow-md overflow-hidden">
                            <!-- Ticket Cutout Effect -->
                            <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-white border-r border-slate-100 rounded-full z-10"></div>
                            <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-white border-l border-slate-100 rounded-full z-10"></div>
                            
                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-100 text-2xl text-emerald-600 shadow-sm flex-shrink-0">
                                🎫
                            </div>
                            <div class="flex-1 min-width-0">
                                <h3 class="text-sm font-bold text-slate-900 truncate">{{ $uv->name }}</h3>
                                <p class="text-[10px] font-medium text-slate-500 mt-1">Status: 
                                    <span class="{{ $uv->pivot->status === 'digunakan' ? 'text-slate-400' : 'text-emerald-600 font-bold' }}">
                                        {{ ucfirst($uv->pivot->status) }}
                                    </span>
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="{{ route('vouchers.index') }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white text-[10px] font-bold hover:bg-slate-800 transition-colors">
                                    {{ $uv->pivot->status === 'digunakan' ? 'Detail' : 'Gunakan' }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="mt-8 rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center text-sm text-slate-500">
                    Kamu belum memiliki voucher. 
                    <a href="{{ route('vouchers.index') }}" class="text-emerald-600 font-bold hover:underline">Klaim voucher pertamamu sekarang!</a>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
