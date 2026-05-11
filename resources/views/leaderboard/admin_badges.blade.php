@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12">
    <!-- HEADER -->
    <div class="bg-gradient-to-r from-emerald-800 to-emerald-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-white/10 backdrop-blur-md mb-3 text-emerald-100">
                        Admin Console
                    </span>
                    <h1 class="text-4xl font-black tracking-tight leading-tight mb-2">
                        Kelola <span class="text-emerald-300">Lencana</span>
                    </h1>
                    <p class="text-emerald-100/80 text-sm font-medium">Manajemen sistem pencapaian dan reward lencana pengguna.</p>
                </div>
                <a href="{{ route('leaderboard') }}" class="group inline-flex items-center gap-2 px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all border border-white/20 backdrop-blur-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-1 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 space-y-8">
        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500/10 p-4 text-emerald-600 border border-emerald-500/20 font-black text-xs uppercase tracking-widest text-center animate-pulse">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
                <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Daftar Lencana</h2>
                <button onclick="document.getElementById('createForm').classList.toggle('hidden')" class="px-6 py-3 bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah Lencana
                </button>
            </div>

            <!-- CREATE FORM -->
            <div id="createForm" class="hidden p-8 border-b border-slate-100 bg-slate-50/50">
                <form action="{{ route('leaderboard.admin.badges.store') }}" method="POST" class="grid grid-cols-1 gap-8 sm:grid-cols-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Nama Lencana</label>
                        <input type="text" name="name" placeholder="Plastic Hunter" required class="w-full rounded-2xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-4 bg-white font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Slug (ID Unik)</label>
                        <input type="text" name="slug" placeholder="plastic_hunter" required class="w-full rounded-2xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-4 bg-white font-mono">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Poin Reward</label>
                        <input type="number" name="points_reward" placeholder="0" required class="w-full rounded-2xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-4 bg-white font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Level</label>
                        <select name="level" required class="w-full rounded-2xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-4 bg-white font-bold">
                            <option value="Bronze">Bronze</option>
                            <option value="Silver">Silver</option>
                            <option value="Gold">Gold</option>
                            <option value="Platinum">Platinum</option>
                        </select>
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Deskripsi</label>
                        <input type="text" name="description" placeholder="Cara mendapatkan lencana ini..." required class="w-full rounded-2xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-4 bg-white font-bold">
                    </div>
                    <div class="flex items-end gap-3">
                        <input type="hidden" name="icon" value="award">
                        <input type="hidden" name="category" value="general">
                        <input type="hidden" name="target" value="1">
                        <input type="hidden" name="target_column" value="id">
                        <input type="hidden" name="target_condition" value=">=">
                        <button type="submit" class="flex-1 rounded-2xl bg-slate-900 px-6 py-4 text-xs font-black text-white hover:bg-slate-800 transition-all shadow-xl uppercase tracking-widest">Simpan</button>
                        <button type="button" onclick="document.getElementById('createForm').classList.add('hidden')" class="px-6 py-4 rounded-2xl border border-slate-200 text-xs font-black text-slate-500 hover:bg-slate-50 transition-all uppercase tracking-widest">Batal</button>
                    </div>
                </form>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($badges as $badge)
                <div class="group flex flex-col p-8 bg-slate-50/50 rounded-[2rem] border border-slate-100 hover:border-emerald-500/50 hover:bg-white hover:shadow-2xl transition-all duration-500 relative overflow-hidden">
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-14 h-14 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 15V3"/><path d="M8 7l4-4 4 4"/><path d="M12 22a8 8 0 1 0 0-16 8 8 0 0 0 0 16z"/></svg>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="document.getElementById('editBadge-{{ $badge->id }}').classList.toggle('hidden')" class="p-3 text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <form action="{{ route('leaderboard.admin.badges.destroy', $badge) }}" method="POST" onsubmit="return confirm('Hapus lencana ini?')">
                                @csrf
                                <button type="submit" class="p-3 text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2M10 11v6M14 11v6"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- EDIT FORM -->
                    <div id="editBadge-{{ $badge->id }}" class="hidden mb-6 p-6 bg-emerald-50 rounded-3xl border border-emerald-100">
                        <form action="{{ route('leaderboard.admin.badges.update', $badge) }}" method="POST" class="space-y-5">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Nama Lencana</label>
                                <input type="text" name="name" value="{{ $badge->name }}" required class="w-full rounded-xl border-slate-200 text-sm p-3 font-bold">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Poin Reward</label>
                                    <input type="number" name="points_reward" value="{{ $badge->points_reward }}" required class="w-full rounded-xl border-slate-200 text-sm p-3 font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Level</label>
                                    <select name="level" required class="w-full rounded-xl border-slate-200 text-sm p-3 font-bold bg-white">
                                        <option value="Bronze" {{ $badge->level == 'Bronze' ? 'selected' : '' }}>Bronze</option>
                                        <option value="Silver" {{ $badge->level == 'Silver' ? 'selected' : '' }}>Silver</option>
                                        <option value="Gold" {{ $badge->level == 'Gold' ? 'selected' : '' }}>Gold</option>
                                        <option value="Platinum" {{ $badge->level == 'Platinum' ? 'selected' : '' }}>Platinum</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Deskripsi</label>
                                <textarea name="description" required class="w-full rounded-xl border-slate-200 text-sm p-3 h-24 font-bold">{{ $badge->description }}</textarea>
                            </div>
                            <input type="hidden" name="category" value="{{ $badge->category }}">
                            <input type="hidden" name="target" value="{{ $badge->target }}">
                            <div class="flex gap-3">
                                <button type="submit" class="flex-1 bg-emerald-600 text-white text-xs font-black py-3 rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-600/20 uppercase tracking-widest">Update</button>
                                <button type="button" onclick="document.getElementById('editBadge-{{ $badge->id }}').classList.add('hidden')" class="px-6 bg-white border border-slate-200 text-slate-500 text-xs font-black py-3 rounded-xl hover:bg-slate-50 uppercase tracking-widest">Batal</button>
                            </div>
                        </form>
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border border-emerald-200 bg-emerald-50 text-emerald-700">
                                {{ $badge->level }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border border-slate-200 bg-white text-slate-500">
                                {{ $badge->category }}
                            </span>
                        </div>
                        <h3 class="font-black text-slate-900 text-xl tracking-tight mb-2">{{ $badge->name }}</h3>
                        <p class="text-[10px] text-slate-400 font-mono mb-4 uppercase tracking-wider">{{ $badge->slug }}</p>
                        <p class="text-xs text-slate-500 font-bold leading-relaxed line-clamp-3 mb-6">{{ $badge->description }}</p>
                        
                        <div class="mt-auto pt-6 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Reward</span>
                            <span class="font-black text-emerald-600 bg-emerald-50 px-4 py-2 rounded-xl border border-emerald-100 text-sm">+{{ number_format($badge->points_reward, 0, ',', '.') }} PTS</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
