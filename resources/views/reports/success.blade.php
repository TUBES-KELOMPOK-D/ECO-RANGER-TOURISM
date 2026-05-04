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
            <div class="mt-10">
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-200 hover:bg-slate-800 transition">Kembali ke Beranda</a>
            </div>
        @else
            <p class="mt-3 text-sm text-slate-500">Kamu mengirim sebagai tamu. Daftar akun untuk mendapatkan poin pada laporan berikutnya.</p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition">Daftar Akun</a>
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-900 shadow-lg shadow-slate-100 hover:bg-slate-50 transition">Masuk</a>
            </div>
            <div class="mt-6">
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-200 hover:bg-slate-800 transition">Kembali ke Beranda</a>
            </div>
        @endauth
    </div>
</div>
@endsection
