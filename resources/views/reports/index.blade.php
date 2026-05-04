@extends('layouts.app')

@php
    $isPublic = $isPublic ?? false;
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
@endphp

@section('title', $isAdmin ? 'Kelola Laporan - GreenTour' : ($isPublic ? 'Laporan - GreenTour' : 'Laporan Saya - GreenTour'))

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="rounded-3xl bg-white p-8 shadow-soft border border-slate-200">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">{{ $isAdmin ? 'Kelola Laporan' : ($isPublic ? 'Laporan' : 'Laporan Saya') }}</h1>
                <p class="mt-2 text-sm text-slate-500">{{ $isAdmin ? 'Kelola semua laporan isu lingkungan dari pengguna.' : ($isPublic ? 'Lihat semua laporan yang masuk dari pengguna, baik login maupun tamu.' : 'Kelola laporan isu lingkungan Anda.') }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                @if(auth()->check() && !$isAdmin)
                <a href="{{ route('profile.settings') }}" class="rounded-full border border-emerald-100 bg-emerald-50 px-5 py-3 text-sm font-semibold text-emerald-700 hover:bg-emerald-100">Pengaturan Profil</a>
                <a href="{{ route('profile.index') }}" class="rounded-full border border-slate-200 bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Profil Saya</a>
                @endif
            </div>
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
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

        @if($isAdmin)
            <div class="mt-8 space-y-5">
                @forelse($reports as $report)
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex-1">
                                <p class="text-xl font-semibold text-slate-900">{{ $report->title }}</p>
                                <p class="mt-2 text-sm text-slate-600">{{ $report->description }}</p>
                                @if($report->user)
                                    <p class="mt-2 text-xs text-slate-500">Oleh: {{ $report->user->name }}</p>
                                @endif
                            </div>
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                                <span class="rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] {{ $report->status === 'menunggu' ? 'bg-yellow-100 text-yellow-700' : ($report->status === 'diverifikasi' ? 'bg-sky-100 text-sky-700' : ($report->status === 'diterima' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700')) }}">{{ strtoupper($report->status) }}</span>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.reports.edit', $report) }}" class="rounded-full bg-blue-600 px-4 py-2 text-xs font-bold text-white hover:bg-blue-700 transition">Edit</a>
                                    <form action="{{ route('admin.reports.delete', $report) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-full bg-red-600 px-4 py-2 text-xs font-bold text-white hover:bg-red-700 transition">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 flex flex-wrap items-center gap-4 text-sm text-slate-500">
                            <span>Tanggal: {{ $report->report_date?->format('Y-m-d') ?? $report->created_at->format('Y-m-d') }}</span>
                            <span>Lokasi: {{ number_format($report->latitude, 3) }}, {{ number_format($report->longitude, 3) }}</span>
                            @if($report->photo_path)
                                <span>Ada foto bukti</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center text-sm text-slate-500">
                        Belum ada laporan. Buat laporan baru untuk membantu lingkungan.
                    </div>
                @endforelse
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
