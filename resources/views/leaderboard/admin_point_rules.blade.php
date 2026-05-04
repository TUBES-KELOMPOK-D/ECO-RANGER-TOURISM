@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12">
    <!-- HEADER -->
    <div class="bg-slate-900 shadow-lg py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Kelola Aturan Poin</h1>
                    <p class="text-slate-400 font-medium">Atur jumlah poin yang didapatkan pengguna dari setiap aktivitas.</p>
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
                <h2 class="text-lg font-bold text-slate-900">Daftar Aturan Poin</h2>
                <button onclick="document.getElementById('createForm').classList.toggle('hidden')" class="px-4 py-2 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition-all shadow-sm text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah Aturan
                </button>
            </div>

            <!-- CREATE FORM -->
            <div id="createForm" class="hidden p-6 border-b border-slate-100 bg-slate-50/30">
                <form action="{{ route('leaderboard.admin.point_rules.store') }}" method="POST" class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    @csrf
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Aktivitas</label>
                        <input type="text" name="action_name" placeholder="contoh: Lapor Isu Lingkungan" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-3 bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Poin</label>
                        <input type="number" name="points_reward" placeholder="10" required class="w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-3 bg-white">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 rounded-xl bg-slate-900 px-8 py-3 text-sm font-bold text-white hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/20">Simpan</button>
                        <button type="button" onclick="document.getElementById('createForm').classList.add('hidden')" class="px-6 py-3 rounded-xl border border-slate-200 text-sm font-bold text-slate-500 hover:bg-slate-50 transition-all">Batal</button>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-500 border-b border-slate-100 font-bold text-xs uppercase tracking-widest">
                            <th class="px-6 py-4">Aktivitas</th>
                            <th class="px-6 py-4 text-center">Poin</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($rules as $rule)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v10"/><path d="M18 8l-6 6-6-6"/></svg>
                                    </div>
                                    <div class="font-bold text-slate-900">{{ $rule->action_name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center font-black text-emerald-600">+{{ $rule->points_reward }} pts</td>
                            <td class="px-6 py-5 text-right flex justify-end gap-2">
                                <button onclick="document.getElementById('editRule-{{ $rule->id }}').classList.toggle('hidden')" class="p-2 text-emerald-500 hover:bg-emerald-50 rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <form action="{{ route('leaderboard.admin.point_rules.destroy', $rule) }}" method="POST" onsubmit="return confirm('Hapus aturan ini?')">
                                    @csrf
                                    <button type="submit" class="p-2 text-red-400 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2M10 11v6M14 11v6"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <!-- EDIT RULE FORM -->
                        <tr id="editRule-{{ $rule->id }}" class="hidden bg-slate-50/80">
                            <td colspan="3" class="px-6 py-6 border-x border-emerald-100">
                                <form action="{{ route('leaderboard.admin.point_rules.update', $rule) }}" method="POST" class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                                    @csrf
                                    @method('PUT')
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Aktivitas</label>
                                        <input type="text" name="action_name" value="{{ $rule->action_name }}" required class="w-full rounded-xl border-slate-200 text-sm p-3 bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Poin</label>
                                        <input type="number" name="points_reward" value="{{ $rule->points_reward }}" required class="w-full rounded-xl border-slate-200 text-sm p-3 bg-white">
                                    </div>
                                    <div class="flex items-end gap-2">
                                        <button type="submit" class="flex-1 rounded-xl bg-emerald-600 px-8 py-3 text-sm font-bold text-white hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20">Simpan Perubahan</button>
                                        <button type="button" onclick="document.getElementById('editRule-{{ $rule->id }}').classList.add('hidden')" class="px-6 py-3 rounded-xl border border-slate-200 text-sm font-bold text-slate-500 hover:bg-white transition-all">Batal</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
