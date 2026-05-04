@extends('layouts.app')

@section('title', 'Detail Laporan - GreenTour')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="rounded-3xl bg-white p-8 shadow-soft border border-slate-200">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Detail Laporan</h1>
                <p class="mt-2 text-sm text-slate-500">Informasi lengkap status dan detail laporan yang dikirim oleh pengguna.</p>
            </div>
            <a href="{{ route('reports.public') }}" class="rounded-full border border-slate-200 bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Kembali ke Laporan</a>
        </div>

        @php
            $reportAuthor = 'Anonim';
            if ($report->user) {
                if (auth()->check() && auth()->user()->role === 'admin') {
                    $reportAuthor = $report->user->name;
                } elseif (auth()->check() && auth()->id() === $report->user_id) {
                    $reportAuthor = $report->user->name;
                }
            }
        @endphp

        <div class="mt-10 grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
                <div class="space-y-6">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Judul</p>
                        <p class="mt-2 text-xl font-semibold text-slate-900">{{ $report->title }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Kategori</p>
                        <p class="mt-2 text-lg text-slate-900">{{ $report->category }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Deskripsi</p>
                        <p class="mt-2 text-slate-700 leading-7">{{ $report->description }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Lokasi</p>
                        <p class="mt-2 text-slate-900">{{ number_format($report->latitude, 6) }}, {{ number_format($report->longitude, 6) }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Tanggal Laporan</p>
                        <p class="mt-2 text-slate-900">{{ $report->report_date ? $report->report_date->format('d M Y') : $report->created_at->format('d M Y') }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Pelapor</p>
                        <p class="mt-2 text-slate-900">{{ $reportAuthor }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Status</p>
                        <span class="inline-flex rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] {{ $report->status === 'menunggu' ? 'bg-yellow-100 text-yellow-700' : ($report->status === 'diverifikasi' ? 'bg-sky-100 text-sky-700' : ($report->status === 'diterima' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700')) }}">{{ strtoupper($report->status) }}</span>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-900">Bukti Foto</h2>
                    <div class="mt-5 rounded-3xl bg-slate-50 p-4 text-center">
                        @if($report->photo_path)
                            <img src="{{ asset('storage/' . $report->photo_path) }}" alt="Bukti Laporan" class="mx-auto max-h-80 w-full rounded-3xl object-cover" />
                        @else
                            <p class="text-sm text-slate-500">Tidak ada foto bukti tersedia.</p>
                        @endif
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-slate-900">Info Tambahan</h2>
                    <div class="mt-4 space-y-3 text-sm text-slate-600">
                        <p><span class="font-semibold text-slate-900">Latitude</span>: {{ $report->latitude }}</p>
                        <p><span class="font-semibold text-slate-900">Longitude</span>: {{ $report->longitude }}</p>
                        <p><span class="font-semibold text-slate-900">Kategori</span>: {{ $report->category }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
