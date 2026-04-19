<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Eco-Reporter - GreenTour</title>
    <!-- Tailwind via Vite -->
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="bg-gray-50 min-h-screen text-gray-800 antialiased font-sans">
    
    <header class="bg-white sticky top-0 z-50 border-b border-gray-100 px-6 py-4 flex items-center justify-between">
        {{-- Logo kiri --}}
        <div class="flex items-center gap-2">
            <div class="w-10 h-10 bg-green-600 rounded-xl flex justify-center items-center font-bold text-white shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
            </div>
            <span class="text-xl font-bold tracking-tight text-gray-900">Eco <span class="text-green-600">Ranger Tourism</span></span>
        </div>

        {{-- Nav + tombol + avatar semua di kanan --}}
        <div class="hidden md:flex items-center gap-6">
            <nav class="flex items-center gap-6 text-sm font-semibold text-gray-500">
                <a href="#" class="hover:text-green-600">Beranda</a>
                <a href="#" class="hover:text-green-600">Aksi</a>
                <a href="#" class="hover:text-green-600">Peringkat</a>
                <a href="#" class="hover:text-green-600">Akademi</a>
            </nav>
            <div class="h-5 w-px bg-gray-200"></div>
            <a href="/pelaporan" class="bg-green-600 hover:bg-green-700 text-white font-medium text-sm px-5 py-2.5 rounded-full flex gap-2 items-center shadow-sm transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                Lapor Isu
            </a>
            <div class="w-10 h-10 border-2 border-green-500 text-green-600 font-bold bg-green-50 rounded-full flex items-center justify-center text-sm">
                AS
            </div>
        </div>
    </header>

    <!-- REACT MOUNT ROOT -->
    <main class="w-full pb-20 pt-6">
        <div id="eco-reporter-root"></div>
    </main>
    
</body>
</html>
