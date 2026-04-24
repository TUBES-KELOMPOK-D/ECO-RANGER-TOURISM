@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12">
    <!-- GREEN HEADER WITH PODIUM -->
    <div class="bg-gradient-to-b from-emerald-700 to-emerald-600 rounded-b-3xl shadow-lg py-16" style="background: linear-gradient(to bottom, #098352, #10A96E);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-white text-center mb-12">Eco-Rankings 🏆</h1>
            
            <!-- PODIUM SECTION -->
            <div class="flex flex-col md:flex-row justify-center items-end gap-8 md:gap-6 h-96 md:h-80 w-full max-w-4xl mx-auto">
                <!-- Rank #2 (Left) - Height 60% -->
                @php $rank2 = $topThree[1] ?? null; @endphp
                <div class="flex-1 text-center flex flex-col items-center h-3/5 w-full">
                    @if($rank2)
                        <div class="mb-3 flex justify-center">
                            <div class="w-14 h-14 bg-gradient-to-b from-gray-300 to-gray-400 rounded-2xl flex items-center justify-center text-white text-lg font-bold shadow-lg border-2 border-white">
                                {{ strtoupper(substr($rank2->name ?? '', 0, 2)) }}
                            </div>
                        </div>
                        <div class="w-full flex-1 rounded-t-3xl flex items-center justify-center relative border-4 border-white shadow-lg mx-2" style="background: linear-gradient(to top, #10A96E, #1FD571);">
                            <div class="text-center">
                                <p class="text-5xl font-black text-white">2</p>
                            </div>
                        </div>
                        <p class="text-white text-sm font-bold mt-2 w-full py-2 px-2 rounded-b-2xl mx-2" style="background-color: #10A96E;">{{ $rank2->name ?? '' }}</p>
                    @endif
                </div>

                <!-- Rank #1 (Center) - Height 100% TALLEST -->
                @php $rank1 = $topThree[0] ?? null; @endphp
                <div class="flex-1 text-center flex flex-col items-center h-full mb-4 w-full cursor-pointer z-10" onclick="window.location.href='/profile/{{ $rank1->id ?? '' }}'">
                    @if($rank1)
                        <div class="mb-4 animate-bounce">
                            <div class="text-5xl">👑</div>
                        </div>
                        <div class="mb-4 flex justify-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-3xl flex items-center justify-center text-white text-2xl font-black shadow-xl border-4 border-white transform scale-110">
                                {{ strtoupper(substr($rank1->name ?? '', 0, 2)) }}
                            </div>
                        </div>
                        <div class="w-full flex-1 rounded-t-3xl flex items-center justify-center relative border-4 border-white shadow-2xl mx-2" style="background: linear-gradient(to top, #098352, #10A96E);">
                            <div class="text-center">
                                <p class="text-7xl font-black text-white">1</p>
                            </div>
                        </div>
                        <p class="text-white text-sm font-black mt-2 w-full py-2 px-2 rounded-b-2xl mx-2" style="background-color: #098352;">{{ $rank1->name ?? '' }}</p>
                    @endif
                </div>

                <!-- Rank #3 (Right) - Height 40% SHORTEST -->
                @php $rank3 = $topThree[2] ?? null; @endphp
                <div class="flex-1 text-center flex flex-col items-center h-2/5 w-full">
                    @if($rank3)
                        <div class="mb-2 flex justify-center">
                            <div class="w-12 h-12 bg-gradient-to-b from-orange-300 to-orange-500 rounded-2xl flex items-center justify-center text-white text-base font-bold shadow-lg border-2 border-white">
                                {{ strtoupper(substr($rank3->name ?? '', 0, 2)) }}
                            </div>
                        </div>
                        <div class="w-full flex-1 rounded-t-3xl flex items-center justify-center relative border-4 border-white shadow-lg mx-2" style="background: linear-gradient(to top, #10A96E, #1FD571);">
                            <div class="text-center">
                                <p class="text-4xl font-black text-white">3</p>
                            </div>
                        </div>
                        <p class="text-white text-sm font-bold mt-2 w-full py-2 px-2 rounded-b-2xl mx-2" style="background-color: #10A96E;">{{ $rank3->name ?? '' }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT (Tumpuk ke bawah) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 space-y-8">
        
        <!-- REWARD VOUCHER -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-slate-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                Reward Voucher
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($rewards as $rank => $reward)
                <div class="bg-gradient-to-r @if($rank == 1) from-yellow-50 to-yellow-100 border-yellow-300 @elseif($rank == 2) from-gray-100 to-gray-200 border-gray-400 @else from-orange-50 to-orange-100 border-orange-300 @endif rounded-xl p-4 border">
                    <p class="font-bold text-sm @if($rank == 1) text-yellow-700 @elseif($rank == 2) text-gray-700 @else text-orange-700 @endif">
                        {{ $reward['icon'] }} {{ $reward['title'] }}
                    </p>
                    <p class="text-sm font-semibold text-gray-700 mt-2">🎫 {{ $reward['reward'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- SEARCH BAR -->
        <div class="bg-white rounded-2xl shadow-sm p-4 border border-slate-100">
            <form method="GET" action="{{ route('leaderboard') }}" class="flex flex-col sm:flex-row gap-2">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari nama atau level pengguna..." 
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all font-medium text-slate-700 bg-slate-50">
                <button type="submit" class="px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition font-bold shadow-md shadow-emerald-500/20 active:translate-y-0.5">
                    🔍 Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('leaderboard') }}" class="px-6 py-3 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition font-bold text-center active:translate-y-0.5">
                        ✕ Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- PAPAN PERINGKAT -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-100">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    Papan Peringkat
                </h2>
                <div class="text-sm font-semibold text-slate-500 bg-white px-3 py-1 rounded-lg border border-slate-200 shadow-sm">
                    Total: {{ $leaderboard->total() }} Pengguna
                </div>
            </div>
            
            @if($leaderboard->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-white text-left text-sm font-bold text-slate-500 uppercase tracking-wider border-b-2 border-emerald-500">
                                <th class="py-4 px-6 w-20 text-center">Rank</th>
                                <th class="py-4 px-6">Pengguna</th>
                                <th class="py-4 px-6">Level</th>
                                <th class="py-4 px-6 text-right">Total Poin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($leaderboard as $user)
                                <tr class="hover:bg-emerald-50/50 transition-colors group">
                                    <td class="py-4 px-6 text-center">
                                        @if($user->rank == 1) 
                                            <span class="text-2xl" title="Rank 1">🥇</span>
                                        @elseif($user->rank == 2) 
                                            <span class="text-2xl" title="Rank 2">🥈</span>
                                        @elseif($user->rank == 3) 
                                            <span class="text-2xl" title="Rank 3">🥉</span>
                                        @else 
                                            <span class="font-bold text-slate-500 bg-slate-100 px-3 py-1 rounded-lg">#{{ $user->rank }}</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            @if($user->avatar)
                                                <div class="w-10 h-10 rounded-xl overflow-hidden bg-emerald-100 shadow-sm">
                                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                                </div>
                                            @else
                                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-xl flex items-center justify-center text-emerald-700 font-bold shadow-sm border border-emerald-300/50">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                            @endif
                                            <span class="font-bold text-slate-800 group-hover:text-emerald-700 transition-colors">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="px-4 py-1.5 rounded-full text-xs font-bold shadow-sm border 
                                            @if($user->level == 'Eco-Ranger') bg-emerald-100 text-emerald-700 border-emerald-200
                                            @elseif($user->level == 'Eco-Warrior') bg-blue-100 text-blue-700 border-blue-200
                                            @else bg-slate-100 text-slate-600 border-slate-200 @endif">
                                            {{ $user->level }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="inline-flex items-center gap-1.5 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-100/50">
                                            <span class="font-black text-emerald-600 tracking-tight">{{ number_format($user->total_points) }}</span>
                                            <span class="text-xs font-bold text-emerald-400">PTS</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="p-6 border-t border-slate-100 bg-slate-50">
                    {{ $leaderboard->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-16 text-slate-400">
                    <div class="text-5xl mb-4 opacity-50">🌱</div>
                    <p class="font-medium text-lg text-slate-500">Tidak ada pengguna yang ditemukan</p>
                    @if(request('search'))
                        <a href="{{ route('leaderboard') }}" class="text-emerald-600 font-bold mt-2 inline-block hover:underline">Reset Pencarian</a>
                    @endif
                </div>
            @endif
        </div>

        <!-- ATURAN POIN -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                Aturan Poin
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @foreach($pointRules as $rule)
                <div class="flex flex-col items-center justify-center p-5 bg-gradient-to-b from-slate-50 to-slate-100 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all group">
                    <span class="text-3xl mb-3 group-hover:scale-110 transition-transform">{{ $rule['icon'] }}</span>
                    <span class="text-xs font-bold text-slate-600 text-center uppercase tracking-wider h-8 flex items-center justify-center mb-1">{{ $rule['activity'] }}</span>
                    <span class="font-black text-lg text-emerald-600 bg-emerald-100/50 px-3 py-1 rounded-xl">+{{ $rule['points'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- LENCANA & PENCAPAIAN -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                Lencana & Pencapaian
            </h2>
            
            @if(Auth::check() && count($badges) > 0)
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    @foreach($badges as $badge)
                        <div class="p-5 bg-[#0f172a] rounded-xl border border-slate-700 shadow-md relative overflow-hidden group hover:border-emerald-500 transition-colors">
                            <div class="absolute right-0 bottom-0 opacity-5 transform translate-x-4 translate-y-4 group-hover:scale-110 transition-transform">
                                <span class="text-8xl">🏆</span>
                            </div>
                            <div class="relative z-10">
                                <h3 class="font-bold text-white text-base mb-1 flex items-center gap-2">
                                    {{ $badge['name'] }}
                                </h3>
                                <p class="text-xs text-slate-400 mb-4">{{ $badge['target'] }}</p>
                                
                                <div class="w-full bg-slate-800 rounded-full h-2 mb-2 overflow-hidden border border-slate-700">
                                    <div class="h-full transition-all duration-1000 @if($badge['progress'] >= 100) bg-emerald-400 shadow-[0_0_10px_rgba(52,211,153,0.5)] @else bg-emerald-500 @endif" style="width: {{ $badge['progress'] }}%;"></div>
                                </div>
                                <div class="flex justify-between items-center text-[10px] font-bold text-slate-400">
                                    <span class="uppercase tracking-wider">Progress</span>
                                    <span class="text-white">{{ (int)$badge['current'] }}/{{ $badge['max'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-slate-500 text-sm font-medium bg-slate-50 rounded-xl border border-slate-100">
                    📝 Silakan Login untuk melihat progress pencapaian Anda
                </div>
            @endif
        </div>
        
        <!-- TIPS NAIK PERINGKAT -->
        <div class="bg-blue-50/50 rounded-2xl shadow-sm p-6 border border-blue-100">
            <h3 class="font-bold text-blue-800 mb-4 flex items-center gap-2">
                Tips Naik Peringkat
            </h3>
            <ul class="text-sm font-medium text-blue-700/80 grid grid-cols-1 md:grid-cols-2 gap-3">
                <li class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div> Laporkan isu lingkungan yang Anda temui</li>
                <li class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div> Ikuti event-event komunitas hijau</li>
                <li class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div> Aktif berdiskusi di forum komunitas</li>
                <li class="flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div> Bagikan konten tentang lingkungan</li>
                <li class="flex items-center gap-2 md:col-span-2"><div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div> Selesaikan semua kuis di Green Academy</li>
            </ul>
        </div>
        
        <!-- POSISI USER SAAT INI (Paling Bawah) -->
        @auth
            <div class="rounded-3xl shadow-lg p-10 text-white text-center relative overflow-hidden" style="background: linear-gradient(135deg, #098352 0%, #10A96E 100%);">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-5 mix-blend-overlay"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 rounded-full bg-white opacity-10 mix-blend-overlay"></div>
                
                <div class="relative z-10">
                    <p class="text-sm font-bold uppercase tracking-[0.2em] text-emerald-100 mb-4">Posisi Anda Saat Ini</p>
                    <div class="inline-flex items-end justify-center gap-3 bg-white/10 px-8 py-4 rounded-3xl backdrop-blur-sm border border-white/20 shadow-inner">
                        <span class="text-3xl font-medium text-emerald-50">Rank</span>
                        <span class="text-6xl font-black text-white leading-none">#{{ $userPosition ?? '?' }}</span>
                    </div>
                    <p class="text-xl font-medium mt-6 text-emerald-50"><strong class="font-bold text-white">{{ number_format($userPoints ?? 0) }}</strong> total poin Eco</p>
                    <p class="text-sm font-bold text-emerald-100 mt-6 bg-black/10 inline-block px-4 py-2 rounded-full">✨ Terus berkontribusi untuk naik peringkat!</p>
                </div>
            </div>
        @else
            <div class="rounded-3xl shadow-lg p-10 text-white text-center bg-slate-800 relative overflow-hidden">
                <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
                <div class="relative z-10">
                    <p class="text-sm font-bold uppercase tracking-[0.2em] text-slate-400 mb-4">Posisi Anda Saat Ini</p>
                    <p class="text-xl font-medium text-slate-200">Silakan login untuk melihat posisi Anda di Leaderboard</p>
                    <a href="{{ route('login') }}" class="mt-8 mx-auto inline-flex items-center gap-2 px-8 py-4 bg-emerald-500 rounded-xl font-bold shadow-lg shadow-emerald-500/30 hover:bg-emerald-400 hover:shadow-emerald-400/50 transition-all text-white active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Login Sekarang
                    </a>
                </div>
            </div>
        @endauth
        
    </div>
</div>
@endsection
