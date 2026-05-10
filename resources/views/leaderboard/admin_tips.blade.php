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
                        Kelola <span class="text-emerald-300">Tips Peringkat</span>
                    </h1>
                    <p class="text-emerald-100/80 text-sm font-medium">Berikan panduan bagi pengguna untuk meningkatkan skor mereka.</p>
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
                <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Daftar Tips</h2>
                <button onclick="document.getElementById('createForm').classList.toggle('hidden')" class="px-6 py-3 bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah Tips
                </button>
            </div>

            <!-- CREATE FORM -->
            <div id="createForm" class="hidden p-8 border-b border-slate-100 bg-slate-50/50">
                <form action="{{ route('leaderboard.admin.tips.store') }}" method="POST" class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Judul Tips</label>
                        <input type="text" name="title" placeholder="contoh: Selesaikan Materi Akademi" required class="w-full rounded-2xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-4 bg-white font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Icon (Nama Lucide)</label>
                        <input type="text" name="icon" placeholder="book, star, zap..." class="w-full rounded-2xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-4 bg-white font-bold">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Deskripsi Lengkap</label>
                        <textarea name="description" rows="3" placeholder="Jelaskan langkah-langkahnya..." required class="w-full rounded-2xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-4 bg-white font-bold"></textarea>
                    </div>
                    <div class="sm:col-span-2 flex justify-end gap-3">
                        <button type="submit" class="rounded-2xl bg-slate-900 px-8 py-4 text-xs font-black text-white hover:bg-slate-800 transition-all shadow-xl uppercase tracking-widest">Simpan Tips</button>
                        <button type="button" onclick="document.getElementById('createForm').classList.add('hidden')" class="px-8 py-4 rounded-2xl border border-slate-200 text-xs font-black text-slate-500 hover:bg-slate-50 transition-all uppercase tracking-widest">Batal</button>
                    </div>
                </form>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($tips as $tip)
                <div class="group p-8 bg-slate-50/50 rounded-[2rem] border border-slate-100 hover:border-emerald-500/50 hover:bg-white hover:shadow-2xl transition-all duration-500 relative overflow-hidden">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-black text-slate-900 text-xl tracking-tight mb-2">{{ $tip->title }}</h3>
                                <p class="text-sm text-slate-500 font-bold leading-relaxed line-clamp-2">{{ $tip->description }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="document.getElementById('editTip-{{ $tip->id }}').classList.toggle('hidden')" class="p-3 text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <form action="{{ route('leaderboard.admin.tips.destroy', $tip) }}" method="POST" onsubmit="return confirm('Hapus tips ini?')">
                                @csrf
                                <button type="submit" class="p-3 text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2M10 11v6M14 11v6"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- EDIT FORM -->
                    <div id="editTip-{{ $tip->id }}" class="hidden mt-6 p-8 bg-emerald-50 rounded-3xl border border-emerald-100">
                        <form action="{{ route('leaderboard.admin.tips.update', $tip) }}" method="POST" class="grid grid-cols-1 gap-6">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Judul Tips</label>
                                <input type="text" name="title" value="{{ $tip->title }}" required class="w-full rounded-2xl border-slate-200 text-sm p-4 bg-white font-bold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Icon</label>
                                <input type="text" name="icon" value="{{ $tip->icon }}" class="w-full rounded-2xl border-slate-200 text-sm p-4 bg-white font-bold">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Deskripsi Lengkap</label>
                                <textarea name="description" rows="3" required class="w-full rounded-2xl border-slate-200 text-sm p-4 bg-white font-bold">{{ $tip->description }}</textarea>
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="flex-1 rounded-2xl bg-emerald-600 px-8 py-4 text-xs font-black text-white hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-600/20 uppercase tracking-widest">Update</button>
                                <button type="button" onclick="document.getElementById('editTip-{{ $tip->id }}').classList.add('hidden')" class="px-8 py-4 rounded-2xl border border-slate-200 text-xs font-black text-slate-500 hover:bg-white transition-all uppercase tracking-widest">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
