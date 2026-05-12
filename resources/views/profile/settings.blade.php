@extends('layouts.app')

@section('title', 'Pengaturan Profil - GreenTour')

@section('content')

<div class="mx-auto max-w-5xl py-8 px-2">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Pengaturan Profil</h1>
        <a href="{{ route('profile.index') }}" class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Kembali ke Profil</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Kiri: Form Card -->
        <div class="bg-white rounded-2xl shadow p-8 flex flex-col gap-6">
            <div class="mb-2 flex items-center gap-2 text-emerald-700 font-semibold">
                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118A7.5 7.5 0 0112 15.75a7.5 7.5 0 017.5 4.368'/></svg> Informasi Profil
            </div>
            <form action="{{ route('profile.settings.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
                @csrf
                <div>
                    <label class="block text-xs font-semibold mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-lg border border-slate-200 px-4 py-2 text-slate-900 focus:ring-emerald-200 focus:border-emerald-400" />
                    <span class="text-xs text-slate-400">Nama ini akan ditampilkan di profil Anda.</span>
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1">Level Eco</label>
                    <div class="flex items-center gap-2">
                        <input type="text" value="{{ $user->eco_level }}" readonly class="w-full rounded-lg border border-slate-200 px-4 py-2 bg-slate-50 text-slate-900" />
                        <button type="button" id="guideButton" class="text-xs font-semibold text-toscagreen hover:underline">Lihat Panduan</button>
                    </div>
                    <span class="text-xs text-slate-400">Level ini menunjukkan kontribusi Anda terhadap lingkungan.</span>
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1">Foto Profil</label>
                    <div class="flex items-center gap-4">
                        <div class="relative h-16 w-16 rounded-full border-2 border-emerald-200 bg-emerald-50 flex items-center justify-center text-2xl font-bold text-toscagreen">
                            @if($user->photo)
                                <img src="{{ asset('storage/'.$user->photo) }}" class="h-full w-full object-cover rounded-full" />
                            @else
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            @endif
                        </div>
                        <label for="photoInput" class="flex items-center gap-2 cursor-pointer rounded-lg bg-toscagreen px-4 py-2 text-white text-sm font-semibold shadow hover:bg-emerald-700 transition">
                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M12 17v-6m0 0V7m0 4h4m-4 0H8'/></svg> Pilih Foto
                            <input type="file" name="photo" id="photoInput" accept="image/*" class="hidden" />
                        </label>
                    </div>
                </div>
                <div class="flex justify-end pt-4 border-t border-slate-100">
                    <button type="submit" class="rounded-lg bg-toscagreen px-6 py-2 text-white font-semibold text-sm shadow hover:bg-emerald-700 transition">Simpan Perubahan</button>
                </div>
            </form>
        </div>
        <!-- Kanan: Preview Card -->
        <div class="bg-emerald-50 rounded-2xl shadow p-8 flex flex-col items-center gap-4">
            <div class="mb-2 flex items-center gap-2 text-emerald-700 font-semibold">
                <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-5 h-5'><path stroke-linecap='round' stroke-linejoin='round' d='M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118A7.5 7.5 0 0112 15.75a7.5 7.5 0 017.5 4.368'/></svg> Preview Profil
            </div>
            <div class="h-20 w-20 rounded-full border-2 border-emerald-400 bg-white flex items-center justify-center text-3xl font-bold text-toscagreen mb-2">
                @if($user->photo)
                    <img src="{{ asset('storage/'.$user->photo) }}" class="h-full w-full object-cover rounded-full" />
                @else
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                @endif
            </div>
            <div class="text-lg font-bold text-slate-900">{{ $user->name }}</div>
            <div class="flex items-center gap-2">
                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-toscagreen">{{ $user->eco_level }}</span>
            </div>
            <div class="text-xs text-slate-500 text-center">Terus tingkatkan kontribusi Anda untuk mendukung keberlanjutan lingkungan.</div>
            <div class="flex justify-between w-full mt-4 text-center">
                <div class="flex-1">
                    <div class="text-lg font-bold text-toscagreen">{{ number_format($totalPoints) }}</div>
                    <div class="text-xs text-slate-500">Poin Eco</div>
                </div>
                <div class="flex-1">
                    <div class="text-lg font-bold text-toscagreen">{{ $user->eco_level }}</div>
                    <div class="text-xs text-slate-500">Level Saat Ini</div>
                </div>
                <div class="flex-1">
                    <div class="text-lg font-bold text-toscagreen">{{ $participatedEventsCount }}</div>
                    <div class="text-xs text-slate-500">Aksi Selesai</div>
                </div>
            </div>
            <div class="mt-4 w-full bg-white rounded-lg p-3 text-xs text-slate-600 border border-emerald-100">
                <b>Tips:</b> Lengkapi profil Anda untuk pengalaman terbaik dan rekomendasi personal.
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
            <p>Dengan catatan aksi dan laporan, poin eco akan meningkat. Hak akses admin sudah dipisah di backend, sehingga marker hanya dapat ditambahkan oleh admin.</p>
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
