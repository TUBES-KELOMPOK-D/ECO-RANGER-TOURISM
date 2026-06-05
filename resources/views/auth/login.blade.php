<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Masuk ke akun Eco Ranger Tourism dan mulai berkontribusi untuk lingkungan.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — Eco Ranger Tourism</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    
    @viteReactRefresh
    @vite(['resources/js/eco-ranger/main.tsx'])

    @php
        $usersCount = \App\Models\User::count();
        $markersCount = \App\Models\Marker::count();
    @endphp

    <script>
        window.appData = {
            user: @json(auth()->user()),
            isAdmin: {{ auth()->check() && auth()->user()?->role === 'admin' ? 'true' : 'false' }},
            usersCount: {{ $usersCount }},
            markersCount: {{ $markersCount }},
            csrfToken: '{{ csrf_token() }}',
            loginRoute: @json(route('login')),
            registerRoute: @json(route('register')),
            error: @json($errors->first() ?? ''),
            oldEmail: @json(old('email') ?? '')
        };
    </script>
</head>
<body style="margin:0;padding:0;background:#F8FAF9;">
    <div id="login-root"></div>
</body>
</html>