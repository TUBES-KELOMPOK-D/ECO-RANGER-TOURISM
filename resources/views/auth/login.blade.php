<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Masuk ke akun Eco Ranger Tourism dan mulai berkontribusi untuk lingkungan.">
    <title>Login — Eco Ranger Tourism</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter','Poppins','ui-sans-serif','system-ui','sans-serif'] } } }
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', 'Poppins', ui-sans-serif, system-ui, sans-serif; }
        .eco-panel { background-color: #064e3b; background-image: radial-gradient(circle at 20% 50%, rgba(16,185,129,.15) 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(52,211,153,.1) 0%, transparent 40%); }
        .input-field { width:100%; padding:12px 16px; border:1.5px solid #e2e8f0; border-radius:12px; font-size:14px; color:#1e293b; background:#f8fafc; outline:none; transition:all .2s ease; font-family:inherit; }
        .input-field:focus { border-color:#059669; background:#fff; box-shadow:0 0 0 3px rgba(5,150,105,.1); }
    </style>
</head>
<body class="min-h-screen bg-slate-50">
    <x-navbar />
    <div class="min-h-[calc(100vh-80px)] flex">
        {{-- LEFT: Eco Branding --}}
        <div class="hidden lg:flex lg:w-1/2 eco-panel flex-col justify-center items-center px-16 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-emerald-900/40 rounded-full translate-y-1/2 -translate-x-1/2"></div>
            <div class="relative z-10 text-center">
                <div class="w-20 h-20 bg-emerald-500/20 border border-emerald-400/30 rounded-3xl flex items-center justify-center mx-auto mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 12"/></svg>
                </div>
                <h1 class="text-4xl font-black text-white leading-tight">Eco<span class="text-emerald-400"> Ranger</span><br>Tourism</h1>
                <p class="mt-4 text-emerald-100/80 text-base leading-relaxed max-w-sm">Bergabunglah dalam misi pelestarian alam Indonesia. Laporkan, jelajahi, dan dapatkan poin eco.</p>
                @php
                    $usersCount = \App\Models\User::count();
                    $markersCount = \App\Models\Marker::count();
                @endphp
                <div class="mt-10 grid grid-cols-3 gap-4">
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-4"><p class="text-2xl font-black text-white">{{ $usersCount }}</p><p class="text-xs text-emerald-200 mt-1 font-medium">Pengguna Aktif</p></div>
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-4"><p class="text-2xl font-black text-white">50+</p><p class="text-xs text-emerald-200 mt-1 font-medium">Laporan Isu</p></div>
                    <div class="bg-white/10 backdrop-blur rounded-2xl p-4"><p class="text-2xl font-black text-white">{{ $markersCount }}</p><p class="text-xs text-emerald-200 mt-1 font-medium">Lokasi Wisata</p></div>
                </div>
            </div>
        </div>
        {{-- RIGHT: Form --}}
        <div class="flex-1 flex items-center justify-center px-6 py-12 lg:px-16">
            <div class="w-full max-w-md">
                <div class="lg:hidden flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 12"/></svg></div>
                    <span class="text-lg font-black text-slate-800">Eco<span class="text-emerald-600"> Ranger Tourism</span></span>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-slate-900">Selamat Datang</h2>
                    <p class="mt-2 text-slate-500 text-sm">Masuk ke akun Eco Ranger Anda untuk melanjutkan.</p>
                </div>
                @if ($errors->any())
                <div class="mt-6 flex items-center gap-3 bg-red-50 border border-red-200 rounded-2xl px-4 py-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                    <p class="text-sm text-red-600 font-semibold">{{ $errors->first() }}</p>
                </div>
                @endif
                <form action="{{ route('login') }}" method="POST" class="mt-8 space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="input-field">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                        <input type="password" name="password" required placeholder="••••••••" class="input-field">
                    </div>
                    <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3.5 rounded-2xl hover:bg-emerald-700 active:scale-[0.98] transition-all shadow-lg shadow-emerald-100 flex items-center justify-center gap-2 mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                        Masuk
                    </button>
                </form>
                <p class="mt-6 text-center text-sm text-slate-500">Belum punya akun? <a href="{{ route('register') }}" class="text-emerald-600 font-bold hover:text-emerald-700 transition-colors decoration-transparent">Daftar Gratis</a></p>
                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <a href="/" class="text-xs text-slate-400 hover:text-slate-600 transition-colors">← Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>