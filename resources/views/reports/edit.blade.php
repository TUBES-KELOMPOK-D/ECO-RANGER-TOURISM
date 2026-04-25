@extends('layouts.app')

@section('title', 'Edit Laporan - GreenTour')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="rounded-3xl bg-white p-8 shadow-soft border border-slate-200">
        <div class="flex items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Edit Laporan</h1>
                <p class="mt-2 text-sm text-slate-500">Perbarui status laporan isu lingkungan.</p>
            </div>
            <a href="{{ route('reports.index') }}" class="rounded-full border border-slate-200 bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Kembali</a>
        </div>

        <div class="grid gap-8 lg:grid-cols-2">
            <!-- Report Details -->
            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Detail Laporan</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Judul</p>
                            <p class="text-slate-900">{{ $report->title }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Kategori</p>
                            <p class="text-slate-900">{{ $report->category }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Deskripsi</p>
                            <p class="text-slate-900">{{ $report->description ?: 'Tidak ada deskripsi' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Lokasi</p>
                            <p class="text-slate-900">{{ number_format($report->latitude, 6) }}, {{ number_format($report->longitude, 6) }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Tanggal Laporan</p>
                            <p class="text-slate-900">{{ $report->report_date->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-500">Pelapor</p>
                            <p class="text-slate-900">{{ $report->user ? $report->user->name : 'Anonymous' }}</p>
                        </div>
                    </div>
                </div>

                @if($report->photo_path)
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Bukti Foto</h3>
                    <img src="{{ asset('storage/' . $report->photo_path) }}" alt="Bukti Laporan" class="w-full rounded-3xl object-cover border border-slate-200" />
                </div>
                @endif
            </div>

            <!-- Update Status Form -->
            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Update Status</h3>

                <form action="{{ route('admin.reports.update', $report) }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Status Laporan</label>
                        <select name="status" class="w-full rounded-3xl border border-slate-200 bg-white px-5 py-4 text-sm text-slate-900 outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100">
                            <option value="menunggu" {{ $report->status === 'menunggu' ? 'selected' : '' }}>Menunggu - Laporan baru diterima</option>
                            <option value="diverifikasi" {{ $report->status === 'diverifikasi' ? 'selected' : '' }}>Diverifikasi - Laporan sedang diproses</option>
                            <option value="diterima" {{ $report->status === 'diterima' ? 'selected' : '' }}>Diterima - Laporan valid dan ditindaklanjuti</option>
                            <option value="ditolak" {{ $report->status === 'ditolak' ? 'selected' : '' }}>Ditolak - Laporan tidak valid</option>
                        </select>
                        @error('status')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 rounded-3xl bg-emerald-600 px-6 py-4 text-sm font-bold text-white shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition">
                            Update Status
                        </button>
                    </div>
                </form>

                <form action="{{ route('admin.reports.delete', $report) }}" method="POST" class="mt-4" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full rounded-3xl bg-red-600 px-6 py-4 text-sm font-bold text-white shadow-lg shadow-red-200 hover:bg-red-700 transition">
                        Hapus Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection