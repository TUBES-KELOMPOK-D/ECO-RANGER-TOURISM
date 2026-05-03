@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl relative">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 rounded-2xl p-8 mb-8 text-white shadow-lg relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl font-bold mb-2 font-inter">Voucher Eksklusif Wisata Eco Ranger</h1>
            <p class="text-emerald-100 mb-6 font-inter">Klaim voucher wisata menarik khusus untuk Top 3 Leaderboard!</p>
            
            <div class="flex items-center gap-4 bg-white/20 backdrop-blur-sm rounded-xl p-4 inline-flex">
                <div class="text-3xl font-bold mb-1">
                    @if($userRank && $userRank <= 3)
                        <span class="text-yellow-300">Rank #{{ $userRank }}</span>
                    @else
                        <span>{{ $userRank ? 'Rank #' . $userRank : 'Belum Ada Rank' }}</span>
                    @endif
                </div>
                <div class="h-10 w-px bg-white/30"></div>
                <div class="text-sm text-emerald-100 font-semibold max-w-[200px]">
                    @if($userRank && $userRank <= 3)
                        Selamat! Anda bisa mengklaim voucher khusus Rank {{ $userRank }} di bawah ini.
                    @else
                        Tingkatkan rank Anda menjadi top 3 untuk bisa mengklaim voucher!
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Decorative icon background -->
        <div class="absolute right-0 top-0 opacity-10 text-[15rem] leading-none transform translate-x-1/4 -translate-y-1/4">
            🎟️
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 border border-green-200 shadow-sm flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-700 border border-red-200 shadow-sm flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Navigation Tab Back to Leaderboard/Badges -->
    <div class="mb-8 flex gap-4">
        <a href="{{ route('leaderboard') }}" class="px-5 py-2.5 rounded-full font-bold text-sm transition-all bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 shadow-sm decoration-transparent flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Kembali ke Peringkat
        </a>
    </div>

    @if($vouchers->isEmpty())
        <div class="text-center py-12 bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="text-6xl mb-4">🎫</div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Voucher</h3>
            <p class="text-slate-500">Saat ini belum ada voucher yang tersedia untuk diklaim.</p>
        </div>
    @else
        <!-- Voucher Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($vouchers as $voucher)
            @php
                // Logika: Voucher id 1 untuk rank 1, id 2 untuk rank 2, id 3 untuk rank 3
                $isEligible = $userRank && $userRank == $voucher->id;
                $hasClaimed = isset($userVouchers[$voucher->id]);
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col h-full {{ !$isEligible && !$hasClaimed ? 'opacity-80 grayscale-[30%]' : '' }}">
                
                <div class="h-40 bg-slate-100 flex items-center justify-center relative overflow-hidden">
                    @if($voucher->image_path)
                        <img src="{{ asset('storage/' . $voucher->image_path) }}" alt="{{ $voucher->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-6xl absolute z-10 transform scale-150 opacity-20">🎟️</div>
                        <div class="text-5xl relative z-20">🏝️</div>
                    @endif
                    
                    <div class="absolute top-4 right-4 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-bold text-emerald-700 shadow-sm uppercase tracking-wide border border-emerald-100">
                        {{ $voucher->kategori }}
                    </div>
                    
                    @if($hasClaimed)
                        <div class="absolute top-4 left-4 px-3 py-1 bg-yellow-500 rounded-full text-xs font-bold text-white shadow-sm uppercase tracking-wide flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                            Milik Anda
                        </div>
                    @endif
                </div>

                <div class="p-6 flex flex-col flex-grow">
                    <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $voucher->name }}</h3>
                    <p class="text-sm text-slate-600 mb-6 flex-grow">{{ $voucher->description }}</p>
                    
                    <div class="flex items-center justify-between mt-auto">
                        <div class="flex flex-col">
                            <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Khusus</span>
                            <span class="text-lg font-bold text-emerald-600">
                                Rank {{ $voucher->id }}
                            </span>
                        </div>
                        
                        <div class="flex gap-2">
                            @if($hasClaimed)
                                <button type="button" onclick="openVoucherModal('{{ $voucher->id }}', '{{ $voucher->name }}', '{{ \Carbon\Carbon::parse($userVouchers[$voucher->id]->redeemed_at)->format('d M Y, H:i') }}', '{{ $userVouchers[$voucher->id]->status }}')" class="px-5 py-2.5 rounded-full bg-emerald-600 text-white font-bold text-sm hover:bg-emerald-700 shadow-md transition-all flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                    Lihat Bukti & S&K
                                </button>
                            @elseif(!$isEligible)
                                <button disabled class="px-5 py-2.5 rounded-full bg-slate-100 text-slate-400 font-bold text-sm cursor-not-allowed border border-slate-200 flex items-center gap-2" title="Hanya untuk Rank {{ $voucher->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    Terkunci
                                </button>
                            @else
                                <form action="{{ route('vouchers.claim', $voucher->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-5 py-2.5 rounded-full bg-emerald-600 text-white font-bold text-sm hover:bg-emerald-700 shadow-md hover:shadow-lg transition-all hover:-translate-y-0.5 active:translate-y-0">
                                        Klaim Voucher
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

