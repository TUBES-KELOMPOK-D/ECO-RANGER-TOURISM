@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl relative">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-2xl p-8 mb-8 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-3xl font-bold mb-2 font-inter">Voucher Eksklusif Wisata</h1>
                    <p class="text-emerald-100 font-inter">Klaim voucher menarik khusus untuk Top 3 Leaderboard Eco Ranger!</p>
                </div>
                <a href="{{ route('leaderboard') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-white/20 hover:bg-white/30 backdrop-blur-md text-white rounded-xl font-bold transition-all border border-white/30 shadow-sm self-start md:self-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Kembali ke Peringkat
                </a>
            </div>
            
            <div class="flex items-center gap-4 bg-white/20 backdrop-blur-sm rounded-xl p-4 inline-flex border border-white/10">
                <div class="text-3xl font-bold">
                    @if($userRank && $userRank <= 3)
                        <span class="text-yellow-300">Rank #{{ $userRank }}</span>
                    @else
                        <span class="text-white/80">#{{ $userRank ?? '?' }}</span>
                    @endif
                </div>
                <div class="h-10 w-px bg-white/30"></div>
                <div class="text-sm text-emerald-50 font-medium max-w-[200px]">
                    @if($userRank && $userRank <= 3)
                        Selamat! Anda berhak mengklaim voucher khusus peringkat {{ $userRank }}.
                    @else
                        Masuk 3 besar papan peringkat untuk mendapatkan akses voucher eksklusif!
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Decorative SVG background -->
        <div class="absolute right-0 top-0 opacity-5 transform translate-x-1/4 -translate-y-1/4">
            <svg xmlns="http://www.w3.org/2000/svg" width="400" height="400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-sm flex items-center gap-3 font-bold animate-pulse">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-700 border border-red-200 shadow-sm flex items-center gap-3 font-bold">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            {{ session('error') }}
        </div>
    @endif

    @if($vouchers->isEmpty())
        <div class="text-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm">
            <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Voucher</h3>
            <p class="text-slate-500 font-medium">Saat ini belum ada voucher yang tersedia untuk diklaim.</p>
        </div>
    @else
        <!-- Voucher Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($vouchers as $voucher)
            @php
                $isEligible = $userRank && $userRank == $voucher->id;
                $hasClaimed = isset($userVouchers[$voucher->id]);
            @endphp
            <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden hover:shadow-2xl transition-all duration-500 flex flex-col h-full group {{ !$isEligible && !$hasClaimed ? 'opacity-60 grayscale-[50%]' : '' }}">
                
                <div class="h-48 bg-slate-50 flex items-center justify-center relative overflow-hidden">
                    @if($voucher->image_path)
                        <img src="{{ asset('storage/' . $voucher->image_path) }}" alt="{{ $voucher->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="text-emerald-100 absolute z-10 transform scale-150 opacity-20 group-hover:rotate-12 transition-transform duration-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/></svg>
                        </div>
                        <div class="text-emerald-600 relative z-20">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                    @endif
                    
                    <div class="absolute top-4 right-4 px-4 py-1.5 bg-white/90 backdrop-blur-sm rounded-full text-[10px] font-black text-emerald-700 shadow-sm uppercase tracking-widest border border-emerald-100">
                        {{ $voucher->kategori }}
                    </div>
                    
                    @if($hasClaimed)
                        <div class="absolute top-4 left-4 px-4 py-1.5 bg-emerald-500 rounded-full text-[10px] font-black text-white shadow-sm uppercase tracking-widest flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            Milik Anda
                        </div>
                    @endif
                </div>

                <div class="p-8 flex flex-col flex-grow">
                    <h3 class="text-xl font-black text-slate-800 mb-3 tracking-tight">{{ $voucher->name }}</h3>
                    <p class="text-sm text-slate-500 mb-8 flex-grow leading-relaxed font-medium">{{ $voucher->description }}</p>
                    
                    <div class="flex items-center justify-between mt-auto pt-6 border-t border-slate-50">
                        <div class="flex flex-col">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mb-1">Khusus</span>
                            <span class="text-lg font-black text-emerald-600">
                                Rank #{{ $voucher->id }}
                            </span>
                        </div>
                        
                        <div class="flex gap-2">
                            @if($hasClaimed)
                                <button type="button" onclick="openVoucherModal('{{ $voucher->id }}', '{{ $voucher->name }}', '{{ \Carbon\Carbon::parse($userVouchers[$voucher->id]->redeemed_at)->format('d M Y, H:i') }}', '{{ $userVouchers[$voucher->id]->status }}')" class="px-6 py-3 rounded-2xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 shadow-lg shadow-emerald-500/20 transition-all flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                    Lihat Bukti
                                </button>
                            @elseif(!$isEligible)
                                <button disabled class="px-6 py-3 rounded-2xl bg-slate-100 text-slate-400 font-bold text-xs cursor-not-allowed border border-slate-200 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    Terkunci
                                </button>
                            @else
                                <form action="{{ route('vouchers.claim', $voucher->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-6 py-3 rounded-2xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 shadow-lg shadow-emerald-500/20 hover:-translate-y-1 transition-all active:translate-y-0">
                                        Klaim Sekarang
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Modal Bukti Klaim -->
<div id="voucherModal" class="fixed inset-0 z-[150] hidden items-center justify-center">
    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-md" onclick="closeVoucherModal()"></div>
    <div class="bg-white rounded-[2.5rem] shadow-2xl relative z-10 w-full max-w-md mx-4 overflow-hidden transform scale-95 opacity-0 transition-all duration-500" id="voucherModalContent">
        
        <!-- Modal Header -->
        <div class="bg-slate-900 p-8 text-white text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
            <button onclick="closeVoucherModal()" class="absolute top-6 right-6 w-10 h-10 flex items-center justify-center rounded-2xl bg-white/10 hover:bg-white/20 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
            <h2 class="text-2xl font-black mb-1 tracking-tight">Bukti Klaim</h2>
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Eco Ranger Tourism</p>
        </div>

        <!-- Modal Body -->
        <div class="p-10">
            <div class="text-center mb-8">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.3em] mb-2">Voucher Reward</p>
                <h3 id="modalVoucherName" class="text-xl font-black text-slate-800 tracking-tight">Voucher Name</h3>
            </div>
            
            <!-- Barcode Placeholder -->
            <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl p-8 text-center mb-8">
                <div class="flex flex-col items-center justify-center gap-4">
                    <div class="w-full h-16 bg-slate-900 rounded-xl flex gap-1 items-center justify-center overflow-hidden px-6" id="barcodePattern"></div>
                    <p class="text-slate-900 font-black tracking-[0.4em] text-lg">ECO-<span id="modalVoucherId"></span></p>
                </div>
            </div>

            <!-- Detail Info -->
            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Tanggal Klaim</p>
                    <p id="modalClaimDate" class="text-xs font-black text-slate-800">01 Jan 2026</p>
                </div>
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Status</p>
                    <p id="modalStatus" class="text-xs font-black text-emerald-600 uppercase">Tersedia</p>
                </div>
            </div>

            <!-- S&K -->
            <div class="mb-10 p-6 bg-emerald-50 rounded-2xl border border-emerald-100">
                <p class="text-xs font-black text-emerald-800 mb-3 flex items-center gap-2 uppercase tracking-wider">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                    Ketentuan
                </p>
                <ul class="text-[10px] text-emerald-700/80 space-y-2 list-disc pl-4 font-bold">
                    <li>Tunjukkan bukti ini kepada petugas wisata.</li>
                    <li>Berlaku untuk 1 (satu) kali penukaran.</li>
                    <li>Masa berlaku 30 hari sejak tanggal klaim.</li>
                </ul>
            </div>
            
            <div id="useVoucherContainer">
                <form id="useVoucherForm" action="" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-black shadow-xl shadow-emerald-500/30 hover:bg-emerald-700 hover:-translate-y-1 transition-all text-xs uppercase tracking-widest flex items-center justify-center gap-2" onclick="return confirm('Gunakan voucher sekarang?');">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Gunakan Voucher
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openVoucherModal(id, name, date, status) {
        document.getElementById('modalVoucherName').innerText = name;
        document.getElementById('modalVoucherId').innerText = id;
        document.getElementById('modalClaimDate').innerText = date;
        
        let statusEl = document.getElementById('modalStatus');
        statusEl.innerText = status;
        
        let useForm = document.getElementById('useVoucherForm');
        useForm.action = `/vouchers/${id}/use`;
        
        if (status === 'digunakan') {
            statusEl.className = "text-xs font-black text-slate-400 uppercase";
            document.getElementById('useVoucherContainer').innerHTML = `
                <div class="w-full py-4 bg-slate-100 text-slate-400 border border-slate-200 rounded-2xl font-black text-xs uppercase tracking-widest flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    Sudah Digunakan
                </div>
            `;
        } else {
            statusEl.className = "text-xs font-black text-emerald-600 uppercase";
        }
        
        // Generate barcode lines
        let barcodeHtml = '';
        for(let i=0; i<15; i++) {
            let width = Math.floor(Math.random() * 5) + 1;
            barcodeHtml += `<div class="bg-white/80 h-full" style="width: ${width}px;"></div>`;
            barcodeHtml += `<div class="h-full" style="width: ${Math.floor(Math.random() * 3) + 1}px;"></div>`;
        }
        document.getElementById('barcodePattern').innerHTML = barcodeHtml;

        const modal = document.getElementById('voucherModal');
        const content = document.getElementById('voucherModalContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeVoucherModal() {
        const modal = document.getElementById('voucherModal');
        const content = document.getElementById('voucherModalContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }
</script>
@endsection
