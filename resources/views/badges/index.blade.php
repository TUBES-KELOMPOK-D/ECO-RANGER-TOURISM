@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 to-green-800 rounded-2xl p-8 mb-8 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold mb-2 font-inter">Koleksi Lencana Eco Ranger</h1>
                    <p class="text-green-100 font-inter">Kumpulkan lencana dengan aktif berkontribusi dalam menjaga lingkungan!</p>
                </div>
                <a href="{{ route('leaderboard') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-white/20 hover:bg-white/30 backdrop-blur-md text-white rounded-xl font-bold transition-all border border-white/30 shadow-sm self-start md:self-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Kembali ke Peringkat
                </a>
            </div>
            
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold mb-1">{{ $achievedCount }}/{{ $totalBadges }}</div>
                    <div class="text-sm text-green-100 uppercase tracking-wider font-semibold">Terkumpul</div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold mb-1">{{ $completionPercentage }}%</div>
                    <div class="text-sm text-green-100 uppercase tracking-wider font-semibold">Selesai</div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold mb-1 text-yellow-500">{{ $goldCount }}</div>
                    <div class="text-sm text-green-100 uppercase tracking-wider font-semibold">Gold</div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold mb-1 text-gray-300">{{ $silverCount }}</div>
                    <div class="text-sm text-green-100 uppercase tracking-wider font-semibold">Silver</div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center">
                    <div class="text-3xl font-bold mb-1 text-orange-400">{{ $bronzeCount }}</div>
                    <div class="text-sm text-green-100 uppercase tracking-wider font-semibold">Bronze</div>
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
        
        <div class="relative bg-white rounded-2xl border p-6 transition-all duration-300 hover:shadow-lg {{ $badge->is_achieved ? 'border-green-200 ' . $shadowColor : 'border-gray-200 opacity-75 hover:opacity-100' }}">
            
            <!-- Level Indicator -->
            <div class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $levelColor }}">
                {{ $badge->level }}
            </div>

            <!-- Icon -->
            <div class="flex justify-center mb-6">
                <div class="w-24 h-24 rounded-full flex items-center justify-center text-emerald-600 {{ $badge->is_achieved ? 'bg-emerald-50' : 'bg-slate-50 text-slate-300' }}">
                    @if(str_contains($badge->slug, 'report') || str_contains($badge->slug, 'plastic') || str_contains($badge->slug, 'saver') || str_contains($badge->slug, 'verified'))
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>
                    @elseif(str_contains($badge->slug, 'event') || str_contains($badge->slug, 'tree'))
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    @elseif(str_contains($badge->slug, 'module') || str_contains($badge->slug, 'academy'))
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>
                    @elseif(str_contains($badge->slug, 'eco-'))
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    @endif
                </div>
            </div>

            <!-- Info -->
            <div class="text-center mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-2 font-inter">{{ $badge->name }}</h3>
                <p class="text-sm text-gray-600 min-h-[40px]">{{ $badge->description }}</p>
            </div>

            <!-- Progress -->
            <div>
                <div class="flex justify-between text-xs font-semibold mb-2">
                    <span class="{{ $badge->is_achieved ? 'text-green-600' : 'text-gray-500' }}">
                        {{ $badge->is_achieved ? 'Tercapai!' : 'Progress' }}
                    </span>
                    <span class="text-gray-600">
                        {{ $badge->current_progress }} / {{ $badge->target }}
                    </span>
                </div>
                
                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2 overflow-hidden">
                    <div class="h-2.5 rounded-full transition-all duration-1000 ease-out {{ $badge->is_achieved ? 'bg-green-500' : 'bg-green-400' }}" 
                         style="width: {{ $badge->progress_percentage }}%"></div>
                </div>
                
                @if($badge->is_achieved && $badge->achieved_at)
                <div class="text-xs text-center text-gray-500 mt-3">
                    Diraih pada: {{ \Carbon\Carbon::parse($badge->achieved_at)->format('d M Y') }}
                </div>
                @elseif($badge->points_reward > 0)
                <div class="text-xs text-center text-gray-500 mt-3 flex items-center justify-center gap-1">
                    <span class="text-yellow-500 font-bold">+{{ $badge->points_reward }}</span> Poin
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
