@extends('layouts.app')

@section('title', 'Kelola Materi Edukasi')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Kelola Materi Edukasi</h1>
            <p class="mt-2 text-sm text-slate-500">Materi di halaman ini langsung digunakan oleh Green Academy user.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.academy.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">Dashboard</a>
            <a href="{{ route('admin.academy.articles.create') }}" class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-100 transition hover:bg-emerald-700">Tambah Materi</a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="border-b border-slate-200 bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Materi</th>
                        <th class="px-6 py-4">Durasi</th>
                        <th class="px-6 py-4">Poin</th>
                        <th class="px-6 py-4">Kuis</th>
                        <th class="px-6 py-4">Progress</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($artikels as $artikel)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900">{{ $artikel->title }}</p>
                                <p class="mt-1 max-w-xl text-xs text-slate-500">{{ \Illuminate\Support\Str::limit(strip_tags($artikel->content), 110) }}</p>
                            </td>
                            <td class="px-6 py-4">{{ $artikel->duration ?? '-' }}</td>
                            <td class="px-6 py-4">{{ (int) $artikel->points }}</td>
                            <td class="px-6 py-4">
                                @if($artikel->quiz)
                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">Ada</span>
                                @else
                                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">Belum ada</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">{{ $artikel->progresses_count }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.academy.articles.edit', $artikel) }}" class="rounded-lg bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700 transition hover:bg-emerald-100">Edit</a>
                                    <form action="{{ route('admin.academy.articles.destroy', $artikel) }}" method="POST" onsubmit="return confirm('Hapus materi ini? Kuis dan progress terkait juga akan dihapus.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-rose-50 px-3 py-2 text-xs font-bold text-rose-700 transition hover:bg-rose-100">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500">Belum ada materi edukasi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($artikels->hasPages())
            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">{{ $artikels->links() }}</div>
        @endif
    </div>
</div>
@endsection
