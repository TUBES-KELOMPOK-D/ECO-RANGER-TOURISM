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
                        Kelola <span class="text-emerald-300">Voucher</span>
                    </h1>
                    <p class="text-emerald-100/80 text-sm font-medium">Manajemen reward penukaran poin untuk pengguna.</p>
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
                <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.2em]">Daftar Voucher</h2>
                <button onclick="document.getElementById('createForm').classList.toggle('hidden')" class="px-6 py-3 bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                    Tambah Voucher
                </button>
            </div>

            <!-- CREATE FORM -->
            <div id="createForm" class="hidden p-8 border-b border-slate-100 bg-slate-50/50">
                <form action="{{ route('leaderboard.admin.vouchers.store') }}" method="POST" class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    @csrf
                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Nama Voucher</label>
                        <input type="text" name="name" placeholder="Voucher Wisata Rp 100rb" required class="w-full rounded-2xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-4 bg-white font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Target Poin</label>
                        <input type="number" name="poin_required" placeholder="500" required class="w-full rounded-2xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-4 bg-white font-bold">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Deskripsi</label>
                        <input type="text" name="description" placeholder="Syarat dan ketentuan penukaran..." required class="w-full rounded-2xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm p-4 bg-white font-bold">
                    </div>
                    <div class="flex items-end gap-3">
                        <button type="submit" class="flex-1 rounded-2xl bg-slate-900 px-8 py-4 text-xs font-black text-white hover:bg-slate-800 transition-all shadow-xl uppercase tracking-widest">Simpan</button>
                        <button type="button" onclick="document.getElementById('createForm').classList.add('hidden')" class="px-8 py-4 rounded-2xl border border-slate-200 text-xs font-black text-slate-500 hover:bg-slate-50 transition-all uppercase tracking-widest">Batal</button>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-400 border-b border-slate-100 font-black text-[10px] uppercase tracking-[0.2em]">
                            <th class="px-8 py-5">Voucher & Deskripsi</th>
                            <th class="px-8 py-5 text-center">Target Poin</th>
                            <th class="px-8 py-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($vouchers as $voucher)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100 group-hover:scale-110 transition-transform duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg>
                                    </div>
                                    <div>
                                        <div class="font-black text-slate-900 text-base tracking-tight">{{ $voucher->name }}</div>
                                        <div class="text-[10px] text-slate-400 mt-0.5 truncate max-w-xs font-bold">{{ $voucher->description }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <span class="inline-flex items-center gap-1.5 font-black text-emerald-600 bg-emerald-50 px-4 py-2 rounded-xl border border-emerald-100">
                                    {{ number_format($voucher->poin_required, 0, ',', '.') }} PTS
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick="document.getElementById('editVoucher-{{ $voucher->id }}').classList.toggle('hidden')" class="p-3 text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </button>
                                    <form action="{{ route('leaderboard.admin.vouchers.destroy', $voucher) }}" method="POST" onsubmit="return confirm('Hapus voucher ini?')">
                                        @csrf
                                        <button type="submit" class="p-3 text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2M10 11v6M14 11v6"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <!-- EDIT VOUCHER FORM -->
                        <tr id="editVoucher-{{ $voucher->id }}" class="hidden">
                            <td colspan="3" class="px-8 py-8 bg-emerald-50/50">
                                <form action="{{ route('leaderboard.admin.vouchers.update', $voucher) }}" method="POST" class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                                    @csrf
                                    @method('PUT')
                                    <div class="sm:col-span-2">
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Nama Voucher</label>
                                        <input type="text" name="name" value="{{ $voucher->name }}" required class="w-full rounded-2xl border-slate-200 text-sm p-4 bg-white font-bold">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Target Poin</label>
                                        <input type="number" name="poin_required" value="{{ $voucher->poin_required }}" required class="w-full rounded-2xl border-slate-200 text-sm p-4 bg-white font-bold">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Deskripsi</label>
                                        <input type="text" name="description" value="{{ $voucher->description }}" required class="w-full rounded-2xl border-slate-200 text-sm p-4 bg-white font-bold">
                                    </div>
                                    <div class="flex items-end gap-3">
                                        <button type="submit" class="flex-1 rounded-2xl bg-emerald-600 px-8 py-4 text-xs font-black text-white hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-600/20 uppercase tracking-widest">Update</button>
                                        <button type="button" onclick="document.getElementById('editVoucher-{{ $voucher->id }}').classList.add('hidden')" class="px-8 py-4 rounded-2xl border border-slate-200 text-xs font-black text-slate-500 hover:bg-white transition-all uppercase tracking-widest">Batal</button>
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
