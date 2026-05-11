@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Header Section -->
<div class="bg-gradient-to-r from-emerald-800 to-emerald-600 text-white py-16 px-4 sm:px-6 rounded-3xl shadow-lg mb-12">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div>
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-white/10 backdrop-blur-md mb-4">
                    Pencapaian Eco Ranger
                </span>
                <div class="flex items-center gap-4 mb-2">
                    <a href="{{ route('leaderboard') }}" class="group flex items-center justify-center w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full transition-all border border-white/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-white group-hover:-translate-x-0.5 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
                    </a>
                    <h1 class="text-4xl sm:text-5xl font-black tracking-tight leading-tight">
                        Koleksi <span class="text-emerald-300">Lencana</span>
                    </h1>
                </div>
                <p class="mt-3 text-emerald-100 text-base max-w-lg">
                    Kumpulkan lencana dengan aktif berkontribusi dalam menjaga kelestarian lingkungan!
                </p>
            </div>

            <!-- Stats -->
            <div class="flex flex-wrap gap-3">
                <div class="bg-white/10 backdrop-blur-md rounded-2xl px-5 py-4 border border-white/10 text-center">
                    <div class="text-2xl font-black text-white">{{ $achievedCount }}/{{ $totalBadges }}</div>
                    <div class="text-[10px] text-emerald-200 uppercase tracking-widest font-black mt-1">Lencana</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl px-5 py-4 border border-white/10 text-center">
                    <div class="text-2xl font-black text-emerald-300">{{ $completionPercentage }}%</div>
                    <div class="text-[10px] text-emerald-200 uppercase tracking-widest font-black mt-1">Selesai</div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Voucher Navigation Section -->
    <div class="mb-8 flex justify-between items-center bg-white rounded-2xl p-6 border border-emerald-100 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="12" rx="2"/><path d="M12 8v12"/><path d="M19 12v.01"/><path d="M19 16v.01"/><path d="M5 12v.01"/><path d="M5 16v.01"/><path d="M10.5 8c0-1.5-1.5-3-3-3s-3 1.5-3 3"/><path d="M13.5 8c0-1.5 1.5-3 3-3s3 1.5 3 3"/></svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-slate-800">Voucher & Reward Eksklusif</h3>
                <p class="text-slate-500 text-sm">Khusus untuk Rank 1, 2, dan 3 di Leaderboard</p>
            </div>
        </div>
        <a href="{{ route('vouchers.index') }}" class="px-6 py-3 bg-emerald-600 text-white rounded-full font-bold shadow-md shadow-emerald-200 hover:bg-emerald-700 hover:-translate-y-0.5 transition-all active:translate-y-0 flex items-center gap-2">
            Klaim Voucher
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
        </a>
    </div>

    <!-- Badge Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($badges as $badge)
        @php
            // Setup colors based on level
            $levelColor = 'bg-green-50 text-green-700 border-green-200';
            $shadowColor = '';
            
            if ($badge->is_achieved) {
                if ($badge->level == 'gold') {
                    $levelColor = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                    $shadowColor = 'shadow-[0_0_15px_rgba(234,179,8,0.3)]';
                } elseif ($badge->level == 'silver') {
                    $levelColor = 'bg-gray-50 text-gray-700 border-gray-200';
                    $shadowColor = 'shadow-[0_0_15px_rgba(156,163,175,0.3)]';
                } elseif ($badge->level == 'bronze') {
                    $levelColor = 'bg-orange-50 text-orange-800 border-orange-200';
                    $shadowColor = 'shadow-[0_0_15px_rgba(249,115,22,0.3)]';
                }
            } else {
                $levelColor = 'bg-gray-50 text-gray-500 border-gray-200';
            }
        @endphp
        
        <div class="relative bg-slate-900 rounded-[2rem] border border-slate-800 p-8 transition-all duration-500 hover:shadow-2xl group hover:border-emerald-500/50 flex flex-col justify-between min-h-[220px]">
            
            <div class="flex items-center justify-between mb-6">
                <div class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $levelColor }}">
                    {{ $badge->level }}
                </div>
                @if($badge->is_achieved)
                    <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                @endif
            </div>

            <div class="mb-6">
                <h3 class="text-base font-black text-white mb-2 uppercase tracking-tight leading-tight">{{ $badge->name }}</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest leading-relaxed">{{ $badge->description }}</p>
            </div>

            <div>
                <div class="flex justify-between text-[10px] font-black uppercase tracking-widest mb-3">
                    <span class="{{ $badge->is_achieved ? 'text-emerald-400' : 'text-slate-500' }}">
                        {{ $badge->is_achieved ? 'Achieved' : 'Progress' }}
                    </span>
                    <span class="text-slate-300">
                        {{ $badge->current_progress }} / {{ $badge->target }}
                    </span>
                </div>
                
                <div class="w-full bg-slate-800 rounded-full h-1.5 mb-2 overflow-hidden border border-slate-700">
                    <div class="h-full rounded-full transition-all duration-1000 ease-out {{ $badge->is_achieved ? 'bg-emerald-400' : 'bg-emerald-600' }}" 
                         style="width: {{ $badge->progress_percentage }}%"></div>
                </div>
                
                @if($badge->is_achieved && $badge->achieved_at)
                <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-4">
                    Diraih: {{ \Carbon\Carbon::parse($badge->achieved_at)->translatedFormat('d M Y') }}
                </div>
                @elseif($badge->points_reward > 0)
                <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-4 flex items-center gap-1">
                    Reward: <span class="text-emerald-400">+{{ $badge->points_reward }} Poin</span>
                </div>
                @endif
            </div>
            
            <!-- Lock Overlay if not achieved -->
            @if(!$badge->is_achieved)
            <div class="absolute inset-0 bg-white/40 backdrop-blur-[1px] rounded-2xl flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity duration-300">
                <div class="bg-gray-900/80 text-white px-4 py-2 rounded-lg font-semibold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                    Terkunci
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection
