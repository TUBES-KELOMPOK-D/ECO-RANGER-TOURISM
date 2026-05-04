@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12">
    <!-- HEADER -->
    <div class="bg-slate-900 shadow-lg py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Kelola Tips Naik Peringkat</h1>
                    <p class="text-slate-400 font-medium">Berikan panduan bagi pengguna untuk meningkatkan skor mereka.</p>
                </div>
                <a href="{{ route('leaderboard') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold transition-all border border-slate-700">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 space-y-8">
        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500/10 p-4 text-emerald-600 border border-emerald-500/20 font-bold text-center">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h2 class="text-lg font-bold text-slate-900">Daftar Tips</h2>
                <button onclick="document.getElementById('createForm').classList.toggle('hidden')" class="px-4 py-2 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition-all shadow-sm text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah Tips
                </button>
            </div>

            <!-- CREATE FORM -->
            <div id="createForm" class="hidden p-6 border-b border-slate-100 bg-slate-50/30">
                <form action="{{ route('leaderboard.admin.tips.store') }}" method="POST" class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Tips</label>
                        <input type="text" name="title" placeholder="contoh: Selesaikan Materi Akademi" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-3 bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Icon (Lucide Name)</label>
                        <input type="text" name="icon" placeholder="book, check, star..." class="w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-3 bg-white">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Lengkap</label>
                        <textarea name="description" rows="3" placeholder="Jelaskan langkah-langkahnya..." required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-3 bg-white"></textarea>
                    </div>
                    <div class="sm:col-span-2 flex justify-end">
                        <button type="submit" class="rounded-xl bg-slate-900 px-8 py-3 text-sm font-bold text-white hover:bg-slate-800 transition-all">Simpan Tips</button>
                    </div>
                </form>
            </div>

            <div class="p-6 space-y-4">
                @foreach($tips as $tip)
                <div class="group p-6 bg-slate-50/50 rounded-2xl border border-slate-100 hover:border-emerald-200 hover:bg-white transition-all">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-white rounded-lg shadow-sm border border-slate-100 flex items-center justify-center text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900">{{ $tip->title }}</h3>
                                <p class="text-sm text-slate-600 mt-1">{{ $tip->description }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="document.getElementById('editTip-{{ $tip->id }}').classList.toggle('hidden')" class="p-2 text-slate-300 hover:text-emerald-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <form action="{{ route('leaderboard.admin.tips.destroy', $tip) }}" method="POST" onsubmit="return confirm('Hapus tips ini?')">
                                @csrf
                                <button type="submit" class="p-2 text-slate-300 hover:text-red-500 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2M10 11v6M14 11v6"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- EDIT FORM -->
                    <div id="editTip-{{ $tip->id }}" class="hidden mt-6 p-6 bg-emerald-50 rounded-xl border border-emerald-100">
                        <form action="{{ route('leaderboard.admin.tips.update', $tip) }}" method="POST" class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Tips</label>
                                <input type="text" name="title" value="{{ $tip->title }}" required class="w-full rounded-xl border-slate-200 text-sm p-3 bg-white">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Icon (Lucide Name)</label>
                                <input type="text" name="icon" value="{{ $tip->icon }}" class="w-full rounded-xl border-slate-200 text-sm p-3 bg-white">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Deskripsi Lengkap</label>
                                <textarea name="description" rows="3" required class="w-full rounded-xl border-slate-200 text-sm p-3 bg-white">{{ $tip->description }}</textarea>
                            </div>
                            <div class="sm:col-span-2 flex justify-end">
                                <button type="submit" class="rounded-xl bg-emerald-600 px-8 py-3 text-sm font-bold text-white hover:bg-emerald-700 transition-all">Update Tips</button>
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
