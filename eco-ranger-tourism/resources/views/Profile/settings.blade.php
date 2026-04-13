@extends('layouts.app')

@section('title', 'Pengaturan Profil - GreenTour')

@section('content')
<div class="mt-6 mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="rounded-3xl bg-white p-8 shadow-soft border border-slate-200">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Pengaturan Profil</h1>
                <p class="mt-2 text-sm text-slate-500">Kelola profile, foto, dan mode admin Anda.</p>
            </div>
            <a href="{{ route('profile.index') }}" class="rounded-full border border-slate-200 bg-slate-50 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Kembali ke Profil</a>
        </div>

        @if(session('success'))
            <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-900">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-8 grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="space-y-8">
                <form action="{{ route('profile.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="space-y-3">
                            <label class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4 text-slate-900 shadow-sm outline-none focus:border-toscagreen focus:ring-2 focus:ring-toscagreen/10" placeholder="Andi Saputra" />
                            @error('name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-3">
                            <label class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Level Eco</label>
                            <div class="rounded-3xl border border-emerald-100 bg-slate-50 px-4 py-4 text-slate-900">
                                <div class="flex items-center justify-between gap-4">
                                    <span>{{ $user->eco_level }}</span>
                                    <button type="button" id="guideButton" class="text-sm font-semibold text-toscagreen hover:text-[#1f3f30]">Lihat Panduan</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Mode Admin</p>
                                <p class="mt-1 text-sm text-slate-500">Aktifkan untuk menambah marker lokasi baru dan simpan marker secara permanen di database.</p>
                            </div>
                            <label class="flex items-center gap-3">
                                <input type="checkbox" id="adminToggle" name="mode_admin" class="sr-only" {{ old('mode_admin', session('admin_mode') ? 'checked' : '') ? 'checked' : '' }} />
                                <span class="ios-switch"></span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-400">Foto Profil</label>
                        <div class="flex items-center gap-4">
                            <div id="profilePreview" class="relative h-28 w-28 overflow-hidden rounded-[2rem] border-2 border-toscagreen bg-emerald-50 text-4xl font-black uppercase text-toscagreen shadow-md">
                                <img id="previewImage" src="{{ $user->photo ? asset('storage/'.$user->photo) : '' }}" alt="Foto Profil" class="h-full w-full object-cover {{ $user->photo ? '' : 'hidden' }}" />
                                <div id="previewInitials" class="flex h-full w-full items-center justify-center {{ $user->photo ? 'hidden' : '' }}">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                            </div>
                            <div>
                                <label for="photoInput" class="cursor-pointer rounded-3xl bg-toscagreen px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-200 transition hover:bg-[#244f3f]">Pilih Foto</label>
                                <input type="file" name="photo" id="photoInput" accept="image/*" class="hidden" />
                                <p class="mt-3 text-sm text-slate-500">Upload foto baru untuk profil Anda.</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-3xl bg-[#2d6a4f] px-6 py-4 text-base font-semibold text-white shadow-lg shadow-emerald-200 transition hover:bg-[#244f3f]">Simpan Perubahan</button>
                </form>
            </div>

            <div class="space-y-6">
                @if($user->role === 'admin')
                    <div id="adminMessage" class="rounded-3xl border border-emerald-200 bg-emerald-50 p-6 text-slate-900">
                        <p class="font-semibold text-emerald-800">Akun admin terdeteksi.</p>
                        <p class="mt-2 text-sm text-slate-600">Aktifkan mode admin di atas untuk menambahkan marker baru ke database.</p>
                    </div>
                @endif

                <div id="markerPanel" class="rounded-3xl border border-slate-200 bg-slate-50 p-6 {{ $user->role !== 'admin' || !session('admin_mode') ? 'hidden' : '' }}">
                    <h2 class="text-xl font-semibold text-slate-900">Tambah Marker Lokasi</h2>
                    <form action="{{ route('markers.store') }}" method="POST" class="mt-6 space-y-4">
                        @csrf
                        <div class="grid gap-4 sm:grid-cols-2">
                            <input type="text" name="latitude" placeholder="Latitude" class="rounded-3xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-toscagreen focus:ring-2 focus:ring-toscagreen/10" required>
                            <input type="text" name="longitude" placeholder="Longitude" class="rounded-3xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-toscagreen focus:ring-2 focus:ring-toscagreen/10" required>
                        </div>
                        <textarea name="description" rows="3" placeholder="Deskripsi lokasi" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-toscagreen focus:ring-2 focus:ring-toscagreen/10" required></textarea>
                        <button type="submit" class="w-full rounded-3xl bg-[#2d6a4f] px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-200 transition hover:bg-[#244f3f]">Tambah Marker</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="guideModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4">
    <div class="w-full max-w-xl rounded-3xl bg-white p-6 shadow-soft">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Panduan Level Eco</h2>
                <p class="mt-2 text-sm text-slate-500">Informasi singkat mengenai level Eco dan mengapa Eco-Ranger penting.</p>
            </div>
            <button id="closeGuide" class="rounded-full bg-slate-100 p-2 text-slate-600 transition hover:bg-slate-200">×</button>
        </div>
        <div class="mt-6 space-y-4 text-sm leading-6 text-slate-600">
            <p><strong>Eco-Ranger</strong> adalah level untuk pengguna yang aktif melaporkan isu lingkungan dan mendukung konservasi. Kamu dapat menambah laporan dan ikut pelestarian.</p>
            <p>Dengan catatan aksi dan laporan, poin eco akan meningkat. Gunakan mode admin hanya ketika menambah marker lokasi.</p>
        </div>
        <div class="mt-6 text-right">
            <button id="closeGuideFooter" class="rounded-3xl bg-toscagreen px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#244f3f]">Tutup</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const guideButton = document.getElementById('guideButton');
    const guideModal = document.getElementById('guideModal');
    const closeGuide = document.getElementById('closeGuide');
    const closeGuideFooter = document.getElementById('closeGuideFooter');
    const photoInput = document.getElementById('photoInput');
    const previewImage = document.getElementById('previewImage');
    const previewInitials = document.getElementById('previewInitials');

    guideButton?.addEventListener('click', () => guideModal.classList.remove('hidden'));
    closeGuide?.addEventListener('click', () => guideModal.classList.add('hidden'));
    closeGuideFooter?.addEventListener('click', () => guideModal.classList.add('hidden'));

    photoInput?.addEventListener('change', event => {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            if (previewImage) {
                previewImage.src = e.target.result;
                previewImage.classList.remove('hidden');
            }
            if (previewInitials) {
                previewInitials.classList.add('hidden');
            }
        };
        reader.readAsDataURL(file);
    });
</script>
@endpush
