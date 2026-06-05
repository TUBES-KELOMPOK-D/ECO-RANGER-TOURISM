<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Eco Ranger Tourism – Platform GIS Lingkungan Interaktif untuk memantau, melaporkan, dan menjaga kelestarian alam Indonesia." />
    <meta name="keywords" content="eco ranger, lingkungan, GIS, peta interaktif, laporan lingkungan, eco tourism Indonesia" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Eco Ranger Tourism – Pantau Alam, Jaga Bumi</title>

    <!-- Preconnect for Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <!-- Google Fonts: Plus Jakarta Sans + Space Grotesk -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet" />

    <!-- Leaflet CSS (must come before React bundle) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Vite React bundle -->
    @vite(['resources/js/eco-ranger/main.tsx'])

    <!-- Inject Laravel Data for React -->
    <script>
        window.appData = {
            markers: @json($markers),
            user: @json(auth()->user()),
            isAdmin: {{ auth()->check() && auth()->user()?->role === 'admin' ? 'true' : 'false' }},
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
</head>
<body style="margin:0;padding:0;background:#F8FAF9;">
    <div id="eco-ranger-root"></div>
</body>
</html>