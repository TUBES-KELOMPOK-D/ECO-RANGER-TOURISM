@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="mt-2 text-gray-600">Selamat datang di panel admin. Pilih fitur untuk dikelola.</p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            <a href="{{ route('users.index') }}" class="block rounded-3xl bg-white p-6 shadow-soft hover:shadow-lg transition">
                <div class="text-sm font-semibold text-gray-500 uppercase tracking-[0.16em]">User Management</div>
                <div class="mt-4 text-2xl font-semibold text-gray-900">Kelola User</div>
                <p class="mt-3 text-gray-600">Lihat, tambah, edit, dan hapus akun pengguna.</p>
            </a>

            <a href="{{ route('markers.index') }}" class="block rounded-3xl bg-white p-6 shadow-soft hover:shadow-lg transition">
                <div class="text-sm font-semibold text-gray-500 uppercase tracking-[0.16em]">Marker Management</div>
                <div class="mt-4 text-2xl font-semibold text-gray-900">Kelola Marker</div>
                <p class="mt-3 text-gray-600">Buat dan edit lokasi serta marker dalam sistem.</p>
            </a>

            <a href="{{ route('admin.reports.index') }}" class="block rounded-3xl bg-white p-6 shadow-soft hover:shadow-lg transition">
                <div class="text-sm font-semibold text-gray-500 uppercase tracking-[0.16em]">Laporan Admin</div>
                <div class="mt-4 text-2xl font-semibold text-gray-900">Kelola Laporan</div>
                <p class="mt-3 text-gray-600">Review dan kelola laporan yang masuk.</p>
            </a>
        </div>
    </div>
</div>
@endsection
