@extends('layouts.app')

@section('title', 'Monitoring Progress Academy')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900">Monitoring Progress</h1>
            <p class="mt-2 text-sm text-slate-500">Pantau progres belajar dan skor kuis user Green Academy.</p>
        </div>
        <a href="{{ route('admin.academy.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">Dashboard</a>
    </div>

    <form method="GET" action="{{ route('admin.academy.progress') }}" class="mb-6 grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm md:grid-cols-[1fr_220px_auto]">
        <input type="text" name="search" value="{{ $search }}" class="rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="Cari nama atau email user">
        <select name="status" class="rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua progress</option>
            <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Pernah selesai</option>
            <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>Belum selesai</option>
            <option value="no_progress" {{ $status === 'no_progress' ? 'selected' : '' }}>Belum mulai</option>
        </select>
        <button type="submit" class="rounded-xl bg-emerald-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-emerald-700">Filter</button>
    </form>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="border-b border-slate-200 bg-slate-50 text-xs font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Materi Selesai</th>
                        <th class="px-6 py-4">Progress Tercatat</th>
                        <th class="px-6 py-4">Rata-rata Skor</th>
                        <th class="px-6 py-4">Skor Tertinggi</th>
                        <th class="px-6 py-4">Poin</th>
                        <th class="px-6 py-4">Update Terakhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-900">{{ $user->name }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $user->email }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-900">{{ $user->completed_modules_count }}</span>
                                <span class="text-slate-400">/ {{ $totalArticles }}</span>
                            </td>
                            <td class="px-6 py-4">{{ $user->total_progress_count }}</td>
                            <td class="px-6 py-4">{{ $user->average_score !== null ? number_format($user->average_score, 1) : '-' }}</td>
                            <td class="px-6 py-4">{{ $user->highest_score ?? '-' }}</td>
                            <td class="px-6 py-4">{{ number_format((int) $user->eco_points) }}</td>
                            <td class="px-6 py-4">
                                {{ $user->latest_progress_at ? \Illuminate\Support\Carbon::parse($user->latest_progress_at)->format('Y-m-d H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500">Tidak ada data progress sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection
