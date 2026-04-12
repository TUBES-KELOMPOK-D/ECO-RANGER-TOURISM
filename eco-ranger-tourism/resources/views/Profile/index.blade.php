<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - GreenTour</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50">
    <x-navbar />
    <div class="flex-grow flex items-center justify-center p-4">
    <div class="max-w-sm w-full bg-white p-8 rounded-3xl shadow-sm border border-slate-100 text-center">
        <div class="w-20 h-20 mx-auto bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center text-2xl font-bold border-2 border-emerald-500 mb-4">
            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
        </div>
        
        <h2 class="text-xl font-black text-slate-800 mb-1">{{ auth()->user()->name }}</h2>
        <p class="text-sm text-slate-500 mb-8">{{ auth()->user()->email }}</p>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-red-500 text-white font-bold py-3 rounded-xl hover:bg-red-600 transition-all">
                Keluar Akun (Logout)
            </button>
        </form>

        <div class="mt-6">
            <a href="/" class="text-sm text-emerald-600 font-bold hover:underline">Kembali ke Peta</a>
        </div>
    </div>
</body>
</html>