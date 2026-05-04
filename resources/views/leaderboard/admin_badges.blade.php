@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12">
    <!-- HEADER -->
    <div class="bg-slate-900 shadow-lg py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Kelola Lencana & Pencapaian</h1>
                    <p class="text-slate-400 font-medium">Atur sistem lencana untuk memotivasi partisipasi pengguna.</p>
                </div>
                <a href="{{ route('leaderboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold transition-all border border-slate-700 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Kembali ke Peringkat
                </a>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 space-y-8">
        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500/10 p-4 text-emerald-600 border border-emerald-500/20 font-bold text-center animate-pulse">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h2 class="text-lg font-bold text-slate-900">Daftar Lencana Tersedia</h2>
                <button onclick="document.getElementById('createForm').classList.toggle('hidden')" class="px-4 py-2 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition-all shadow-sm text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah Lencana
                </button>
            </div>

            <!-- CREATE FORM -->
            <div id="createForm" class="hidden p-6 border-b border-slate-100 bg-slate-50/30">
                <form action="{{ route('leaderboard.admin.badges.store') }}" method="POST" class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lencana</label>
                        <input type="text" name="name" placeholder="contoh: Plastic Hunter" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-3 bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Slug Identitas</label>
                        <input type="text" name="slug" placeholder="plastic_hunter" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-3 bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Poin Reward</label>
                        <input type="number" name="points_reward" placeholder="0" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-3 bg-white">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi</label>
                        <input type="text" name="description" placeholder="Cara mendapatkan lencana ini..." required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-3 bg-white">
                    </div>
                    <div class="flex items-end gap-2">
                        <input type="hidden" name="icon" value="award">
                        <input type="hidden" name="category" value="general">
                        <input type="hidden" name="target" value="1">
                        <input type="hidden" name="level" value="1">
                        <input type="hidden" name="target_column" value="id">
                        <input type="hidden" name="target_condition" value=">=">
                        <button type="submit" class="flex-1 rounded-xl bg-slate-900 px-6 py-3 text-sm font-bold text-white hover:bg-slate-800 transition-all shadow-lg">Simpan</button>
                        <button type="button" onclick="document.getElementById('createForm').classList.add('hidden')" class="px-6 py-3 rounded-xl border border-slate-200 text-sm font-bold text-slate-500 hover:bg-slate-50 transition-all">Batal</button>
                    </div>
                </form>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($badges as $badge)
                <div class="group flex flex-col p-6 bg-slate-50/50 rounded-2xl border border-slate-100 hover:border-emerald-200 hover:bg-white transition-all relative overflow-hidden">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-white rounded-xl shadow-sm border border-slate-100 flex items-center justify-center text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 15V3"/><path d="M8 7l4-4 4 4"/><path d="M12 22a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"/></svg>
                        </div>
                        <div class="flex gap-1">
                            <button onclick="document.getElementById('editBadge-{{ $badge->id }}').classList.toggle('hidden')" class="p-2 text-slate-300 hover:text-emerald-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <form action="{{ route('leaderboard.admin.badges.destroy', $badge) }}" method="POST" onsubmit="return confirm('Hapus lencana ini?')">
                                @csrf
                                <button type="submit" class="p-2 text-slate-300 hover:text-red-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2M10 11v6M14 11v6"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- EDIT FORM (Hidden by default) -->
                    <div id="editBadge-{{ $badge->id }}" class="hidden mb-4 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                        <form action="{{ route('leaderboard.admin.badges.update', $badge) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Nama</label>
                                <input type="text" name="name" value="{{ $badge->name }}" required class="w-full rounded-lg border-slate-200 text-xs p-2">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Poin Reward</label>
                                <input type="number" name="points_reward" value="{{ $badge->points_reward }}" required class="w-full rounded-lg border-slate-200 text-xs p-2">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Deskripsi</label>
                                <textarea name="description" required class="w-full rounded-lg border-slate-200 text-xs p-2 h-20">{{ $badge->description }}</textarea>
                            </div>
                            <input type="hidden" name="category" value="{{ $badge->category }}">
                            <input type="hidden" name="target" value="{{ $badge->target }}">
                            <input type="hidden" name="level" value="{{ $badge->level }}">
                            <div class="flex gap-2">
                                <button type="submit" class="flex-1 bg-emerald-600 text-white text-xs font-bold py-2 rounded-lg hover:bg-emerald-700 transition-all">Simpan</button>
                                <button type="button" onclick="document.getElementById('editBadge-{{ $badge->id }}').classList.add('hidden')" class="px-4 bg-white border border-slate-200 text-slate-500 text-xs font-bold py-2 rounded-lg hover:bg-slate-50">Batal</button>
                            </div>
                        </form>
                    </div>

                    <div id="badgeInfo-{{ $badge->id }}">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-bold text-slate-900 text-lg">{{ $badge->name }}</h3>
                            <span class="text-xs font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg border border-emerald-100">+{{ $badge->points_reward }} pts</span>
                        </div>
                        <p class="text-xs text-slate-400 font-mono mb-3">{{ $badge->slug }}</p>
                        <p class="text-sm text-slate-600 line-clamp-3 mb-4 h-15">{{ $badge->description }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
