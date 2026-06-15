@extends('layouts.app')

@section('content')
<style>
/* ===== LEADERBOARD MODERN STYLES ===== */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

.lb-page { background: #f1f5f9; min-height: 100vh; font-family: 'Inter', sans-serif; }

/* ===== HEADER ===== */
.lb-header {
    background: linear-gradient(135deg, #065f46 0%, #047857 50%, #059669 100%);
    position: relative;
    overflow: hidden;
    padding: 4rem 1.5rem 6rem;
}
.lb-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 60%;
    height: 200%;
    background: radial-gradient(ellipse, rgba(255,255,255,0.06) 0%, transparent 65%);
    pointer-events: none;
}
/* ===== PODIUM (TOP 3 CARD) ===== */
.lb-top3-card {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.8);
    border-radius: 1.5rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    max-width: 800px;
    margin: -2.5rem auto 0;
    position: relative;
    z-index: 20;
    display: flex;
    align-items: stretch;
    padding: 1.5rem;
}
.top3-col {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    border-right: 1.5px dashed #cbd5e1;
    cursor: pointer;
    transition: transform 0.2s;
}
.top3-col:last-child {
    border-right: none;
}
.top3-col:hover {
    transform: translateY(-4px);
}
.podium-avatar {
    border-radius: 1rem;
    display: flex; align-items: center; justify-content: center;
    font-weight: 900;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    position: relative;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    border: 3px solid rgba(255,255,255,0.8);
    overflow: hidden;
}
.podium-avatar.rank1 {
    width: 72px; height: 72px; font-size: 1.25rem;
    background: linear-gradient(135deg, #fde68a, #f59e0b, #d97706);
    box-shadow: 0 0 30px rgba(245,158,11,0.4), 0 8px 24px rgba(0,0,0,0.15);
    border-color: #fef3c7;
}
.podium-avatar.rank2 {
    width: 60px; height: 60px; font-size: 1.1rem;
    background: linear-gradient(135deg, #e2e8f0, #94a3b8);
}
.podium-avatar.rank3 {
    width: 56px; height: 56px; font-size: 1rem;
    background: linear-gradient(135deg, #fed7aa, #f97316, #ea580c);
}
.crown-icon { position: absolute; top: -26px; left: 50%; transform: translateX(-50%); font-size: 1.6rem; z-index: 10; }
.top3-rank-badge {
    margin-top: -12px; z-index: 5;
    background: white; border-radius: 999px;
    padding: 2px 12px; font-weight: 900; font-size: 0.8rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.top3-rank-badge.r1 { color: #b45309; border: 2px solid #fde68a; }
.top3-rank-badge.r2 { color: #475569; border: 2px solid #e2e8f0; }
.top3-rank-badge.r3 { color: #c2410c; border: 2px solid #fed7aa; }

/* ===== CARDS ===== */
.lb-card {
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.7);
    border-radius: 1.25rem;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06), 0 1px 3px rgba(0,0,0,0.04);
    transition: box-shadow 0.2s;
}
.lb-card:hover { box-shadow: 0 8px 32px rgba(0,0,0,0.08); }

.lb-section-label {
    font-size: 0.65rem;
    font-weight: 800;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: #94a3b8;
}
.lb-section-title {
    font-size: 1rem;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: 0.02em;
    text-transform: uppercase;
}

/* ===== REWARD CARDS ===== */
.reward-card {
    border-radius: 1rem;
    padding: 1.25rem;
    position: relative; overflow: hidden;
    border: 1.5px solid;
    transition: transform 0.2s, box-shadow 0.2s;
}
.reward-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
.reward-card.gold { background: linear-gradient(135deg, #fffbeb, #fef3c7); border-color: #fbbf24; }
.reward-card.silver { background: linear-gradient(135deg, #f8fafc, #f1f5f9); border-color: #94a3b8; }
.reward-card.bronze { background: linear-gradient(135deg, #fff7ed, #ffedd5); border-color: #fb923c; }
.reward-rank-badge {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px; border-radius: 50%;
    font-size: 1rem; font-weight: 900; margin-bottom: 0.75rem;
    flex-shrink: 0;
}
.reward-rank-badge.gold  { background: #fef3c7; color: #b45309; }
.reward-rank-badge.silver { background: #e2e8f0; color: #475569; }
.reward-rank-badge.bronze { background: #ffedd5; color: #c2410c; }

/* ===== LEADERBOARD TABLE (LIST STYLE) ===== */
.lb-row {
    display: flex; align-items: center; gap: 0.75rem;
    padding: 0.85rem 1.25rem;
    border-radius: 0.875rem;
    background: white;
    border: 1.5px solid #f1f5f9;
    transition: all 0.2s;
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}
.lb-row:hover { border-color: #a7f3d0; background: #f0fdf4; box-shadow: 0 4px 16px rgba(5,150,105,0.1); transform: translateX(4px); }
.lb-row.rank-1 { border-color: #fbbf24; background: linear-gradient(135deg, #fffbeb, #ffffff); box-shadow: 0 2px 12px rgba(251,191,36,0.15); }
.lb-row.rank-2 { border-color: #94a3b8; background: linear-gradient(135deg, #f8fafc, #ffffff); }
.lb-row.rank-3 { border-color: #fb923c; background: linear-gradient(135deg, #fff7ed, #ffffff); box-shadow: 0 2px 12px rgba(251,146,60,0.1); }
.lb-rank-badge {
    font-weight: 900; font-size: 0.7rem;
    padding: 4px 10px; border-radius: 999px;
    flex-shrink: 0; letter-spacing: 0.05em;
    min-width: 48px; text-align: center;
}
.lb-rank-badge.r1 { background: #fef3c7; color: #92400e; border: 1px solid #fbbf24; }
.lb-rank-badge.r2 { background: #e2e8f0; color: #334155; border: 1px solid #94a3b8; }
.lb-rank-badge.r3 { background: #ffedd5; color: #9a3412; border: 1px solid #fb923c; }
.lb-rank-badge.rn { background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
.lb-avatar {
    width: 40px; height: 40px; border-radius: 0.75rem;
    flex-shrink: 0; overflow: hidden;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 0.8rem;
    background: linear-gradient(135deg, #d1fae5, #6ee7b7); color: #065f46;
    border: 2px solid rgba(255,255,255,0.8);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.lb-pts-pill {
    display: inline-flex; align-items: center; gap: 4px;
    background: #f0fdf4; border: 1px solid #a7f3d0;
    padding: 4px 12px; border-radius: 999px;
    font-weight: 800; font-size: 0.75rem; color: #059669;
    flex-shrink: 0;
}
.lb-level-pill {
    font-size: 0.65rem; font-weight: 700;
    padding: 3px 9px; border-radius: 999px;
    flex-shrink: 0; letter-spacing: 0.04em;
}

/* ===== POINT RULES ===== */
.rule-card {
    display: flex; flex-direction: column; align-items: center;
    padding: 1.25rem 1rem;
    background: white; border: 1.5px solid #f1f5f9;
    border-radius: 1rem;
    transition: all 0.2s;
    text-align: center;
}
.rule-card:hover { border-color: #6ee7b7; box-shadow: 0 4px 20px rgba(5,150,105,0.1); transform: translateY(-3px); }
.rule-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: #f0fdf4; color: #059669; margin-bottom: 0.75rem; transition: transform 0.2s; }
.rule-card:hover .rule-icon { transform: scale(1.15) rotate(-5deg); }

/* ===== BADGE CARDS ===== */
.badge-card {
    background: linear-gradient(160deg, #0f172a, #1e293b);
    border-radius: 1.25rem;
    padding: 1.25rem;
    border: 1.5px solid #334155;
    transition: all 0.25s;
    position: relative; overflow: hidden;
    height: 180px; display: flex; flex-direction: column; justify-content: space-between;
}
.badge-card::before {
    content: '';
    position: absolute; top: -30%; right: -20%;
    width: 120px; height: 120px;
    background: radial-gradient(circle, rgba(52,211,153,0.12), transparent 70%);
    border-radius: 50%;
    transition: opacity 0.3s;
}
.badge-card:hover { border-color: #34d399; box-shadow: 0 8px 30px rgba(52,211,153,0.15); transform: translateY(-4px); }
.badge-card:hover::before { opacity: 2; }
.badge-progress { height: 5px; background: #1e293b; border-radius: 99px; overflow: hidden; border: 1px solid #334155; }
.badge-progress-bar { height: 100%; border-radius: 99px; transition: width 1s ease; background: linear-gradient(90deg, #059669, #34d399); }
.badge-progress-bar.complete { background: linear-gradient(90deg, #34d399, #6ee7b7); box-shadow: 0 0 8px rgba(52,211,153,0.4); }

/* ===== SEARCH ===== */
.lb-search-input {
    flex: 1; padding: 0.85rem 1.25rem;
    border: 1.5px solid #e2e8f0; border-radius: 999px;
    font-weight: 600; color: #0f172a; background: white;
    outline: none; transition: all 0.2s; font-size: 0.9rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}
.lb-search-input:focus { border-color: #059669; box-shadow: 0 0 0 3px rgba(5,150,105,0.1); }
.lb-search-btn {
    padding: 0.85rem 1.5rem; background: linear-gradient(135deg, #059669, #047857);
    color: white; border-radius: 999px; font-weight: 700; font-size: 0.9rem;
    display: flex; align-items: center; gap: 6px;
    transition: all 0.2s; border: none; cursor: pointer;
    box-shadow: 0 4px 14px rgba(5,150,105,0.3);
}
.lb-search-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(5,150,105,0.4); }

/* ===== PAGINATION ===== */
.pagination { display: flex; gap: 6px; justify-content: center; flex-wrap: wrap; }
.pagination span, .pagination a {
    padding: 6px 14px; border-radius: 10px;
    font-weight: 700; font-size: 0.8rem;
    border: 1.5px solid #e2e8f0; background: white; color: #475569;
    text-decoration: none; transition: all 0.15s;
    display: inline-flex; align-items: center;
}
.pagination span.active, .pagination [aria-current="page"] > span {
    background: linear-gradient(135deg, #059669, #047857);
    color: white; border-color: transparent;
    box-shadow: 0 3px 10px rgba(5,150,105,0.3);
}
.pagination a:hover { border-color: #059669; color: #059669; }

/* ===== USER POSITION BANNER ===== */
.user-pos-banner {
    background: linear-gradient(135deg, #065f46 0%, #047857 50%, #059669 100%);
    border-radius: 1.5rem;
    padding: 2rem;
    position: relative; overflow: hidden;
    text-align: center;
    box-shadow: 0 12px 40px rgba(5,150,105,0.3);
}
.user-pos-banner::before {
    content: '';
    position: absolute; top: -40%; left: -10%;
    width: 70%; height: 180%;
    background: radial-gradient(ellipse, rgba(255,255,255,0.07) 0%, transparent 60%);
    pointer-events: none;
}
.user-pos-dots {
    position: absolute; inset: 0;
    background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.05) 1px, transparent 0);
    background-size: 24px 24px;
}

/* ===== RESPONSIVE MEDIA QUERIES ===== */
@media (max-width: 768px) {
    .lb-top3-card {
        padding: 1rem 0.5rem;
        gap: 0.25rem;
        border-radius: 1rem;
    }
    .top3-col {
        padding: 0.5rem 0.25rem;
        min-width: 0; /* Mencegah overflow pada text truncate */
    }
    .podium-avatar.rank1 { width: 56px; height: 56px; font-size: 1.1rem; border-width: 2px; }
    .podium-avatar.rank2 { width: 48px; height: 48px; font-size: 0.95rem; border-width: 2px; }
    .podium-avatar.rank3 { width: 48px; height: 48px; font-size: 0.95rem; border-width: 2px; }
    .crown-icon { font-size: 1.2rem; top: -20px; }
    .top3-rank-badge { font-size: 0.65rem; padding: 2px 8px; margin-top: -10px; border-width: 1.5px; }
    .top3-col p.text-base { font-size: 0.8rem; }
    .top3-col p.text-sm { font-size: 0.75rem; }
}
</style>

<div class="lb-page">

    {{-- ===== HEADER (DIPERTAHANKAN) ===== --}}
    <div class="lb-header">
        <div style="max-width:1200px;margin:0 auto;">
            <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <div>
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-white/10 backdrop-blur-md mb-4" style="color:rgba(255,255,255,0.9);">
                        Halaman Peringkat
                    </span>
                    <h1 class="text-4xl sm:text-5xl font-black tracking-tight leading-tight text-white">
                        Eco <span style="color:#6ee7b7;">Rankings</span>
                    </h1>
                    <p class="mt-3 text-base max-w-lg" style="color:rgba(167,243,208,0.9);">
                        Daftar kontributor terbaik dalam menjaga kelestarian lingkungan bersama Eco Ranger.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur rounded-2xl px-5 py-3.5" style="border:1px solid rgba(255,255,255,0.15);">
                        <span class="text-3xl font-black text-white">{{ $leaderboard->total() }}</span>
                        <div>
                            <p class="text-xs text-emerald-200 font-bold uppercase tracking-wider">User</p>
                            <p class="text-xs text-emerald-200 font-bold uppercase tracking-wider">Terdaftar</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="max-width:1200px;margin:0 auto;padding:0 1.5rem;">

        {{-- ===== PODIUM TOP 3 ===== --}}
        <div class="lb-top3-card">

            {{-- Rank 2 --}}
            @php $rank2 = $topThree[1] ?? null; @endphp
            <div class="top3-col" @if($rank2) onclick="window.location.href='/profile/{{ $rank2->id }}'" @endif>
                @if($rank2)
                <div class="podium-avatar rank2 mb-3">
                    @if($rank2->avatar)
                        <img src="{{ asset('storage/' . $rank2->avatar) }}" alt="{{ $rank2->name }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($rank2->name ?? '', 0, 2)) }}
                    @endif
                </div>
                <div class="top3-rank-badge r2">#2</div>
                <p class="text-sm font-bold text-slate-700 text-center mt-2 px-1 w-full truncate">{{ $rank2->name ?? '' }}</p>
                <p class="text-[10px] font-black text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-1 rounded-md mt-1">{{ number_format($rank2->total_points, 0, ',', '.') }} PTS</p>
                @else
                <div class="text-slate-300 font-bold text-sm">Belum ada</div>
                @endif
            </div>

            {{-- Rank 1 --}}
            @php $rank1 = $topThree[0] ?? null; @endphp
            <div class="top3-col" @if($rank1) onclick="window.location.href='/profile/{{ $rank1->id }}'" @endif>
                @if($rank1)
                <div style="position:relative;">
                    <div class="crown-icon">👑</div>
                    <div class="podium-avatar rank1 mb-3">
                        @if($rank1->avatar)
                            <img src="{{ asset('storage/' . $rank1->avatar) }}" alt="{{ $rank1->name }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($rank1->name ?? '', 0, 2)) }}
                        @endif
                    </div>
                </div>
                <div class="top3-rank-badge r1">#1</div>
                <p class="text-base font-black text-slate-800 text-center mt-2 px-1 w-full truncate">{{ $rank1->name ?? '' }}</p>
                <p class="text-xs font-black text-amber-600 bg-amber-50 border border-amber-200 px-3 py-1 rounded-md mt-1 shadow-sm">{{ number_format($rank1->total_points, 0, ',', '.') }} PTS</p>
                @else
                <div class="text-slate-300 font-bold text-sm">Belum ada</div>
                @endif
            </div>

            {{-- Rank 3 --}}
            @php $rank3 = $topThree[2] ?? null; @endphp
            <div class="top3-col" @if($rank3) onclick="window.location.href='/profile/{{ $rank3->id }}'" @endif>
                @if($rank3)
                <div class="podium-avatar rank3 mb-3">
                    @if($rank3->avatar)
                        <img src="{{ asset('storage/' . $rank3->avatar) }}" alt="{{ $rank3->name }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($rank3->name ?? '', 0, 2)) }}
                    @endif
                </div>
                <div class="top3-rank-badge r3">#3</div>
                <p class="text-sm font-bold text-slate-700 text-center mt-2 px-1 w-full truncate">{{ $rank3->name ?? '' }}</p>
                <p class="text-[10px] font-black text-orange-600 bg-orange-50 border border-orange-200 px-2 py-1 rounded-md mt-1">{{ number_format($rank3->total_points, 0, ',', '.') }} PTS</p>
                @else
                <div class="text-slate-300 font-bold text-sm">Belum ada</div>
                @endif
            </div>

        </div>

        {{-- ===== MAIN CONTENT ===== --}}
        <div class="mt-8 space-y-6 pb-12">

            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))
            <div class="lb-card p-4 text-emerald-700 font-bold text-center" style="border-color:rgba(52,211,153,0.3);background:rgba(240,253,244,0.95);">
                ✅ {{ session('success') }}
            </div>
            @endif

            {{-- ===== REWARD VOUCHER ===== --}}
            <div class="lb-card p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5">
                    <div>
                        <p class="lb-section-label mb-1">Hadiah Eksklusif</p>
                        <div class="flex items-center gap-3">
                            <h2 class="lb-section-title">Reward Voucher</h2>
                            @if(auth()->check() && auth()->user()->role === 'admin')
                                <a href="{{ route('leaderboard.admin.vouchers') }}" class="p-2 bg-slate-100 text-slate-500 hover:bg-emerald-100 hover:text-emerald-600 rounded-xl transition-all group" title="Kelola Voucher">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:rotate-45 transition-transform"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('vouchers.index') }}" class="lb-search-btn" style="border-radius:0.875rem;box-shadow:none;padding:0.65rem 1.25rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="12" rx="2"/><path d="M12 8v12"/><path d="M19 12v.01"/><path d="M5 12v.01"/></svg>
                        Klaim & Gunakan
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($rewards as $rank => $reward)
                    @php
                        $tierClass = $rank == 1 ? 'gold' : ($rank == 2 ? 'silver' : 'bronze');
                        $emoji = $rank == 1 ? '🥇' : ($rank == 2 ? '🥈' : '🥉');
                    @endphp
                    <div class="reward-card {{ $tierClass }}">
                        <div class="flex items-start gap-3">
                            <div class="reward-rank-badge {{ $tierClass }}">{{ $emoji }}</div>
                            <div>
                                <p class="text-xs font-black uppercase tracking-wider mb-0.5 {{ $rank == 1 ? 'text-amber-700' : ($rank == 2 ? 'text-slate-600' : 'text-orange-700') }}">
                                    Peringkat {{ $rank }}
                                </p>
                                <p class="text-[10px] font-bold text-slate-500 mb-1.5">Min. {{ number_format($reward['min_points'], 0, ',', '.') }} Poin</p>
                                <p class="text-sm text-slate-800 font-bold">{{ $reward['reward'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ===== SEARCH BAR ===== --}}
            <div class="lb-card p-4">
                <form method="GET" action="{{ route('leaderboard') }}" class="flex flex-col sm:flex-row gap-2.5">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍  Cari nama pengguna..." class="lb-search-input">
                    <button type="submit" class="lb-search-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        Cari
                    </button>
                    @if(request('search'))
                    <a href="{{ route('leaderboard') }}" style="padding:0.85rem 1.25rem;background:white;border:1.5px solid #e2e8f0;color:#475569;border-radius:999px;font-weight:700;font-size:0.9rem;text-decoration:none;display:inline-flex;align-items:center;gap:6px;transition:all 0.2s;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        Reset
                    </a>
                    @endif
                </form>
            </div>

            {{-- ===== PAPAN PERINGKAT ===== --}}
            <div class="lb-card overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1.5px solid #f1f5f9;">
                    <div class="flex items-center gap-3">
                        <div style="width:36px;height:36px;background:linear-gradient(135deg,#059669,#047857);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                        </div>
                        <div>
                            <p class="lb-section-label">Semua Kontributor</p>
                            <h2 class="lb-section-title">Papan Peringkat</h2>
                        </div>
                        @if(auth()->check() && auth()->user()->role === 'admin')
                        <form action="{{ route('leaderboard.admin.reset') }}" method="POST" onsubmit="return confirm('PERINGATAN: Aksi ini akan mereset SEMUA poin pengguna menjadi 0. Lanjutkan?')" class="inline ml-2">
                            @csrf
                            <button type="submit" class="p-2 bg-slate-100 text-red-400 hover:bg-red-50 hover:text-red-600 rounded-xl transition-all" title="Reset Semua Poin">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                            </button>
                        </form>
                        @endif
                    </div>
                    <span style="background:#f1f5f9;border:1.5px solid #e2e8f0;color:#64748b;font-size:0.75rem;font-weight:700;padding:5px 12px;border-radius:999px;">
                        {{ $leaderboard->total() }} Pengguna
                    </span>
                </div>

                @if($leaderboard->count() > 0)
                <div class="p-4 space-y-2">
                    @foreach($leaderboard as $user)
                    @php
                        $rankClass = $user->rank == 1 ? 'rank-1' : ($user->rank == 2 ? 'rank-2' : ($user->rank == 3 ? 'rank-3' : ''));
                        $badgeClass = $user->rank == 1 ? 'r1' : ($user->rank == 2 ? 'r2' : ($user->rank == 3 ? 'r3' : 'rn'));
                    @endphp
                    <div class="lb-row {{ $rankClass }}">
                        {{-- Rank Badge --}}
                        <span class="lb-rank-badge {{ $badgeClass }}">#{{ $user->rank }}</span>

                        {{-- Avatar --}}
                        <div class="lb-avatar">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            @endif
                        </div>

                        {{-- Name & Level --}}
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-slate-800 text-sm truncate">{{ $user->name }}</p>
                            <span class="lb-level-pill
                                {{ $user->level == 'Eco-Ranger' ? 'bg-emerald-100 text-emerald-700' : ($user->level == 'Eco-Warrior' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-500') }}">
                                {{ $user->level }}
                            </span>
                        </div>

                        {{-- Points --}}
                        <div class="lb-pts-pill">
                            <span>{{ number_format($user->total_points, 0, ',', '.') }}</span>
                            <span style="font-size:0.6rem;font-weight:800;color:#34d399;">PTS</span>
                        </div>

                        {{-- Admin actions --}}
                        @if(auth()->check() && auth()->user()->role === 'admin')
                        <div style="border-left:1.5px solid #f1f5f9;padding-left:0.75rem;">
                            <form action="{{ route('leaderboard.admin.adjust') }}" method="POST" class="flex gap-1.5 items-center">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <input type="number" name="points" placeholder="Poin" required style="width:64px;border:1.5px solid #e2e8f0;border-radius:8px;padding:4px 6px;font-size:0.7rem;text-align:center;outline:none;" title="Positif/negatif">
                                <input type="text" name="description" placeholder="Alasan..." required style="width:88px;border:1.5px solid #e2e8f0;border-radius:8px;padding:4px 6px;font-size:0.7rem;outline:none;">
                                <button type="submit" style="background:linear-gradient(135deg,#059669,#047857);color:white;padding:5px 10px;border-radius:8px;font-size:0.7rem;font-weight:700;border:none;cursor:pointer;">Set</button>
                            </form>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <div class="px-6 py-4" style="border-top:1.5px solid #f1f5f9;background:#fafafa;">
                    {{ $leaderboard->appends(request()->query())->links() }}
                </div>

                @else
                <div class="text-center py-16 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 1rem;opacity:0.4;"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                    <p class="font-semibold text-slate-500">Tidak ada pengguna yang ditemukan</p>
                    @if(request('search'))
                        <a href="{{ route('leaderboard') }}" style="color:#059669;font-weight:700;margin-top:0.5rem;display:inline-block;">Reset Pencarian</a>
                    @endif
                </div>
                @endif
            </div>

            {{-- ===== ATURAN POIN ===== --}}
            <div class="lb-card p-6">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-3">
                            <p class="lb-section-label mb-0">Sistem Gamifikasi</p>
                        </div>
                        <h2 class="lb-section-title">Aturan Poin</h2>
                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <a href="{{ route('leaderboard.admin.point_rules') }}" class="p-2 bg-slate-100 text-slate-500 hover:bg-emerald-100 hover:text-emerald-600 rounded-xl transition-all group" title="Kelola Aturan Poin">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:rotate-45 transition-transform"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($pointRules as $rule)
                    <div class="rule-card">
                        <div class="rule-icon">
                            @if($rule['activity'] == 'Lapor Isu Lingkungan')
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>
                            @elseif($rule['activity'] == 'Ikuti event Lingkungan')
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            @elseif($rule['activity'] == 'Laporan Diverifikasi')
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
                            @endif
                        </div>
                        <span style="font-size:0.65rem;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:0.07em;margin-bottom:0.5rem;line-height:1.4;display:block;">{{ $rule['activity'] }}</span>
                        <span style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);color:#059669;font-weight:900;font-size:1rem;padding:4px 12px;border-radius:999px;border:1px solid #a7f3d0;">+{{ $rule['points'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ===== LENCANA & PENCAPAIAN ===== --}}
            <div>
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center gap-3">
                        <div>
                            <p class="lb-section-label mb-0.5">Sistem Pencapaian</p>
                            <div class="flex items-center gap-3">
                                <h2 class="lb-section-title">Lencana & Pencapaian</h2>
                                @if(auth()->check() && auth()->user()->role === 'admin')
                                    <a href="{{ route('leaderboard.admin.badges') }}" class="p-2 bg-slate-100 text-slate-500 hover:bg-emerald-100 hover:text-emerald-600 rounded-xl transition-all group" title="Kelola Lencana">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:rotate-45 transition-transform"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if(Auth::check() && count($badges) > 0)
                        <a href="{{ route('badges.index') }}" style="font-size:0.8rem;font-weight:700;color:#059669;text-decoration:none;">Lihat Semua &rarr;</a>
                    @endif
                </div>

                @if(Auth::check() && count($badges) > 0)
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    @foreach($badges as $badge)
                    <div class="badge-card">
                        <div style="position:relative;z-index:1;">
                            <div class="flex items-start justify-between mb-2">
                                <h3 style="font-size:0.7rem;font-weight:900;color:white;text-transform:uppercase;letter-spacing:0.12em;line-height:1.3;">{{ $badge->name }}</h3>
                                @if($badge->progress_percentage >= 100)
                                    <span style="width:8px;height:8px;background:#34d399;border-radius:50%;flex-shrink:0;box-shadow:0 0 6px rgba(52,211,153,0.6);margin-top:3px;"></span>
                                @endif
                            </div>
                            <p style="font-size:0.6rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $badge->description }}</p>
                        </div>
                        <div style="position:relative;z-index:1;">
                            <div class="badge-progress mb-1.5">
                                <div class="badge-progress-bar {{ $badge->progress_percentage >= 100 ? 'complete' : '' }}" style="width:{{ $badge->progress_percentage }}%;"></div>
                            </div>
                            <div class="flex justify-between items-center" style="font-size:0.62rem;font-weight:700;">
                                <span style="color:#64748b;text-transform:uppercase;letter-spacing:0.06em;">Progress</span>
                                <span style="color:white;">{{ (int)$badge->current_progress }}/{{ $badge->target }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="lb-card p-8 text-center">
                    <div style="width:48px;height:48px;background:#f1f5f9;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;color:#94a3b8;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><line x1="9" x2="15" y1="9" y2="9"/><line x1="9" x2="15" y1="13" y2="13"/></svg>
                    </div>
                    <p style="color:#94a3b8;font-size:0.85rem;font-weight:600;">Belum ada lencana tersedia atau silakan <a href="{{ route('login') }}" style="color:#059669;font-weight:700;">Login</a> untuk melihat progress Anda</p>
                </div>
                @endif
            </div>

            {{-- ===== POSISI USER / LOGIN BANNER ===== --}}
            @auth
                @if(auth()->user()->role !== 'admin')
                <div class="user-pos-banner">
                    <div class="user-pos-dots"></div>
                    <div style="position:relative;z-index:1;">
                        <p style="font-size:0.6rem;font-weight:900;text-transform:uppercase;letter-spacing:0.25em;color:rgba(167,243,208,0.9);margin-bottom:1rem;">📊 Posisi Anda Saat Ini</p>
                        <div style="display:inline-flex;align-items:flex-end;justify-content:center;gap:0.75rem;background:rgba(255,255,255,0.08);padding:1rem 2rem;border-radius:1rem;border:1px solid rgba(255,255,255,0.12);backdrop-filter:blur(12px);">
                            <span style="font-size:1rem;font-weight:700;color:rgba(167,243,208,0.9);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:4px;">Rank</span>
                            <span style="font-size:3rem;font-weight:900;color:white;line-height:1;letter-spacing:-0.03em;">#{{ $userPosition ?? '?' }}</span>
                        </div>
                        <p style="font-size:1.1rem;font-weight:700;margin-top:1rem;color:rgba(209,250,229,0.95);">
                            <strong style="color:white;font-size:1.4rem;font-weight:900;">{{ number_format($userPoints ?? 0, 0, ',', '.') }}</strong> total poin Eco
                        </p>
                        <p style="font-size:0.65rem;font-weight:800;color:rgba(167,243,208,0.85);margin-top:1rem;background:rgba(0,0,0,0.15);display:inline-flex;align-items:center;gap:6px;padding:6px 16px;border-radius:999px;text-transform:uppercase;letter-spacing:0.1em;">
                            🌿 Terus berkontribusi untuk naik peringkat
                        </p>
                    </div>
                </div>
                @endif
            @else
            <div class="lb-card p-10 text-center" style="background:linear-gradient(160deg,rgba(15,23,42,0.97),rgba(30,41,59,0.97));border-color:rgba(71,85,105,0.5);position:relative;overflow:hidden;">
                <div style="position:absolute;inset:0;background-image:radial-gradient(circle at 2px 2px, rgba(255,255,255,0.04) 1px, transparent 0);background-size:24px 24px;"></div>
                <div style="position:relative;z-index:1;">
                    <div style="width:56px;height:56px;background:rgba(5,150,105,0.2);border:1.5px solid rgba(52,211,153,0.3);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;color:#34d399;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <p style="font-size:0.65rem;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:0.2em;margin-bottom:0.75rem;">Posisi Anda Saat Ini</p>
                    <p style="font-size:1.1rem;font-weight:600;color:#94a3b8;margin-bottom:1.5rem;">Silakan login untuk melihat posisi Anda di Leaderboard</p>
                    <a href="{{ route('login') }}" style="display:inline-flex;align-items:center;gap:8px;padding:0.85rem 2rem;background:linear-gradient(135deg,#059669,#047857);color:white;border-radius:12px;font-weight:700;text-decoration:none;box-shadow:0 6px 20px rgba(5,150,105,0.35);transition:all 0.2s;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Login Sekarang
                    </a>
                </div>
            </div>
            @endauth

        </div>
    </div>
</div>
@endsection
