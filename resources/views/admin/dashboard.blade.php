@extends('layouts.app')

@section('title', 'Admin Dashboard — Eco Ranger Tourism')

@section('content')
<div class="min-h-screen bg-slate-50">

    {{-- Hero Header --}}
    <div class="bg-gradient-to-r from-emerald-800 to-emerald-600 px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center gap-4 mb-3">
                <div class="w-12 h-12 bg-white/15 rounded-2xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div>
                    <p class="text-emerald-200 text-xs font-bold uppercase tracking-widest">Panel Administrator</p>
                    <h1 class="text-3xl font-black text-white">Admin Dashboard</h1>
                </div>
            </div>
            <p class="text-emerald-100/80 text-sm mt-2">Selamat datang kembali. Pilih fitur di bawah untuk mengelola platform.</p>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">

            {{-- Kelola User --}}
            <a href="{{ route('users.index') }}" class="group block rounded-3xl bg-white p-6 shadow-sm border border-slate-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
                <div class="flex items-center justify-between mb-5">
                    <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:stroke-white transition-colors"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:stroke-emerald-600 group-hover:translate-x-1 transition-all"><path d="m9 18 6-6-6-6"/></svg>
                </div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">User Management</div>
                <div class="text-xl font-black text-slate-900 mb-2">Kelola User</div>
                <p class="text-sm text-slate-500 leading-relaxed">Lihat, tambah, edit, dan hapus akun pengguna platform.</p>
            </a>

            {{-- Kelola Marker --}}
            <a href="{{ route('markers.index') }}" class="group block rounded-3xl bg-white p-6 shadow-sm border border-slate-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
                <div class="flex items-center justify-between mb-5">
                    <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:stroke-white transition-colors"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:stroke-emerald-600 group-hover:translate-x-1 transition-all"><path d="m9 18 6-6-6-6"/></svg>
                </div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Marker Management</div>
                <div class="text-xl font-black text-slate-900 mb-2">Kelola Marker</div>
                <p class="text-sm text-slate-500 leading-relaxed">Buat, edit, dan kelola lokasi serta marker dalam peta.</p>
            </a>

            {{-- Kelola Laporan --}}
            <a href="{{ route('admin.reports.index') }}" class="group block rounded-3xl bg-white p-6 shadow-sm border border-slate-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
                <div class="flex items-center justify-between mb-5">
                    <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:stroke-white transition-colors"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:stroke-emerald-600 group-hover:translate-x-1 transition-all"><path d="m9 18 6-6-6-6"/></svg>
                </div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Laporan Admin</div>
                <div class="text-xl font-black text-slate-900 mb-2">Kelola Laporan</div>
                <p class="text-sm text-slate-500 leading-relaxed">Review dan tindak lanjuti laporan isu lingkungan dari pengguna.</p>
            </a>

            {{-- Kelola Edukasi --}}
            <a href="{{ route('admin.academy.index') }}" class="group block rounded-3xl bg-white p-6 shadow-sm border border-slate-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
                <div class="flex items-center justify-between mb-5">
                    <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:stroke-white transition-colors"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:stroke-emerald-600 group-hover:translate-x-1 transition-all"><path d="m9 18 6-6-6-6"/></svg>
                </div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Green Academy</div>
                <div class="text-xl font-black text-slate-900 mb-2">Kelola Edukasi & Kuis</div>
                <p class="text-sm text-slate-500 leading-relaxed">Tambah dan kelola modul edukasi serta kuis untuk pengguna.</p>
            </a>

            {{-- Peta --}}
            <a href="/" class="group block rounded-3xl bg-white p-6 shadow-sm border border-slate-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
                <div class="flex items-center justify-between mb-5">
                    <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center group-hover:bg-emerald-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:stroke-white transition-colors"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" x2="9" y1="3" y2="18"/><line x1="15" x2="15" y1="6" y2="21"/></svg>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:stroke-emerald-600 group-hover:translate-x-1 transition-all"><path d="m9 18 6-6-6-6"/></svg>
                </div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Peta Interaktif</div>
                <div class="text-xl font-black text-slate-900 mb-2">Lihat Peta</div>
                <p class="text-sm text-slate-500 leading-relaxed">Buka peta interaktif dan tambah marker langsung dari peta.</p>
            </a>

            {{-- Peringkat --}}
            <a href="/peringkat" class="group block rounded-3xl bg-white p-6 shadow-sm border border-slate-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-200">
                <div class="flex items-center justify-between mb-5">
                    <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center group-hover:bg-amber-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:stroke-white transition-colors"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:stroke-amber-500 group-hover:translate-x-1 transition-all"><path d="m9 18 6-6-6-6"/></svg>
                </div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Eco Rankings</div>
                <div class="text-xl font-black text-slate-900 mb-2">Papan Peringkat</div>
                <p class="text-sm text-slate-500 leading-relaxed">Lihat peringkat pengguna berdasarkan poin eco yang terkumpul.</p>
            </a>

        </div>
    </div>
</div>
@endsection

