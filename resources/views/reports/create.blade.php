@extends('layouts.app')

@section('title', 'Eco-Reporter')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10 sm:py-16">
    <div class="bg-white rounded-[40px] shadow-soft overflow-hidden">
        <div class="flex flex-col gap-8 p-8 lg:p-10">
            <div class="rounded-[32px] bg-emerald-50 p-8 flex flex-col gap-8">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-3xl bg-white border border-emerald-100 flex items-center justify-center text-emerald-700 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10.5V18a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-7.5"/><path d="M16 3H8a2 2 0 0 0-2 2v5h12V5a2 2 0 0 0-2-2Z"/><path d="M8 12.5H6.5a1.5 1.5 0 0 0 0 3H8"/><path d="M16 12.5h1.5a1.5 1.5 0 0 1 0 3H16"/></svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900">Eco-Reporter</h1>
                        <p class="mt-2 text-slate-600 max-w-xl">Menemukan kerusakan atau penumpukan sampah? Laporkan untuk tindakan segera.</p>
                    </div>
                </div>

                <div class="rounded-[32px] border-2 border-dashed border-emerald-300 bg-white/80 p-6 flex flex-col items-center justify-center gap-4 text-center min-h-[320px]">
                    <div id="previewWrapper" class="w-full h-full flex flex-col items-center justify-center text-slate-500">
                        <div class="w-20 h-20 rounded-3xl bg-emerald-100 text-emerald-700 flex items-center justify-center shadow-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h4l2-3h4l2 3h4v12H4z"/><path d="M12 11v6"/><path d="M9 14h6"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">Ambil atau Unggah Foto</p>
                            <p class="mt-1 text-sm text-slate-500">Preview akan muncul sebelum submit.</p>
                        </div>
                        <button type="button" onclick="document.getElementById('photoInput').click()" class="mt-4 inline-flex items-center gap-2 rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition">
                            Pilih Foto
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="ecoReporterForm">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Judul Laporan</label>
                        <input name="title" type="text" value="{{ old('title') }}" placeholder="Contoh: Tumpukan Sampah Plastik" class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm text-slate-900 outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100" />
                        @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Lokasi Kejadian</label>
                        <button type="button" onclick="saveDraftAndNavigateToMap()" class="w-full text-left rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm text-slate-700 flex items-center gap-3 hover:border-emerald-300 transition">
                            <span class="inline-flex w-10 h-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 1 1 18 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            </span>
                            <span id="locationLabel">{{ $selectedLocation ? $selectedLocation : 'Pilih lokasi di peta' }}</span>
                        </button>
                        @error('latitude')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        @error('longitude')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $latitude) }}" />
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $longitude) }}" />

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori Masalah</label>
                        <select name="category" class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm text-slate-900 outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100">
                            <option value="Masalah Laut" {{ old('category') === 'Masalah Laut' ? 'selected' : '' }}>Masalah Laut</option>
                            <option value="Masalah Darat" {{ old('category') === 'Masalah Darat' ? 'selected' : '' }}>Masalah Darat</option>
                            <option value="Masalah Lingkungan" {{ old('category') === 'Masalah Lingkungan' ? 'selected' : '' }}>Masalah Lingkungan</option>
                        </select>
                        @error('category')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Keterangan Tambahan</label>
                        <textarea name="description" rows="5" placeholder="Jelaskan apa yang kamu temukan..." class="w-full rounded-3xl border border-slate-200 bg-slate-50 px-5 py-4 text-sm text-slate-900 outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100">{{ old('description') }}</textarea>
                        @error('description')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex flex-col gap-3">
                        <label class="block text-sm font-semibold text-slate-700">Bukti Foto</label>
                        <input type="file" name="photo" id="photoInput" accept="image/*" class="hidden" />
                        <div id="photoInfo" class="rounded-3xl border border-dashed border-slate-300 bg-slate-50 px-5 py-6 text-sm text-slate-500">Tidak ada file dipilih.</div>
                        @error('photo')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="w-full rounded-3xl bg-emerald-600 px-6 py-4 text-base font-bold text-white shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition">Kirim Laporan</button>
                </form>

                <div class="rounded-3xl bg-emerald-50 border border-emerald-100 p-5 text-sm text-emerald-800">
                    @auth
                        Laporan akan disimpan, dan kamu akan mendapatkan +10 poin jika sudah login.
                    @else
                        Kamu dapat mengirim laporan sebagai tamu. Login untuk mendapatkan poin saat submit.
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const storageKey = 'ecoReporterDraft';
    const photoInput = document.getElementById('photoInput');
    const photoInfo = document.getElementById('photoInfo');
    const titleField = document.querySelector('[name="title"]');
    const categoryField = document.querySelector('[name="category"]');
    const descriptionField = document.querySelector('[name="description"]');
    const latitudeField = document.getElementById('latitude');
    const longitudeField = document.getElementById('longitude');
    const locationLabel = document.getElementById('locationLabel');
    const form = document.getElementById('ecoReporterForm');

    function loadDraft() {
        const saved = localStorage.getItem(storageKey);
        if (!saved) return;

        try {
            const draft = JSON.parse(saved);
            if (draft.title && !titleField.value) titleField.value = draft.title;
            if (draft.category && !categoryField.value) categoryField.value = draft.category;
            if (draft.description && !descriptionField.value) descriptionField.value = draft.description;
            if (draft.latitude && !latitudeField.value) latitudeField.value = draft.latitude;
            if (draft.longitude && !longitudeField.value) longitudeField.value = draft.longitude;
            if (draft.location && locationLabel) {
                locationLabel.textContent = draft.location;
            }
        } catch (error) {
            console.warn('Gagal memuat draft Eco-Reporter:', error);
        }
    }

    function saveDraft() {
        const draft = {
            title: titleField.value || '',
            category: categoryField.value || '',
            description: descriptionField.value || '',
            latitude: latitudeField.value || '',
            longitude: longitudeField.value || '',
            location: locationLabel ? locationLabel.textContent : ''
        };
        localStorage.setItem(storageKey, JSON.stringify(draft));
    }

    function clearDraft() {
        localStorage.removeItem(storageKey);
    }

    function saveDraftAndNavigateToMap() {
        saveDraft();
        const returnTo = '{{ route('reports.create') }}';
        window.location.href = '/?select_location=1&return_to=' + encodeURIComponent(returnTo);
    }

    photoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) {
            photoInfo.innerHTML = 'Tidak ada file dipilih.';
            return;
        }

        if (!file.type.startsWith('image/')) {
            photoInfo.innerHTML = 'Format file tidak valid. Mohon pilih gambar.';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(event) {
            photoInfo.innerHTML = `
                <div class="flex items-center gap-3">
                    <img src="${event.target.result}" alt="Preview Foto" class="h-20 w-20 rounded-3xl object-cover border border-slate-200" />
                    <div>
                        <div class="font-semibold text-slate-900">${file.name}</div>
                        <div class="text-slate-500 text-sm">${(file.size / 1024).toFixed(1)} KB</div>
                    </div>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    });

    titleField.addEventListener('input', saveDraft);
    categoryField.addEventListener('change', saveDraft);
    descriptionField.addEventListener('input', saveDraft);

    form.addEventListener('submit', function() {
        clearDraft();
    });

    document.addEventListener('DOMContentLoaded', function() {
        loadDraft();
    });
</script>
@endpush
