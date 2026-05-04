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

        /* Review Section Styles */
        .review-summary-card {
            background: linear-gradient(135deg, #f0f9ff 0%, #f8fafc 50%, #f0fdfa 100%);
            border: 1px solid #e0f2fe;
            border-radius: 20px;
            padding: 24px;
        }
        .review-form-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.04);
        }
        .review-textarea {
            width: 100%;
            padding: 14px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            font-family: inherit;
            font-size: 14px;
            color: #1e293b;
            background: #f8fafc;
            resize: vertical;
            transition: all 0.2s ease;
            outline: none;
        }
        .review-textarea:focus {
            border-color: #0ea5e9;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        }
        .review-textarea::placeholder { color: #94a3b8; }
        .star-rating-input {
            display: flex;
            gap: 4px;
        }
        .star-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 2px;
            transition: transform 0.15s ease;
            line-height: 0;
        }
        .star-btn:hover { transform: scale(1.2); }
        .star-btn.active svg {
            fill: #fbbf24;
            stroke: #f59e0b;
        }
        .review-submit-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 14px 20px;
            border-radius: 14px;
            background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);
            color: white;
            font-weight: 700;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .review-submit-btn:hover {
            background: linear-gradient(135deg, #0284c7 0%, #0891b2 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(14, 165, 233, 0.25);
        }
        .login-review-btn {
            display: inline-block;
            padding: 12px 28px;
            border-radius: 12px;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: white;
            font-weight: 700;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.25s ease;
        }
        .login-review-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.2);
        }
        .review-item {
            padding: 18px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .review-item:last-child { border-bottom: none; }
        .review-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e0f2fe, #dbeafe);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 800;
            color: #0369a1;
            flex-shrink: 0;
            overflow: hidden;
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
        @php
            $backUrl = request('from') === 'academy' ? route('academy.index') : url('/');
        @endphp
        <div style="position:absolute; top:0; left:0; right:0; padding: 20px 20px; display:flex; justify-content:space-between; align-items:center; z-index:10;">
            <a href="{{ $backUrl }}" class="circle-btn" id="btn-back" title="Kembali ke Peta">
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

        <!-- ============================================================ -->
        <!-- Review & Ulasan Pengguna (Standalone — tidak terkait laporan) -->
        <!-- ============================================================ -->
        <div class="fade-up fade-up-delay-3" style="margin-top:28px;" id="section-reviews">
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px;">
                <div style="width:40px; height:40px; background:linear-gradient(135deg, #0ea5e9, #06b6d4); border-radius:12px; display:flex; align-items:center; justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                </div>
                <h2 style="font-size:22px; font-weight:800; color:#0f172a;">Review & Ulasan</h2>
            </div>

            <!-- Rating Summary Card -->
            <div class="review-summary-card">
                <div style="display:flex; gap:28px; align-items:center; flex-wrap:wrap;">
                    <!-- Big Rating Number -->
                    <div style="text-align:center; min-width:100px;">
                        <div style="font-size:52px; font-weight:900; color:#0f172a; line-height:1;">{{ $averageRating > 0 ? number_format($averageRating, 1) : '—' }}</div>
                        <div style="display:flex; gap:3px; justify-content:center; margin-top:6px;">
                            @for($i = 1; $i <= 5; $i++)
                                @if($averageRating >= $i)
                                    <span class="star">★</span>
                                @elseif($averageRating >= $i - 0.5)
                                    <span class="star">★</span>
                                @else
                                    <span class="star-empty">★</span>
                                @endif
                            @endfor
                        </div>
                        <div style="font-size:12px; color:#64748b; font-weight:500; margin-top:4px;">{{ $totalReviews }} ulasan</div>
                    </div>

                    <!-- Star Distribution Bars -->
                    <div style="flex:1; min-width:200px;">
                        @for($i = 5; $i >= 1; $i--)
                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
                            <span style="font-size:12px; font-weight:700; color:#64748b; width:14px; text-align:right;">{{ $i }}</span>
                            <span class="star" style="font-size:12px;">★</span>
                            <div style="flex:1; height:8px; background:#e2e8f0; border-radius:999px; overflow:hidden;">
                                <div style="height:100%; background: linear-gradient(90deg, #fbbf24, #f59e0b); border-radius:999px; transition: width 0.6s ease; width: {{ $totalReviews > 0 ? round(($starDistribution[$i] / $totalReviews) * 100) : 0 }}%;"></div>
                            </div>
                            <span style="font-size:11px; color:#94a3b8; font-weight:600; width:24px; text-align:right;">{{ $starDistribution[$i] ?? 0 }}</span>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Review Form (only for logged-in users) -->
            @auth
                @if(!$userHasReviewed)
                <div class="review-form-card" style="margin-top:20px;" id="review-form-section">
                    <h3 style="font-size:16px; font-weight:800; color:#0f172a; margin-bottom:14px;">Tulis Ulasanmu</h3>

                    @if(session('review_error'))
                    <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:12px 16px; margin-bottom:14px; display:flex; align-items:center; gap:8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                        <span style="font-size:13px; color:#dc2626; font-weight:600;">{{ session('review_error') }}</span>
                    </div>
                    @endif

                    <form action="{{ route('markers.reviews.store', $marker->id) }}" method="POST" id="review-form">
                        @csrf

                        <!-- Star Rating Input -->
                        <div style="margin-bottom:16px;">
                            <label style="font-size:13px; font-weight:700; color:#475569; display:block; margin-bottom:8px;">Rating</label>
                            <div class="star-rating-input" id="star-rating-input">
                                @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="star-btn" data-rating="{{ $i }}" onclick="setRating({{ $i }})" title="{{ $i }} bintang">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                    </svg>
                                </button>
                                @endfor
                                <input type="hidden" name="rating" id="rating-value" value="{{ old('rating', '') }}">
                            </div>
                            @error('rating')
                            <p style="font-size:12px; color:#ef4444; margin-top:4px; font-weight:600;">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Review Text -->
                        <div style="margin-bottom:16px;">
                            <label style="font-size:13px; font-weight:700; color:#475569; display:block; margin-bottom:8px;">Komentar</label>
                            <textarea
                                name="review_text"
                                id="review-text"
                                rows="3"
                                maxlength="500"
                                placeholder="Ceritakan pengalamanmu mengunjungi lokasi ini..."
                                class="review-textarea"
                            >{{ old('review_text') }}</textarea>
                            <div style="display:flex; justify-content:space-between; margin-top:4px;">
                                @error('review_text')
                                <p style="font-size:12px; color:#ef4444; font-weight:600;">{{ $message }}</p>
                                @else
                                <span></span>
                                @enderror
                                <span id="char-count" style="font-size:11px; color:#94a3b8; font-weight:500;">0/500</span>
                            </div>
                        </div>

                        <button type="submit" class="review-submit-btn" id="btn-submit-review">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>
                            </svg>
                            Kirim Review
                        </button>
                    </form>
                </div>
                @else
                <div style="margin-top:20px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:16px; padding:16px 20px; display:flex; align-items:center; gap:10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    <span style="font-size:13px; color:#15803d; font-weight:600;">Kamu sudah memberikan review untuk lokasi ini.</span>
                </div>
                @endif
            @else
                <div style="margin-top:20px; background:#f8fafc; border:1.5px dashed #cbd5e1; border-radius:16px; padding:24px; text-align:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 10px;">
                        <circle cx="12" cy="12" r="10"/><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <p style="font-size:14px; color:#64748b; font-weight:600; margin-bottom:12px;">Login untuk menulis review dan berbagi pengalamanmu</p>
                    <a href="{{ route('login') }}" class="login-review-btn">
                        Login untuk Menulis Review
                    </a>
                </div>
            @endauth

            @if(session('review_success'))
            <div style="margin-top:16px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; padding:12px 16px; display:flex; align-items:center; gap:8px;" id="review-success-alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                <span style="font-size:13px; color:#15803d; font-weight:600;">{{ session('review_success') }}</span>
            </div>
            @endif

            <!-- Review List (visible to everyone) -->
            <div style="margin-top:24px;" id="review-list">
                @forelse($reviews as $review)
                <div class="review-item" id="review-{{ $review->id }}">
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:10px;">
                        <!-- Avatar -->
                        <div class="review-avatar">
                            @if($review->user && $review->user->photo)
                                <img src="{{ asset('storage/' . $review->user->photo) }}" alt="{{ $review->user->name }}" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                            @else
                                {{ $review->user ? strtoupper(substr($review->user->name, 0, 2)) : '??' }}
                            @endif
                        </div>
                        <div style="flex:1;">
                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                <span style="font-size:14px; font-weight:700; color:#0f172a;">{{ $review->user->name ?? 'Pengguna' }}</span>
                                <div style="display:flex; gap:1px;">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <span style="color:#fbbf24; font-size:12px;">★</span>
                                        @else
                                            <span style="color:#e2e8f0; font-size:12px;">★</span>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            <span style="font-size:11px; color:#94a3b8; font-weight:500;">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <p style="font-size:14px; line-height:1.7; color:#475569; margin:0; padding-left:52px;">{{ $review->review_text }}</p>
                </div>
                @empty
                <div style="text-align:center; padding:32px 16px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px;">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                    <p style="font-size:14px; color:#94a3b8; font-weight:600;">Belum ada review</p>
                    <p style="font-size:12px; color:#cbd5e1; font-weight:500; margin-top:4px;">Jadilah yang pertama memberikan ulasan!</p>
                </div>
                @endforelse
            </div>
        </div>

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

        // ===== Review & Rating Functions =====
        function setRating(rating) {
            document.getElementById('rating-value').value = rating;
            const buttons = document.querySelectorAll('.star-btn');
            buttons.forEach((btn, index) => {
                if (index < rating) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        // Character counter for review text
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('review-text');
            const charCount = document.getElementById('char-count');
            if (textarea && charCount) {
                const updateCount = () => {
                    charCount.textContent = textarea.value.length + '/500';
                };
                textarea.addEventListener('input', updateCount);
                updateCount(); // initial count
            }

            // Restore star rating from old input (validation errors)
            const oldRating = document.getElementById('rating-value');
            if (oldRating && oldRating.value) {
                setRating(parseInt(oldRating.value));
            }

            // Auto-dismiss success alert after 5 seconds
            const successAlert = document.getElementById('review-success-alert');
            if (successAlert) {
                setTimeout(() => {
                    successAlert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    successAlert.style.opacity = '0';
                    successAlert.style.transform = 'translateY(-8px)';
                    setTimeout(() => successAlert.remove(), 500);
                }, 5000);
            }
        });
    </script>
</body>
</html>
