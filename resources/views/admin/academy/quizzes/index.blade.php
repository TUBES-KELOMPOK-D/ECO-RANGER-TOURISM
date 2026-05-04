@extends('layouts.app')

@section('title', 'Kelola Kuis Academy')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Kelola Kuis</h1>
            <p class="mt-2 text-sm text-slate-500">Kuis tersambung ke satu materi dan dipakai oleh flow user Green Academy.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.academy.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">Dashboard</a>
            <a href="{{ route('admin.academy.quizzes.create') }}" class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-100 transition hover:bg-emerald-700">Tambah Kuis</a>
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
                        <th class="px-6 py-4">Kuis</th>
                        <th class="px-6 py-4">Materi</th>
                        <th class="px-6 py-4">Jumlah Soal</th>
                        <th class="px-6 py-4">Update</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($kuisList as $kuis)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900">{{ $kuis->title }}</p>
                            </td>
                            <td class="px-6 py-4">{{ $kuis->artikel?->title ?? '-' }}</td>
                            <td class="px-6 py-4">{{ count($kuis->questions ?? []) }}</td>
                            <td class="px-6 py-4">{{ optional($kuis->updated_at)->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.academy.quizzes.edit', $kuis) }}" class="rounded-lg bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700 transition hover:bg-emerald-100">Edit</a>
                                    <form action="{{ route('admin.academy.quizzes.destroy', $kuis) }}" method="POST" onsubmit="return confirm('Hapus kuis ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-rose-50 px-3 py-2 text-xs font-bold text-rose-700 transition hover:bg-rose-100">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-500">Belum ada kuis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kuisList->hasPages())
            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">{{ $kuisList->links() }}</div>
        @endif
    </div>
</div>
@endsection
