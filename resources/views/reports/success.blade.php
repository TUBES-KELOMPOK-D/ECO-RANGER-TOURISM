@extends('layouts.app')

@section('title', 'Laporan Terkirim')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-20 sm:py-24">
    <div class="rounded-[40px] bg-white p-10 text-center shadow-soft">
        <div class="mx-auto flex h-28 w-28 items-center justify-center rounded-3xl bg-emerald-100 text-emerald-700 mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5" /></svg>
        </div>
        <h1 class="text-4xl font-extrabold text-slate-900">Berhasil Terkirim!</h1>
        <p class="mt-4 text-slate-600">Terima kasih, Eco-Ranger! Laporanmu sangat berharga bagi kelestarian alam kita.</p>
        @auth
            <p class="mt-3 text-sm text-emerald-700 font-semibold">Kamu mendapatkan +10 Poin Dasar.</p>
        @else
            <p class="mt-3 text-sm text-slate-500">Kamu mengirim sebagai tamu, tanpa poin pada saat ini.</p>
        @endauth
        <div class="mt-10">
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-8 py-4 text-sm font-bold text-white shadow-lg shadow-slate-200 hover:bg-slate-800 transition">Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection
