@extends('layouts.app')

@php
    $isPublic = $isPublic ?? false;
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
@endphp

@section('title', $isAdmin ? 'Kelola Laporan - GreenTour' : ($isPublic ? 'Laporan - GreenTour' : 'Laporan Saya - GreenTour'))

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 4h12v16H6z"/><path d="M9 4v16"/><path d="M6 8h12"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $isAdmin ? 'Kelola Laporan' : ($isPublic ? 'Laporan' : 'Laporan Saya') }}</h1>
                <p class="text-sm text-slate-500">{{ $isAdmin ? 'Kelola semua laporan isu lingkungan dari pengguna.' : ($isPublic ? 'Lihat semua laporan yang masuk dari pengguna, baik login maupun tamu.' : 'Kelola laporan isu lingkungan Anda.') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @if(auth()->check() && !$isAdmin)
            <a href="{{ route('profile.settings') }}" class="rounded-full border border-emerald-100 bg-emerald-50 px-5 py-3 text-sm font-semibold text-emerald-700 hover:bg-emerald-100">Pengaturan Profil</a>
            <a href="{{ route('profile.index') }}" class="rounded-full border border-slate-200 bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Profil Saya</a>
            @endif
        </div>
    </div>

    <div class="flex flex-wrap gap-3 mb-6 pl-4">
        @php
            $filters = [
                'all' => 'Semua',
                'menunggu' => 'Menunggu',
                'diverifikasi' => 'Diverifikasi',
                'diterima' => 'Diterima',
                'ditolak' => 'Ditolak',
            ];
            $routeName = $isAdmin ? 'admin.reports.index' : ($isPublic ? 'reports.public' : 'reports.index');
        @endphp
        @foreach($filters as $key => $label)
            @php
                $isActive = $key === 'all' ? !$status : $status === $key;
            @endphp
            <a href="{{ route($routeName, ['status' => $key === 'all' ? null : $key]) }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ $isActive ? 'bg-toscagreen text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }} transition">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        @if($isAdmin)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Informasi Lokasi</th>
                            <th class="px-6 py-4">Bukti</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($reports as $report)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-5 font-mono text-slate-400">#{{ $loop->iteration }}</td>
                            <td class="px-6 py-5">
                                <div class="font-bold text-slate-800">{{ $report->title }}</div>
                                <div class="text-xs text-slate-500 mt-1">{{ $report->location ?: 'Koordinat: ' . number_format($report->latitude, 3) . ', ' . number_format($report->longitude, 3) }}</div>
                                <div class="text-xs text-slate-400 mt-2">{{ $report->report_date?->format('Y-m-d') ?? $report->created_at->format('Y-m-d') }} · {{ $report->user?->name ?? 'Anonim' }}</div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-slate-100 text-slate-700 border border-slate-200 text-xs font-semibold">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4 text-slate-600">
                                        <path d="M4 19.5A2.5 2.5 0 0 0 6.5 22h11A2.5 2.5 0 0 0 20 19.5V6.5A2.5 2.5 0 0 0 17.5 4h-11A2.5 2.5 0 0 0 4 6.5v13z"/>
                                        <path d="M4 8h16"/>
                                    </svg>
                                    {{ $report->photo_path ? 'Ada' : 'Tidak' }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex rounded-full px-3 py-2 text-xs font-semibold uppercase tracking-[0.2em] {{ $report->status === 'menunggu' ? 'bg-yellow-100 text-yellow-700' : ($report->status === 'diverifikasi' ? 'bg-sky-100 text-sky-700' : ($report->status === 'diterima' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700')) }}">{{ strtoupper($report->status) }}</span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.reports.edit', $report) }}" class="p-2 text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition-colors" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                    </a>
                                    <form action="{{ route('admin.reports.delete', $report) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                Belum ada laporan. Buat laporan baru untuk membantu lingkungan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <div class="mt-8 grid gap-5 xl:grid-cols-3 lg:grid-cols-2">
                @forelse($reports as $report)
                    @php
                        $reportAuthor = 'Anonim';
                        if ($report->user) {
                            if ($isAdmin || !$isPublic) {
                                $reportAuthor = $report->user->name;
                            } elseif (auth()->check() && auth()->id() === $report->user_id) {
                                $reportAuthor = $report->user->name;
                            }
                        }
                    @endphp
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="mb-4">
                            <p class="text-xl font-semibold text-slate-900">{{ $report->title }}</p>
                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ \Illuminate\Support\Str::limit($report->description, 120, '...') }}</p>
                        </div>

                        <div class="space-y-3 text-sm text-slate-600">
                            <p><span class="font-semibold text-slate-900">Oleh:</span> {{ $reportAuthor }}</p>
                            <p><span class="font-semibold text-slate-900">Status:</span> <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.2em] {{ $report->status === 'menunggu' ? 'bg-yellow-100 text-yellow-700' : ($report->status === 'diverifikasi' ? 'bg-sky-100 text-sky-700' : ($report->status === 'diterima' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700')) }}">{{ strtoupper($report->status) }}</span></p>
                            <p><span class="font-semibold text-slate-900">Dikirim:</span> {{ $report->report_date?->format('d M Y') ?? $report->created_at->format('d M Y') }}</p>
                        </div>

                        <div class="mt-6 flex items-center justify-between gap-3">
                            <span class="text-xs text-slate-500">Lokasi: {{ number_format($report->latitude, 3) }}, {{ number_format($report->longitude, 3) }}</span>
                            <a href="{{ route('reports.show', $report) }}" class="inline-flex items-center gap-1 whitespace-nowrap text-blue-600 font-semibold text-xs hover:text-blue-800 transition">Baca Selengkapnya <span aria-hidden="true">→</span></a>
                        </div>
                    </div>
                @empty
                    <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center text-sm text-slate-500 xl:col-span-3">
                        Belum ada laporan. Buat laporan baru untuk membantu lingkungan.
                    </div>
                @endforelse
            </div>
        @endif
    </div>
</div>
@endsection
