<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50">
    <x-navbar />
    <div class="flex-grow flex items-center justify-center p-4">
    <div class="max-w-sm w-full bg-white p-8 rounded-3xl shadow-sm border border-slate-100 text-center">
        <h2 class="text-2xl font-black text-slate-800 mb-6 text-center">Daftar Akun Baru</h2>
        @if ($errors->any())
            <div class="bg-red-50 text-red-500 p-4 rounded-xl mb-4 text-sm font-bold">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Email</label>
                <input type="email" name="email" required class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500">
            </div>
            <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl hover:bg-emerald-700 transition-all">Daftar</button>
        </form>
        
        <p class="mt-4 text-center text-sm text-slate-500">Sudah punya akun? <a href="{{ route('login') }}" class="text-emerald-600 font-bold decoration-transparent">Login</a></p>
    </div>
</body>
</html>