<!-- Modal Bukti Klaim & S&K -->
<div id="voucherModal" class="fixed inset-0 z-[150] hidden items-center justify-center">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeVoucherModal()"></div>
    <div class="bg-white rounded-[2rem] shadow-2xl relative z-10 w-full max-w-md mx-4 overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="voucherModalContent">
        
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-toscagreen p-6 text-white text-center relative">
            <button onclick="closeVoucherModal()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
            <h2 class="text-2xl font-bold mb-1">Bukti Klaim Voucher</h2>
            <p class="text-emerald-100 text-sm opacity-90">Eco Ranger Tourism</p>
        </div>

        <!-- Modal Body -->
        <div class="p-8">
            <div class="text-center mb-6">
                <p class="text-sm text-slate-500 font-semibold uppercase tracking-wider mb-1">Nama Voucher</p>
                <h3 id="modalVoucherName" class="text-xl font-bold text-slate-800">Voucher Name</h3>
            </div>
            
            <!-- Barcode Placeholder -->
            <div class="bg-slate-50 border border-dashed border-slate-300 rounded-xl p-6 text-center mb-6">
                <div class="flex flex-col items-center justify-center gap-3">
                    <div class="w-full h-16 bg-slate-800 rounded flex gap-1 items-center justify-center overflow-hidden px-4 opacity-80" id="barcodePattern">
                        <!-- Bars generated by JS -->
                    </div>
                    <p class="text-slate-800 font-mono font-bold tracking-[0.3em] text-lg">ECO-<span id="modalVoucherId"></span>-{{ strtoupper(auth()->user()->name ?? 'USER') }}</p>
                </div>
            </div>

            <!-- Detail Info -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                    <p class="text-xs text-slate-500 font-semibold uppercase">Tgl Klaim</p>
                    <p id="modalClaimDate" class="text-sm font-bold text-slate-800 mt-1">01 Jan 2026</p>
                </div>
                <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                    <p class="text-xs text-slate-500 font-semibold uppercase">Status</p>
                    <p id="modalStatus" class="text-sm font-bold text-emerald-600 mt-1 capitalize">Tersedia</p>
                </div>
            </div>

            <!-- S&K -->
            <div class="mb-6">
                <p class="text-sm font-bold text-slate-800 mb-2 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                    Syarat & Ketentuan
                </p>
                <ul class="text-xs text-slate-600 space-y-2 list-disc pl-4 marker:text-emerald-500">
                    <li>Voucher ini merupakan bukti sah pencapaian Rank Anda di Leaderboard Eco Ranger.</li>
                    <li>Tunjukkan bukti/QR code ini kepada petugas di loket wisata terkait.</li>
                    <li>Voucher hanya berlaku untuk 1 (satu) kali penukaran.</li>
                    <li>Voucher tidak dapat diuangkan atau dipindahtangankan.</li>
                    <li>Masa berlaku voucher adalah 30 hari sejak tanggal klaim.</li>
                </ul>
            </div>
            
            <div id="useVoucherContainer">
                <form id="useVoucherForm" action="" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-yellow-500 text-white rounded-xl font-bold shadow-md shadow-yellow-500/20 hover:bg-yellow-600 hover:-translate-y-0.5 transition-all text-sm flex items-center justify-center gap-2" onclick="return confirm('PENTING: Hanya klik ini jika Anda sedang berada di loket dan diarahkan oleh petugas. Lanjutkan?');">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Gunakan & Tandai Selesai
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
            statusEl.className = "text-sm font-bold text-slate-500 mt-1 capitalize";
            document.getElementById('useVoucherContainer').innerHTML = `
                <div class="w-full py-3 bg-slate-100 text-slate-400 border border-slate-200 rounded-xl font-bold text-sm flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    Sudah Digunakan pada ${date}
                </div>
            `;
        } else {
            statusEl.className = "text-sm font-bold text-emerald-600 mt-1 capitalize";
        }
        
        // Generate random fake barcode lines
        let barcodeHtml = '';
        const patternLength = 20;
        for(let i=0; i<patternLength; i++) {
            let width = Math.floor(Math.random() * 4) + 1;
            barcodeHtml += `<div class="bg-white h-full opacity-90" style="width: ${width}px;"></div>`;
            // space
            let margin = Math.floor(Math.random() * 3);
            barcodeHtml += `<div class="h-full" style="width: ${margin}px;"></div>`;
        }
        document.getElementById('barcodePattern').innerHTML = barcodeHtml;

        const modal = document.getElementById('voucherModal');
        const content = document.getElementById('voucherModalContent');
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Trigger animation
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
