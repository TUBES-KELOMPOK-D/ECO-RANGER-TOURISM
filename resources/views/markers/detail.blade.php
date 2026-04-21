<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Detail informasi destinasi {{ $marker->location_name ?? $marker->title }} - Eco Ranger Tourism">
    <title>{{ $marker->location_name ?? $marker->title ?? 'Detail Lokasi' }} — Eco Ranger Tourism</title>

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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', 'Poppins', ui-sans-serif, system-ui, sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
            -webkit-font-smoothing: antialiased;
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            width: 100%;
            height: 420px;
            overflow: hidden;
        }
        .hero-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.15) 50%, rgba(0,0,0,0.05) 100%);
        }
        .hero-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 2rem 1.5rem;
        }

        /* Category Badge */
        .badge-category {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase;
        }
        .badge-green { background-color: #10b981; color: white; }
        .badge-yellow { background-color: #f59e0b; color: white; }
        .badge-red { background-color: #ef4444; color: white; }

        /* Circle Button */
        .circle-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(30, 41, 59, 0.55);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            color: white;
        }
        .circle-btn:hover {
            background: rgba(30, 41, 59, 0.8);
            transform: scale(1.08);
        }

        /* Eco Score Card */
        .eco-score-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #f0fdf9 100%);
            border: 1px solid #d1fae5;
            border-radius: 20px;
            padding: 24px;
        }

        /* Indicator Card */
        .indicator-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px 16px;
            text-align: center;
            transition: all 0.2s ease;
        }
        .indicator-card:hover {
            border-color: #10b981;
            background: #f0fdf4;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.1);
        }

        /* Rules Card */
        .rules-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #f8fafc 100%);
            border: 1.5px solid #d1fae5;
            border-radius: 20px;
            padding: 28px;
        }

        /* CTA Button */
        .cta-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 18px 24px;
            border-radius: 16px;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            font-weight: 700;
            font-size: 15px;
            border: none;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
        }
        .cta-btn:hover {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(15, 23, 42, 0.25);
        }

        /* Rule list item */
        .rule-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
        }
        .rule-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 12px;
            font-weight: 700;
            margin-top: 1px;
        }
        .rule-icon-allowed { background: #d1fae5; color: #059669; }
        .rule-icon-warning { background: #fef3c7; color: #d97706; }

        /* Weather tag */
        .weather-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 9999px;
            background: rgba(255,255,255,0.18);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            font-size: 13px;
            font-weight: 600;
            color: white;
        }

        /* Stars */
        .star { color: #fbbf24; font-size: 14px; }
        .star-empty { color: #d1d5db; font-size: 14px; }

        /* Scroll animation */
        .fade-up {
            opacity: 0;
            transform: translateY(24px);
            animation: fadeUp 0.6s ease forwards;
        }
        .fade-up-delay-1 { animation-delay: 0.1s; }
        .fade-up-delay-2 { animation-delay: 0.2s; }
        .fade-up-delay-3 { animation-delay: 0.3s; }
        .fade-up-delay-4 { animation-delay: 0.4s; }

        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }

        /* Eco icon pulse */
        .eco-pulse {
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.08); }
        }

        /* Default placeholder image */
        .placeholder-hero {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #065f46 0%, #10b981 50%, #34d399 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <x-navbar />

    <!-- Hero Section -->
    <div class="hero-section" id="hero-section">
        @if($marker->image_path)
            <img src="{{ asset('storage/' . $marker->image_path) }}" alt="{{ $marker->location_name ?? $marker->title }}" loading="eager">
        @else
            <div class="placeholder-hero">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
                    <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 12"/>
                </svg>
            </div>
        @endif
        <div class="hero-overlay"></div>

        <!-- Top Navigation -->
        <div style="position:absolute; top:0; left:0; right:0; padding: 20px 20px; display:flex; justify-content:space-between; align-items:center; z-index:10;">
            <a href="/" class="circle-btn" id="btn-back" title="Kembali ke Peta">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m15 18-6-6 6-6"/>
                </svg>
            </a>
            <div style="display:flex; gap:10px;">
                <button class="circle-btn" id="btn-favorite" title="Favorit" onclick="toggleFavorite()">
                    <svg id="heart-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                    </svg>
                </button>
                <button class="circle-btn" id="btn-share" title="Bagikan" onclick="shareLocation()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" x2="12" y1="2" y2="15"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Hero Content -->
        <div class="hero-content">
            @php
                $categoryLabel = $marker->category ?? 'Terjaga';
                $badgeClass = match($marker->status) {
                    'red' => 'badge-red',
                    'yellow' => 'badge-yellow',
                    default => 'badge-green',
                };
            @endphp
            <span class="badge-category {{ $badgeClass }}">{{ strtoupper($categoryLabel) }}</span>
            <h1 style="font-size: 28px; font-weight: 900; color: white; margin-top: 10px; line-height: 1.2; text-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                {{ $marker->location_name ?? $marker->title ?? 'Lokasi Wisata' }}
            </h1>
            <div style="display:flex; flex-wrap:wrap; align-items:center; gap:12px; margin-top:12px;">
                @if($lat && $lng)
                <div class="weather-tag">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                    {{ number_format($lat, 2) }}, {{ number_format($lng, 2) }}
                </div>
                @endif
                @if($weather)
                <div class="weather-tag">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.5 19H9a7 7 0 1 1 6.71-9h1.79a4.5 4.5 0 1 1 0 9Z"/></svg>
                    {{ $weather['temperature'] ?? '-' }}°C &nbsp;{{ $weatherDescription }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div style="max-width: 960px; margin: 0 auto; padding: 0 20px 40px;">

        <!-- Eco Health Score Card -->
        <div class="eco-score-card fade-up fade-up-delay-1" style="margin-top: -30px; position: relative; z-index: 5;">
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <p style="font-size:11px; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; color:#059669; margin-bottom:8px;">ECO-HEALTH SCORE</p>
                    <div style="display:flex; align-items:baseline; gap:12px;">
                        <span style="font-size:48px; font-weight:900; color:#065f46; line-height:1;">
                            {{ $marker->eco_health_score ? number_format($marker->eco_health_score, 1) : '—' }}
                        </span>
                        <div style="display:flex; flex-direction:column; gap:4px;">
                            @if($marker->eco_health_score)
                            <div style="display:flex; gap:2px;">
                                @php $fullStars = floor($marker->eco_health_score / 2); $halfStar = ($marker->eco_health_score / 2) - $fullStars >= 0.5; @endphp
                                @for($i = 0; $i < $fullStars; $i++)
                                    <span class="star">★</span>
                                @endfor
                                @if($halfStar)
                                    <span class="star">★</span>
                                @endif
                                @for($i = $fullStars + ($halfStar ? 1 : 0); $i < 5; $i++)
                                    <span class="star-empty">★</span>
                                @endfor
                            </div>
                            @endif
                            <span style="font-size:12px; color:#64748b; font-weight:500;">
                                Berdasarkan {{ number_format($marker->total_reports) > 0 ? (($marker->total_reports >= 1000) ? number_format($marker->total_reports / 1000, 1) . 'k' : $marker->total_reports) : '0' }} Laporan
                            </span>
                        </div>
                    </div>
                </div>
                <div class="eco-pulse" style="width:48px; height:48px; background: linear-gradient(135deg, #d1fae5, #a7f3d0); border-radius:14px; display:flex; align-items:center; justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
                        <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 12"/>
                    </svg>
                </div>
            </div>

            <!-- Indicator Grid -->
            <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:12px; margin-top:20px;">
                <div class="indicator-card">
                    <p style="font-size:10px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#10b981; margin-bottom:6px;">KEBERSIHAN</p>
                    <p style="font-size:18px; font-weight:800; color:#1e293b;">{{ $marker->kebersihan ?? '—' }}</p>
                </div>
                <div class="indicator-card">
                    <p style="font-size:10px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#10b981; margin-bottom:6px;">AKSES</p>
                    <p style="font-size:18px; font-weight:800; color:#1e293b;">{{ $marker->akses ?? '—' }}</p>
                </div>
                <div class="indicator-card">
                    <p style="font-size:10px; font-weight:700; letter-spacing:1.2px; text-transform:uppercase; color:#10b981; margin-bottom:6px;">POPULASI</p>
                    <p style="font-size:18px; font-weight:800; color:#1e293b;">{{ $marker->populasi ?? '—' }}</p>
                </div>
            </div>
        </div>

        <!-- Tentang Lokasi -->
        <div class="fade-up fade-up-delay-2" style="margin-top:28px;">
            <h2 style="font-size:22px; font-weight:800; color:#0f172a; margin-bottom:12px;">Tentang Lokasi</h2>
            <p style="font-size:15px; line-height:1.75; color:#475569;">
                {{ $marker->description ?? 'Belum ada deskripsi untuk lokasi ini.' }}
            </p>
        </div>

        <!-- Aturan Wisata Hijau -->
        @if($marker->eco_rules && count($marker->eco_rules) > 0)
        <div class="rules-card fade-up fade-up-delay-3" style="margin-top:28px;">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:18px;">
                <div style="width:40px; height:40px; background:linear-gradient(135deg, #059669, #10b981); border-radius:12px; display:flex; align-items:center; justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                </div>
                <h3 style="font-size:18px; font-weight:800; color:#0f172a;">Aturan Wisata Hijau</h3>
            </div>

            <div style="display:flex; flex-direction:column; gap:4px;">
                @foreach($marker->eco_rules as $rule)
                <div class="rule-item">
                    @if(($rule['type'] ?? 'allowed') === 'warning')
                        <div class="rule-icon rule-icon-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/>
                            </svg>
                        </div>
                        <span style="font-size:14px; font-weight:700; color:#dc2626;">{{ $rule['text'] }}</span>
                    @else
                        <div class="rule-icon rule-icon-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </div>
                        <span style="font-size:14px; font-weight:600; color:#1e293b;">{{ $rule['text'] }}</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- CTA Button -->
        <div class="fade-up fade-up-delay-4" style="margin-top:32px; padding-bottom:20px;">
            <a href="/pelaporan" class="cta-btn" id="btn-report">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/>
                </svg>
                Laporkan Kondisi Alam
            </a>
        </div>
    </div>

    <script>
        // Favorite toggle
        function toggleFavorite() {
            const heart = document.getElementById('heart-icon');
            const isFilled = heart.getAttribute('fill') === 'currentColor';
            if (isFilled) {
                heart.setAttribute('fill', 'none');
                heart.style.color = 'white';
            } else {
                heart.setAttribute('fill', 'currentColor');
                heart.style.color = '#f43f5e';
            }
        }

        // Share
        function shareLocation() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $marker->location_name ?? $marker->title ?? "Lokasi Wisata" }}',
                    text: 'Lihat destinasi eco-tourism ini di Eco Ranger Tourism!',
                    url: window.location.href
                }).catch(() => {});
            } else {
                // Fallback: copy URL
                navigator.clipboard.writeText(window.location.href).then(() => {
                    const btn = document.getElementById('btn-share');
                    btn.style.background = 'rgba(16, 185, 129, 0.7)';
                    setTimeout(() => {
                        btn.style.background = 'rgba(30, 41, 59, 0.55)';
                    }, 1500);
                });
            }
        }
    </script>
</body>
</html>
