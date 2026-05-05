<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Daftar akun baru di Eco Ranger Tourism dan mulai berkontribusi untuk lingkungan.">
    <title>Registrasi — Eco Ranger Tourism</title>
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
                <p class="mt-4 text-emerald-100/80 text-base leading-relaxed max-w-sm">Daftar sekarang dan jadilah bagian dari komunitas pelestari alam Indonesia.</p>
                <div class="mt-10 flex flex-col gap-4">
                    <div class="flex items-center gap-4 bg-white/10 backdrop-blur rounded-2xl px-5 py-4 text-left">
                        <div class="w-10 h-10 bg-emerald-500/30 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                        </div>
                        <div><p class="text-sm font-bold text-white">Lapor Isu Lingkungan</p><p class="text-xs text-emerald-200">Dapatkan +10 poin setiap laporan</p></div>
                    </div>
                    <div class="flex items-center gap-4 bg-white/10 backdrop-blur rounded-2xl px-5 py-4 text-left">
                        <div class="w-10 h-10 bg-emerald-500/30 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </div>
                        <div><p class="text-sm font-bold text-white">Ikuti Event Lingkungan</p><p class="text-xs text-emerald-200">Dapatkan +50 poin per event</p></div>
                    </div>
                    <div class="flex items-center gap-4 bg-white/10 backdrop-blur rounded-2xl px-5 py-4 text-left">
                        <div class="w-10 h-10 bg-emerald-500/30 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                        </div>
                        <div><p class="text-sm font-bold text-white">Naik Level Eco-Ranger</p><p class="text-xs text-emerald-200">Kumpulkan poin, raih level tertinggi</p></div>
                    </div>
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
                    <h2 class="text-3xl font-black text-slate-900">Buat Akun Baru</h2>
                    <p class="mt-2 text-slate-500 text-sm">Bergabunglah dan mulai perjalanan Anda.</p>
                </div>
                @if ($errors->any())
                <div class="mt-6 bg-red-50 border border-red-200 rounded-2xl px-4 py-3">
                    <div class="flex items-center gap-2 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                        <p class="text-sm text-red-600 font-bold">Terdapat kesalahan:</p>
                    </div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm text-red-600 font-medium">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form action="{{ route('register') }}" method="POST" class="mt-8 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="John Doe" class="input-field">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="input-field">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                        <input type="password" name="password" required placeholder="Min. 8 karakter" class="input-field">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required placeholder="Ulangi password" class="input-field">
                    </div>
                    <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3.5 rounded-2xl hover:bg-emerald-700 active:scale-[0.98] transition-all shadow-lg shadow-emerald-100 flex items-center justify-center gap-2 mt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        Daftar Sekarang
                    </button>
                </form>
                <p class="mt-6 text-center text-sm text-slate-500">Sudah punya akun? <a href="{{ route('login') }}" class="text-emerald-600 font-bold hover:text-emerald-700 transition-colors decoration-transparent">Masuk</a></p>
                <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                    <a href="/" class="text-xs text-slate-400 hover:text-slate-600 transition-colors">← Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>