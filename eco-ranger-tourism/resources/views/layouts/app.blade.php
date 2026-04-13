<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Eco Ranger Tourism')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'Poppins', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        toscagreen: '#2d6a4f',
                    },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', 'Poppins', ui-sans-serif, system-ui, sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
        }
        .shadow-soft {
            box-shadow: 0 18px 50px rgba(15, 23, 42, 0.08);
        }
        .ios-switch {
            width: 3.5rem;
            height: 1.75rem;
            border-radius: 9999px;
            background: #d1d5db;
            position: relative;
            transition: background-color 0.25s ease;
        }
        .ios-switch::after {
            content: '';
            position: absolute;
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 9999px;
            background: white;
            top: 0.125rem;
            left: 0.125rem;
            transition: transform 0.25s ease;
            box-shadow: 0 6px 12px rgba(15, 23, 42, 0.12);
        }
        input[type='checkbox']:checked + .ios-switch {
            background: #2d6a4f;
        }
        input[type='checkbox']:checked + .ios-switch::after {
            transform: translateX(1.75rem);
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen">
    @unless(request()->routeIs('profile.settings'))
        <x-navbar />
    @endunless
    <main class="min-h-screen {{ request()->routeIs('profile.settings') ? 'pt-0' : 'pt-28' }}">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
