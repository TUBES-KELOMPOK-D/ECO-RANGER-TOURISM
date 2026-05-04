@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-12">
    <!-- GREEN HEADER WITH PODIUM -->
    <div class="bg-gradient-to-b from-emerald-700 to-emerald-600 rounded-b-3xl shadow-lg py-16" style="background: linear-gradient(to bottom, #098352, #10A96E);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-white text-center mb-12">Eco-Rankings</h1>
            
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
                        <div class="mb-4 animate-bounce text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="drop-shadow-lg"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
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

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 space-y-8">
        
        <!-- SUCCESS MESSAGE -->
        @if(session('success'))
        <div class="bg-emerald-500/10 border border-emerald-500/20 p-4 rounded-2xl text-emerald-600 font-bold text-center animate-pulse">
            {{ session('success') }}
        </div>
        @endif

        <!-- REWARD VOUCHER -->
        <div class="bg-white rounded-2xl shadow-md p-6 border border-slate-100">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                <div class="flex items-center gap-4">
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        Reward Voucher
                    </h2>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('leaderboard.admin.vouchers') }}" class="p-2 bg-slate-100 text-slate-500 hover:bg-emerald-100 hover:text-emerald-600 rounded-xl transition-all shadow-sm group" title="Kelola Voucher">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:rotate-45 transition-transform"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        </a>
                    @endif
                </div>
                <a href="{{ route('vouchers.index') }}" class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition shadow-sm flex items-center gap-2 text-sm justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="12" rx="2"/><path d="M12 8v12"/><path d="M19 12v.01"/><path d="M19 16v.01"/><path d="M5 12v.01"/><path d="M5 16v.01"/><path d="M10.5 8c0-1.5-1.5-3-3-3s-3 1.5-3 3"/><path d="M13.5 8c0-1.5 1.5-3 3-3s3 1.5 3 3"/></svg>
                    Klaim & Gunakan Voucher
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($rewards as $rank => $reward)
                <div class="bg-gradient-to-r @if($rank == 1) from-yellow-50 to-yellow-100 border-yellow-300 @elseif($rank == 2) from-gray-100 to-gray-200 border-gray-400 @else from-orange-50 to-orange-100 border-orange-300 @endif rounded-xl p-4 border">
                    <p class="font-bold text-sm @if($rank == 1) text-yellow-700 @elseif($rank == 2) text-gray-700 @else text-orange-700 @endif flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                        {{ $reward['title'] }}
                    </p>
                    <p class="text-sm font-semibold text-gray-700 mt-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg>
                        {{ $reward['reward'] }}
                    </p>
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
                <button type="submit" class="px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition font-bold shadow-md shadow-emerald-500/20 active:translate-y-0.5 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('leaderboard') }}" class="px-6 py-3 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition font-bold text-center active:translate-y-0.5 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- PAPAN PERINGKAT -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-100">
            <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        Papan Peringkat
                    </h2>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <form action="{{ route('leaderboard.admin.reset') }}" method="POST" onsubmit="return confirm('PERINGATAN: Aksi ini akan mereset SEMUA poin pengguna menjadi 0. Lanjutkan?')" class="inline">
                            @csrf
                            <button type="submit" class="p-2 bg-slate-100 text-red-400 hover:bg-red-50 hover:text-red-600 rounded-xl transition-all shadow-sm group" title="Reset Semua Poin">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                            </button>
                        </form>
                    @endif
                </div>
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
                                @if(auth()->check() && auth()->user()->role === 'admin')
                                <th class="py-4 px-6 text-center bg-slate-50 border-l border-slate-200">Aksi (Admin)</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($leaderboard as $user)
                                <tr class="hover:bg-emerald-50/50 transition-colors group">
                                    <td class="py-4 px-6 text-center">
                                        @if($user->rank == 1) 
                                            <span class="font-bold text-yellow-600 bg-yellow-100 px-3 py-1 rounded-lg border border-yellow-200">#1</span>
                                        @elseif($user->rank == 2) 
                                            <span class="font-bold text-slate-600 bg-slate-200 px-3 py-1 rounded-lg border border-slate-300">#2</span>
                                        @elseif($user->rank == 3) 
                                            <span class="font-bold text-orange-600 bg-orange-100 px-3 py-1 rounded-lg border border-orange-200">#3</span>
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
                                    @if(auth()->check() && auth()->user()->role === 'admin')
                                    <td class="py-4 px-6 bg-slate-50/50 border-l border-slate-100">
                                        <form action="{{ route('leaderboard.admin.adjust') }}" method="POST" class="flex gap-2 items-center justify-center">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <input type="number" name="points" placeholder="Poin" required class="w-20 rounded-lg border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-xs p-2 text-center bg-white" title="Bisa nilai positif atau negatif">
                                            <input type="text" name="description" placeholder="Alasan..." required class="w-32 rounded-lg border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-xs p-2 bg-white">
                                            <button type="submit" class="rounded-lg bg-emerald-600 px-3 py-2 text-white text-xs font-bold hover:bg-emerald-700 shadow-sm transition-all">Set</button>
                                        </form>
                                    </td>
                                    @endif
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
                    <div class="flex justify-center mb-4 opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                    </div>
                    <p class="font-medium text-lg text-slate-500">Tidak ada pengguna yang ditemukan</p>
                    @if(request('search'))
                        <a href="{{ route('leaderboard') }}" class="text-emerald-600 font-bold mt-2 inline-block hover:underline">Reset Pencarian</a>
                    @endif
                </div>
            @endif
        </div>

        <!-- ATURAN POIN -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        Aturan Poin
                    </h2>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('leaderboard.admin.point_rules') }}" class="p-2 bg-slate-100 text-slate-500 hover:bg-emerald-100 hover:text-emerald-600 rounded-xl transition-all shadow-sm group" title="Kelola Aturan Poin">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:rotate-45 transition-transform"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        </a>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($pointRules as $rule)
                <div class="flex flex-col items-center justify-center p-5 bg-gradient-to-b from-slate-50 to-slate-100 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all group">
                    <div class="w-12 h-12 mb-3 text-emerald-500 group-hover:scale-110 transition-transform flex items-center justify-center bg-white rounded-full shadow-sm border border-emerald-100">
                        @if($rule['activity'] == 'Lapor Isu Lingkungan')
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>
                        @elseif($rule['activity'] == 'Ikuti event Lingkungan')
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        @elseif($rule['activity'] == 'Laporan Diverifikasi')
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 12 2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
                        @endif
                    </div>
                    <span class="text-[10px] sm:text-xs font-bold text-slate-600 text-center uppercase tracking-wider h-8 flex items-center justify-center mb-1">{{ $rule['activity'] }}</span>
                    <span class="font-black text-lg text-emerald-600 bg-emerald-100/50 px-3 py-1 rounded-xl whitespace-nowrap text-sm sm:text-lg">+{{ $rule['points'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- LENCANA & PENCAPAIAN -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-100">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-4">
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        Lencana & Pencapaian
                    </h2>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('leaderboard.admin.badges') }}" class="p-2 bg-slate-100 text-slate-500 hover:bg-emerald-100 hover:text-emerald-600 rounded-xl transition-all shadow-sm group" title="Kelola Lencana">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:rotate-45 transition-transform"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                        </a>
                    @endif
                </div>
                @if(Auth::check() && count($badges) > 0)
                    <a href="{{ route('badges.index') }}" class="text-sm font-bold text-emerald-600 hover:text-emerald-700 hover:underline">Lihat Semua Lencana &rarr;</a>
                @endif
            </div>
            
            @if(Auth::check() && count($badges) > 0)
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    @foreach($badges as $badge)
                        <div class="p-5 bg-[#0f172a] rounded-xl border border-slate-700 shadow-md relative overflow-hidden group hover:border-emerald-500 transition-colors flex flex-col justify-between h-40">
                            <div class="absolute right-0 bottom-0 opacity-5 transform translate-x-4 translate-y-4 group-hover:scale-110 transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                            </div>
                            <div class="relative z-10 flex-grow">
                                <h3 class="font-bold text-white text-base mb-1 flex items-center gap-2 line-clamp-1">
                                    {{ $badge->name }}
                                </h3>
                                <p class="text-[10px] text-slate-400 mb-2 line-clamp-2">{{ $badge->description }}</p>
                            </div>
                            <div class="relative z-10 mt-auto">
                                <div class="w-full bg-slate-800 rounded-full h-2 mb-2 overflow-hidden border border-slate-700">
                                    <div class="h-full transition-all duration-1000 @if($badge->progress_percentage >= 100) bg-emerald-400 shadow-[0_0_10px_rgba(52,211,153,0.5)] @else bg-emerald-500 @endif" style="width: {{ $badge->progress_percentage }}%;"></div>
                                </div>
                                <div class="flex justify-between items-center text-[10px] font-bold text-slate-400">
                                    <span class="uppercase tracking-wider">Progress</span>
                                    <span class="text-white">{{ (int)$badge->current_progress }}/{{ $badge->target }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-slate-500 text-sm font-medium bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><line x1="9" x2="15" y1="9" y2="9"/><line x1="9" x2="15" y1="13" y2="13"/><line x1="9" x2="11" y1="17" y2="17"/></svg>
                    Belum ada lencana yang tersedia atau silakan Login untuk melihat progress pencapaian Anda
                </div>
            @endif
        </div>
        
        <!-- POSISI USER SAAT INI (Paling Bawah) -->
        @auth
            @if(auth()->user()->role !== 'admin')
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
                    <p class="text-sm font-bold text-emerald-100 mt-6 bg-black/10 inline-flex items-center gap-2 px-4 py-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        Terus berkontribusi untuk naik peringkat!
                    </p>
                </div>
            </div>
            @endif
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
