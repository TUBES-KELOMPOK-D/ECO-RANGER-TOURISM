<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $material->title }} – Akademi</title>
    <meta name="description" content="{{ $material->description }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen font-sans text-slate-900">

    <x-navbar />

    {{-- Header Bar (mirip desain TSX: px-6 py-5 border-b) --}}
    <div class="bg-white px-6 py-5 border-b border-slate-100 flex items-center gap-4 shadow-sm">
        <a href="{{ url('/akademi') }}"
           class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-200 transition-colors decoration-transparent">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.5"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6"/>
            </svg>
        </a>
        <div>
            <h3 class="font-black text-slate-800 leading-tight">Materi Edukasi</h3>
        </div>
    </div>

    {{-- Scrollable content area --}}
    <main class="max-w-3xl mx-auto px-4 sm:px-6 py-6 space-y-8">

        {{-- Badge + Title + Description --}}
        <div>
            <div class="inline-flex items-center gap-1.5 text-xs font-black text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full mb-4 uppercase tracking-widest">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
                Modul Pembelajaran
            </div>
            <h2 class="text-3xl font-black text-slate-800 leading-tight mb-4">{{ $material->title }}</h2>
            <p class="text-slate-500 font-medium leading-relaxed">{{ $material->description }}</p>
        </div>

        {{-- Konten Materi --}}
        <div class="bg-white p-6 md:p-8 rounded-[32px] shadow-sm border border-slate-100">
            <p class="text-slate-700 leading-loose font-medium whitespace-pre-line">{{ $material->content }}</p>
        </div>

        {{-- Tombol Mulai Kuis --}}
        <div class="bg-white p-6 border border-slate-100 rounded-[28px] shadow-sm">
            @auth
                <a href="{{ url('/akademi/kuis/' . $material->id) }}"
                   class="w-full bg-indigo-600 text-white py-4 rounded-[20px] font-black shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 flex items-center justify-center gap-2 decoration-transparent block text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/>
                        <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/>
                        <path d="M4 22h16"/>
                        <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/>
                        <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/>
                        <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/>
                    </svg>
                    Mulai Kuis Evaluasi
                </a>
            @else
                <div class="text-center space-y-4">
                    <p class="text-slate-500 font-medium text-sm">
                        Silakan login terlebih dahulu untuk mengikuti kuis dan mendapatkan poin.
                    </p>
                    <a href="{{ route('login') }}"
                       class="w-full bg-slate-900 text-white font-bold py-4 rounded-[20px] hover:bg-slate-800 transition-all active:scale-95 decoration-transparent block text-center text-sm">
                        Login Sekarang
                    </a>
                </div>
            @endauth
        </div>

    </main>

</body>
</html>
