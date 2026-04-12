<nav class="sticky top-0 z-[100] bg-white/80 backdrop-blur-xl border-b border-slate-200/60 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            
            <a href="/" class="flex items-center gap-3 cursor-pointer group hover:opacity-80 transition-opacity decoration-transparent">
                <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 12"/></svg>
                </div>
                <span class="text-xl font-black tracking-tighter text-slate-800">Eco<span class="text-emerald-600"> Ranger Tourism</span></span>
            </a>

            <div class="hidden md:flex items-center gap-1 lg:gap-4">
                {{-- Sembunyikan menu ini jika berada di halaman login atau register --}}
                @if(!request()->is('login') && !request()->is('register'))
                    <a href="/" class="px-4 py-2 rounded-xl text-sm font-bold transition-all bg-emerald-50 text-emerald-700 decoration-transparent">Beranda</a>
                    <a href="/aksi" class="px-4 py-2 rounded-xl text-sm font-bold transition-all text-slate-500 hover:text-slate-800 hover:bg-slate-50 decoration-transparent">Aksi</a>
                    <a href="/peringkat" class="px-4 py-2 rounded-xl text-sm font-bold transition-all text-slate-500 hover:text-slate-800 hover:bg-slate-50 decoration-transparent">Peringkat</a>
                    <a href="/akademi" class="px-4 py-2 rounded-xl text-sm font-bold transition-all text-slate-500 hover:text-slate-800 hover:bg-slate-50 decoration-transparent">Akademi</a>
                    
                    <div class="h-6 w-px bg-slate-200 mx-2"></div>
                    
                    <a href="/pelaporan" class="flex items-center gap-2 bg-emerald-600 text-white px-6 py-2.5 rounded-full font-bold text-sm shadow-lg shadow-emerald-100 hover:bg-emerald-700 hover:-translate-y-0.5 transition-all active:translate-y-0 decoration-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                        Lapor Isu
                    </a>
                @endif

                @auth
                    <a href="/profile" class="ml-4 w-10 h-10 rounded-full flex items-center justify-center font-bold border-2 transition-all bg-emerald-100 text-emerald-700 border-emerald-500 hover:scale-110 decoration-transparent">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </a>
                @else
                    <div class="flex items-center gap-2 ml-4">
                        <a href="/login" class="px-5 py-2.5 rounded-full font-bold text-sm transition-all text-slate-600 hover:bg-slate-100 decoration-transparent">
                            Login
                        </a>
                        <a href="/register" class="px-5 py-2.5 rounded-full font-bold text-sm transition-all bg-slate-900 text-white hover:bg-slate-800 shadow-lg decoration-transparent">
                            Registrasi
                        </a>
                    </div>
                @endauth
            </div>

            <div class="md:hidden flex items-center gap-4">
                @if(!request()->is('login') && !request()->is('register'))
                    <a href="/pelaporan" class="p-2.5 bg-emerald-600 text-white rounded-xl shadow-lg shadow-emerald-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                    </a>
                @endif
                <button id="mobile-menu-btn" class="p-2.5 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors focus:outline-none">
                    <svg id="icon-menu" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="block"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                    <svg id="icon-close" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            
        </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden bg-white border-b border-slate-100 shadow-xl absolute w-full left-0 transition-all">
        <div class="px-4 pt-2 pb-6 space-y-1">
            @if(!request()->is('login') && !request()->is('register'))
                <a href="/" class="flex items-center gap-4 w-full p-4 rounded-2xl transition-all bg-emerald-50 text-emerald-700 font-bold decoration-transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Beranda
                </a>
                <a href="/aksi" class="flex items-center gap-4 w-full p-4 rounded-2xl transition-all text-slate-600 hover:bg-slate-50 font-bold decoration-transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Aksi
                </a>
                <a href="/peringkat" class="flex items-center gap-4 w-full p-4 rounded-2xl transition-all text-slate-600 hover:bg-slate-50 font-bold decoration-transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                    Peringkat
                </a>
                <a href="/akademi" class="flex items-center gap-4 w-full p-4 rounded-2xl transition-all text-slate-600 hover:bg-slate-50 font-bold decoration-transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                    Akademi
                </a>
            @endif
            
            <div class="pt-4 border-t border-slate-50">
                @auth
                    <a href="/profile" class="flex items-center gap-3 w-full p-4 rounded-2xl font-bold bg-slate-50 text-slate-700 hover:bg-slate-100 transition-all">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs bg-emerald-100 text-emerald-700">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                        Profil Saya
                    </a>
                @else
                    <div class="flex flex-col gap-2">
                        <a href="/register" class="text-center w-full p-4 rounded-2xl font-bold bg-slate-900 text-white hover:bg-slate-800 transition-all decoration-transparent">
                            Registrasi
                        </a>
                        <a href="/login" class="text-center w-full p-4 rounded-2xl font-bold bg-slate-50 text-slate-700 hover:bg-slate-100 transition-all decoration-transparent">
                            Login
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const iconMenu = document.getElementById('icon-menu');
        const iconClose = document.getElementById('icon-close');

        if (btn && menu) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
                iconMenu.classList.toggle('hidden');
                iconMenu.classList.toggle('block');
                iconClose.classList.toggle('hidden');
                iconClose.classList.toggle('block');
            });
        }
    });
</script>