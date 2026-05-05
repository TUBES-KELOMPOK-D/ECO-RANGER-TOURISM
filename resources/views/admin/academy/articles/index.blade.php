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
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.academy.articles.edit', $artikel) }}" class="p-2 text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition-colors" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                    </a>
                                    <form action="{{ route('admin.academy.articles.destroy', $artikel) }}" method="POST" onsubmit="return confirm('Hapus materi ini? Kuis dan progress terkait juga akan dihapus.');" class="inline-block">
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
