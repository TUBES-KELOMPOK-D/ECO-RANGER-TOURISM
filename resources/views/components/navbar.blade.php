{{-- ===== MODERN NAVBAR ===== --}}
<style>
    .nav-link {
        position: relative;
        padding: 6px 14px;
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        border-radius: 10px;
        transition: color 0.2s ease, background 0.2s ease;
        text-decoration: none;
        letter-spacing: -0.01em;
        white-space: nowrap;
    }
    .nav-link:hover {
        color: #1e293b;
        background: rgba(15, 23, 42, 0.05);
    }
    .nav-link.active {
        color: #059669;
        background: #ecfdf5;
    }
    .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 50%;
        transform: translateX(-50%);
        width: 4px;
        height: 4px;
        background: #10b981;
        border-radius: 9999px;
    }
    .avatar-btn {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .avatar-btn:hover {
        transform: scale(1.08);
        box-shadow: 0 0 0 3px #d1fae5;
    }
    .avatar-dropdown {
        transform: translateY(8px) scale(0.97);
        opacity: 0;
        pointer-events: none;
        transition: transform 0.18s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.15s ease;
    }
    .avatar-group:hover .avatar-dropdown,
    .avatar-group:focus-within .avatar-dropdown {
        transform: translateY(0) scale(1);
        opacity: 1;
        pointer-events: auto;
    }
    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 14px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        border-radius: 10px;
        text-decoration: none;
        transition: background 0.15s, color 0.15s;
    }
    .dropdown-item:hover { background: #f1f5f9; color: #0f172a; }
    .dropdown-item.danger { color: #dc2626; }
    .dropdown-item.danger:hover { background: #fef2f2; }
    .dropdown-item.accent { color: #059669; }
    .dropdown-item.accent:hover { background: #ecfdf5; }

    .mobile-menu-enter { animation: slideDown 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .lapor-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 9px 20px;
        border-radius: 99px;
        font-weight: 700;
        font-size: 13.5px;
        text-decoration: none;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
        transition: box-shadow 0.2s, transform 0.2s;
        letter-spacing: -0.01em;
    }
    .lapor-btn:hover {
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45);
        transform: translateY(-1px);
    }
    .lapor-btn:active { transform: translateY(0); }
</style>

<nav class="sticky top-0 z-[100] font-sans w-full"
     style="background: rgba(255,255,255,0.85); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-bottom: 1px solid rgba(226,232,240,0.6);">
    <div class="w-full px-4 sm:px-6 lg:px-10">
        <div class="flex justify-between items-center h-[68px]">

            {{-- ===== LOGO ===== --}}
            <a href="/" class="flex items-center gap-3 text-decoration-transparent shrink-0" style="text-decoration:none;">
                <div style="width:38px;height:38px;background:linear-gradient(135deg,#10b981,#059669);border-radius:12px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(16,185,129,0.3);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 12"/></svg>
                </div>
                <span class="text-[17px] font-black tracking-tighter text-slate-800 hidden md:block">Eco<span class="text-emerald-600">Ranger</span> <span class="text-slate-400 font-semibold">Tourism</span></span>
            </a>

            {{-- ===== DESKTOP NAV ===== --}}
            <div class="hidden md:flex items-center gap-1">
                @if(!request()->is('login') && !request()->is('register'))
                    <a href="/" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Beranda</a>
                    <a href="/aksi" class="nav-link {{ request()->is('aksi') ? 'active' : '' }}">Aksi</a>
                    @if(!auth()->check() || auth()->user()->role !== 'admin')
                        <a href="/laporan" class="nav-link {{ request()->is('laporan') ? 'active' : '' }}">Laporan</a>
                    @endif
                    <a href="/leaderboard" class="nav-link {{ (request()->is('leaderboard*') || request()->is('vouchers*') || request()->is('badges*')) ? 'active' : '' }}">Peringkat</a>
                    <a href="{{ route('academy.index') }}" class="nav-link {{ request()->routeIs('academy.*') ? 'active' : '' }}">Akademi</a>
                @endif
            </div>

            {{-- ===== RIGHT SIDE ===== --}}
            <div class="hidden md:flex items-center gap-3">
                @if(!request()->is('login') && !request()->is('register'))
                    <a href="/pelaporan" class="lapor-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                        Lapor Isu
                    </a>
                @endif

                @auth
                    <div class="relative avatar-group">
                        <button class="avatar-btn flex items-center gap-2.5 pl-1 pr-3 py-1 rounded-full border border-slate-200 bg-white shadow-sm hover:shadow-md focus:outline-none transition-all">
                            <div class="w-8 h-8 rounded-full overflow-hidden flex items-center justify-center shrink-0 {{ auth()->user()->role === 'admin' ? 'bg-slate-900 text-white' : 'bg-emerald-100 text-emerald-700' }}">
                                @if(auth()->user()->role === 'admin')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                @elseif(auth()->user()->photo)
                                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Foto" class="h-full w-full object-cover" />
                                @else
                                    <span class="text-xs font-black">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                @endif
                            </div>
                            <span class="text-sm font-bold text-slate-700 max-w-[90px] truncate">{{ explode(' ', auth()->user()->name)[0] }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                        </button>

                        <div class="avatar-dropdown absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-xl z-50" style="border: 1px solid rgba(226,232,240,0.8);">
                            {{-- Header --}}
                            <div class="px-4 py-3 border-b border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ auth()->user()->role }}</p>
                                <p class="text-[13px] font-bold text-slate-800 truncate mt-0.5">{{ auth()->user()->name }}</p>
                            </div>
                            <div class="p-2">
                                @if(auth()->user()->role === 'user')
                                    <a href="/profile" class="dropdown-item">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
                                        Profil Saya
                                    </a>
                                    <a href="/reports" class="dropdown-item">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        Pantau Laporan
                                    </a>
                                @elseif(auth()->user()->role === 'admin')
                                    <a href="/admin/dashboard" class="dropdown-item accent">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                                        Panel Admin
                                    </a>
                                    <a href="{{ route('markers.index') }}" class="dropdown-item accent">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                        Kelola Marker
                                    </a>
                                    <a href="/reports" class="dropdown-item accent">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        Kelola Laporan
                                    </a>
                                    <a href="{{ route('admin.academy.index') }}" class="dropdown-item accent">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                                        Kelola Edukasi & Kuis
                                    </a>
                                @endif
                                <a href="/profile/settings" class="dropdown-item">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                                    Pengaturan
                                </a>
                                <div class="border-t border-slate-100 mt-1 pt-1">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item danger w-full text-left">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="/login" class="text-sm font-bold text-slate-600 hover:text-slate-900 px-4 py-2 rounded-xl hover:bg-slate-100 transition-all" style="text-decoration:none;">Masuk</a>
                    <a href="/register" class="text-sm font-bold text-white px-5 py-2.5 rounded-full shadow-md hover:shadow-lg transition-all" style="background:linear-gradient(135deg,#0f172a,#1e293b);text-decoration:none;">Daftar</a>
                @endauth
            </div>

            {{-- ===== MOBILE TOGGLE ===== --}}
            <div class="md:hidden flex items-center gap-2">
                @if(!request()->is('login') && !request()->is('register'))
                    <a href="/pelaporan" class="p-2.5 rounded-xl text-white shadow-md" style="background:linear-gradient(135deg,#10b981,#059669);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                    </a>
                @endif
                <button id="mobile-menu-btn" class="p-2.5 text-slate-600 rounded-xl hover:bg-slate-100 transition-colors focus:outline-none border border-slate-200/60">
                    <svg id="icon-menu" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" class="block"><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                    <svg id="icon-close" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round" class="hidden"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ===== MOBILE MENU ===== --}}
    <div id="mobile-menu" class="hidden md:hidden" style="background:rgba(255,255,255,0.97);backdrop-filter:blur(20px);border-top:1px solid rgba(226,232,240,0.6);">
        <div class="px-4 py-3 space-y-1">
            @if(!request()->is('login') && !request()->is('register'))
                <a href="/" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ request()->is('/') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" style="text-decoration:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Beranda
                </a>
                <a href="/aksi" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ request()->is('aksi') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" style="text-decoration:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Aksi
                </a>
                @if(!auth()->check() || auth()->user()->role !== 'admin')
                    <a href="/laporan" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ request()->is('laporan') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" style="text-decoration:none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        Laporan
                    </a>
                @endif
                <a href="/leaderboard" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ (request()->is('leaderboard*') || request()->is('vouchers*') || request()->is('badges*')) ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" style="text-decoration:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                    Peringkat
                </a>
                <a href="{{ route('academy.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all {{ request()->routeIs('academy.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" style="text-decoration:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                    Akademi
                </a>
            @endif

            <div class="pt-3 mt-2 border-t border-slate-100 space-y-1">
                @auth
                    <button id="mobile-profile-btn" class="w-full flex items-center justify-between px-4 py-3 rounded-xl bg-slate-50 mb-2 hover:bg-slate-100 transition-all focus:outline-none">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full overflow-hidden flex items-center justify-center shrink-0 {{ auth()->user()->role === 'admin' ? 'bg-slate-900 text-white' : 'bg-emerald-100 text-emerald-700' }}">
                                @if(auth()->user()->role === 'admin')
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                @elseif(auth()->user()->photo)
                                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Foto" class="h-full w-full object-cover"/>
                                @else
                                    <span class="text-xs font-black">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                @endif
                            </div>
                            <div class="text-left">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ auth()->user()->role }}</p>
                                <p class="text-sm font-bold text-slate-800">{{ auth()->user()->name }}</p>
                            </div>
                        </div>
                        <svg id="mobile-profile-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400 transition-transform duration-200"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>

                    <div id="mobile-profile-menu" class="hidden space-y-1 pl-2 border-l-2 border-slate-100 ml-4 mb-2">
                        @if(auth()->user()->role === 'admin')
                            <a href="/admin/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm text-emerald-700 hover:bg-emerald-50 transition-all" style="text-decoration:none;">Panel Admin</a>
                            <a href="{{ route('markers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm text-emerald-700 hover:bg-emerald-50 transition-all" style="text-decoration:none;">Kelola Marker</a>
                            <a href="/reports" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm text-emerald-700 hover:bg-emerald-50 transition-all" style="text-decoration:none;">Kelola Laporan</a>
                            <a href="{{ route('admin.academy.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm text-emerald-700 hover:bg-emerald-50 transition-all" style="text-decoration:none;">Kelola Edukasi & Kuis</a>
                        @elseif(auth()->user()->role === 'user')
                            <a href="/profile" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm text-slate-700 hover:bg-slate-50 transition-all" style="text-decoration:none;">Profil Saya</a>
                            <a href="/reports" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm text-slate-700 hover:bg-slate-50 transition-all" style="text-decoration:none;">Pantau Laporan</a>
                        @endif
                        <a href="/profile/settings" class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm text-slate-700 hover:bg-slate-50 transition-all" style="text-decoration:none;">Pengaturan</a>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 w-full text-left px-4 py-3 rounded-xl font-semibold text-sm text-red-600 hover:bg-red-50 transition-all">Logout</button>
                    </form>
                @else
                    <a href="/register" class="block text-center w-full px-4 py-3 rounded-xl font-bold text-sm text-white hover:opacity-90 transition-all" style="background:linear-gradient(135deg,#0f172a,#1e293b);text-decoration:none;">Daftar</a>
                    <a href="/login" class="block text-center w-full px-4 py-3 rounded-xl font-bold text-sm text-slate-700 bg-slate-100 hover:bg-slate-200 transition-all" style="text-decoration:none;">Masuk</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const iconMenu = document.getElementById('icon-menu');
        const iconClose = document.getElementById('icon-close');

        if (btn && menu) {
            btn.addEventListener('click', () => {
                const isOpen = !menu.classList.contains('hidden');
                if (isOpen) {
                    menu.classList.add('hidden');
                } else {
                    menu.classList.remove('hidden');
                    menu.classList.add('mobile-menu-enter');
                    setTimeout(() => menu.classList.remove('mobile-menu-enter'), 200);
                }
                iconMenu.classList.toggle('hidden');
                iconMenu.classList.toggle('block');
                iconClose.classList.toggle('hidden');
                iconClose.classList.toggle('block');
                
                const searchBar = document.getElementById('searchBar');
                if (searchBar) {
                    if (isOpen) {
                        searchBar.classList.remove('hidden');
                    } else {
                        searchBar.classList.add('hidden');
                    }
                }
            });
        }

        const profileBtn = document.getElementById('mobile-profile-btn');
        const profileMenu = document.getElementById('mobile-profile-menu');
        const profileChevron = document.getElementById('mobile-profile-chevron');

        if (profileBtn && profileMenu && profileChevron) {
            profileBtn.addEventListener('click', () => {
                profileMenu.classList.toggle('hidden');
                if (!profileMenu.classList.contains('hidden')) {
                    profileChevron.style.transform = 'rotate(180deg)';
                } else {
                    profileChevron.style.transform = 'rotate(0deg)';
                }
            });
        }
    });
</script>
