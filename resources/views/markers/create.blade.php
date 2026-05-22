@extends('layouts.app')

@section('title', 'Tambah Marker Baru')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">

    {{-- Back Button --}}
    <a href="/" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-slate-800 transition mb-6 decoration-transparent">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Kembali ke Peta
    </a>

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
        </div>
        <div>
            <h1 class="text-2xl font-black text-slate-900">Tambah Marker Baru</h1>
            <p class="text-sm text-slate-500">Input data marker, polygon, atau polyline ke dalam sistem.</p>
        </div>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl mb-6 font-semibold text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('markers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Tipe Entri Selector --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-1">Tipe Entri</h2>
            <p class="text-xs text-slate-400 mb-4">Pilih tujuan marker — menentukan tampilan dan form yang tersedia.</p>
            <div class="grid grid-cols-2 gap-3" id="entry-type-picker">
                <button type="button" id="btn-wisata" onclick="setEntryType('wisata')"
                    class="entry-type-btn active-entry flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-emerald-500 bg-emerald-50 transition-all">
                    <span class="text-2xl">📍</span>
                    <span class="text-sm font-bold text-emerald-700">Destinasi Wisata</span>
                    <span class="text-xs text-emerald-600 text-center">Pinpoint lokasi wisata alam</span>
                </button>
                <button type="button" id="btn-lingkungan" onclick="setEntryType('lingkungan')"
                    class="entry-type-btn flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-slate-200 bg-slate-50 transition-all">
                    <span class="text-2xl">🌿</span>
                    <span class="text-sm font-bold text-slate-600">Kondisi Lingkungan</span>
                    <span class="text-xs text-slate-500 text-center">Area/garis kondisi ekologi</span>
                </button>
            </div>
            {{-- Hidden field untuk category --}}
            <input type="hidden" name="category" id="category-input" value="Destinasi Wisata">
        </div>

        {{-- Basic Info Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-5">Informasi Dasar</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Marker</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm" placeholder="Nama marker">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lokasi</label>
                    <input type="text" name="location_name" value="{{ old('location_name') }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm" placeholder="Misal: Muara Gembong, Jawa Barat">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm resize-none" placeholder="Deskripsi lokasi...">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                        <select name="status" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm">
                            <option value="green" {{ old('status') === 'green' ? 'selected' : '' }}>🟢 Aman (Hijau)</option>
                            <option value="yellow" {{ old('status') === 'yellow' ? 'selected' : '' }}>🟡 Waspada (Kuning)</option>
                            <option value="red" {{ old('status') === 'red' ? 'selected' : '' }}>🔴 Bahaya (Merah)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori</label>
                        <select name="category" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm">
                            <option value="">— Pilih —</option>
                            <option value="Sangat Terjaga" {{ old('category') === 'Sangat Terjaga' ? 'selected' : '' }}>Sangat Terjaga</option>
                            <option value="Terjaga" {{ old('category') === 'Terjaga' ? 'selected' : '' }}>Terjaga</option>
                            <option value="Perlu Perhatian" {{ old('category') === 'Perlu Perhatian' ? 'selected' : '' }}>Perlu Perhatian</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Gambar Lokasi</label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition">
                </div>
            </div>
        </div>

        {{-- Coordinate Info Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-2">Informasi Spasial (Koordinat)</h2>
            <p class="text-xs text-slate-400 mb-5">Gambar bentuk di peta untuk mengisi koordinat secara otomatis.</p>

            <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Bentuk (Shape Type)</label>
                        <select name="shape_type" id="shape_type" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm">
                            <option value="Marker" {{ old('shape_type') === 'Marker' ? 'selected' : '' }}>📍 Marker (Titik)</option>
                            <option value="Polygon" {{ old('shape_type') === 'Polygon' ? 'selected' : '' }} class="env-shape-option">🔷 Polygon (Area Bebas)</option>
                            <option value="Rectangle" {{ old('shape_type') === 'Rectangle' ? 'selected' : '' }} class="env-shape-option">⬛ Rectangle (Area Kotak)</option>
                            <option value="Circle" {{ old('shape_type') === 'Circle' ? 'selected' : '' }} class="env-shape-option">🔵 Circle (Lingkaran)</option>
                            <option value="Polyline" {{ old('shape_type') === 'Polyline' ? 'selected' : '' }} class="env-shape-option">〰️ Polyline (Garis)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Radius (Khusus Circle)</label>
                        <input type="number" step="any" name="radius" id="radius" value="{{ old('radius') }}"
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm disabled:bg-slate-100 disabled:text-slate-400" placeholder="Dalam meter (contoh: 500)">
                    </div>
                </div>

                {{-- Mini Map Picker --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pilih Lokasi di Peta</label>
                    <p class="text-xs text-slate-400 mb-2">
                        Gunakan toolbar di pojok kiri atas peta untuk menggambar bentuk sesuai pilihan. Hanya 1 bentuk yang diizinkan.
                    </p>

                    {{-- Map Container --}}
                    <div id="coord-map" class="w-full rounded-xl border border-slate-300 overflow-hidden" style="height: 380px; z-index: 0;"></div>

                    {{-- Coordinate Preview --}}
                    <div class="mt-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-semibold text-slate-600">Koordinat Terpilih</span>
                            <button type="button" id="clear-shape-btn"
                                class="text-xs text-red-500 hover:text-red-700 font-semibold transition hidden">
                                Hapus Bentuk
                            </button>
                        </div>
                        <code id="coord-preview" class="text-xs text-emerald-700 break-all">
                            <span class="text-slate-400 italic">Belum ada koordinat dipilih...</span>
                        </code>
                    </div>

                    {{-- Hidden inputs submitted with form --}}
                    <input type="hidden" name="coordinates" id="coordinates-input" value="{{ old('coordinates') }}" required>
                </div>
            </div>
        </div>

        {{-- Eco Detail Card — hanya untuk Destinasi Wisata --}}
        <div id="eco-detail-section" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-5">Detail Eco-Health</h2>
            <p class="text-xs text-slate-400 -mt-3 mb-4">Khusus untuk marker Destinasi Wisata.</p>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Eco-Health Score (0–10)</label>
                        <input type="number" name="eco_health_score" value="{{ old('eco_health_score') }}" step="0.1" min="0" max="10"
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm" placeholder="8.5">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kebersihan</label>
                        <input type="text" name="kebersihan" value="{{ old('kebersihan') }}"
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm" placeholder="4/5">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Akses</label>
                        <select name="akses" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm">
                            <option value="">— Pilih —</option>
                            <option value="Mudah" {{ old('akses') === 'Mudah' ? 'selected' : '' }}>Mudah</option>
                            <option value="Sedang" {{ old('akses') === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="Sulit" {{ old('akses') === 'Sulit' ? 'selected' : '' }}>Sulit</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Populasi</label>
                        <select name="populasi" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm">
                            <option value="">— Pilih —</option>
                            <option value="Rendah" {{ old('populasi') === 'Rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="Sedang" {{ old('populasi') === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="Tinggi" {{ old('populasi') === 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Eco Rules Card — hanya untuk Destinasi Wisata --}}
        <div id="eco-rules-section" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-800">Aturan Wisata Hijau</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Tambahkan daftar aturan untuk lokasi ini.</p>
                </div>
                <span id="rules-count-badge" class="hidden text-xs font-bold bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full">0 aturan</span>
            </div>

            {{-- Rule list --}}
            <div id="eco-rules-list" class="space-y-2 mb-4"></div>

            {{-- Add row --}}
            <div class="flex gap-2">
                <input type="text" id="rule-text-input" placeholder="Contoh: Dilarang buang sampah sembarangan"
                    class="flex-1 px-4 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm">
                <select id="rule-type-select"
                    class="px-3 py-2.5 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm font-semibold min-w-[130px]">
                    <option value="prohibited">Dilarang</option>
                    <option value="allowed">Diizinkan</option>
                </select>
                <button type="button" id="add-rule-btn"
                    class="px-4 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-bold hover:bg-emerald-700 transition flex items-center gap-1.5 whitespace-nowrap">
                    + Tambah
                </button>
            </div>

            {{-- Empty state --}}
            <div id="rules-empty" class="mt-3 text-center py-5 border-2 border-dashed border-slate-200 rounded-xl">
                <p class="text-xs text-slate-400">Belum ada aturan. Tambahkan aturan di atas.</p>
            </div>

            {{-- Hidden field, serialized as JSON on submit --}}
            <input type="hidden" name="eco_rules" id="eco-rules-input" value="{{ old('eco_rules') }}">
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-4">
            <button type="submit" class="flex-1 bg-emerald-600 text-white px-6 py-3.5 rounded-xl font-bold text-sm hover:bg-emerald-700 hover:-translate-y-0.5 transition-all shadow-lg shadow-emerald-100 active:translate-y-0">
                Tambah Marker
            </button>
            <a href="/" class="px-6 py-3.5 rounded-xl font-bold text-sm text-slate-500 hover:bg-slate-100 transition border border-slate-200">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/@geoman-io/leaflet-geoman-free@2.16.0/dist/leaflet-geoman.css" />
<style>
    /* Fix leaflet z-index inside form card */
    #coord-map { position: relative; }
    .leaflet-pane, .leaflet-control { z-index: 1 !important; }
    .leaflet-top, .leaflet-bottom { z-index: 2 !important; }

    /* Custom Geoman toolbar style */
    .leaflet-pm-toolbar .leaflet-pm-icon-marker { filter: hue-rotate(130deg); }

    /* Entry type button active state */
    .active-entry {
        border-color: #059669 !important;
        background-color: #f0fdf4 !important;
    }
    .active-entry span.text-sm { color: #065f46 !important; }
</style>
@endpush

@push('scripts')
<script>
/* ─── Entry Type Toggle ────────────────────────────────── */
function setEntryType(type) {
    const categoryInput  = document.getElementById('category-input');
    const shapeSelect    = document.getElementById('shape_type');
    const ecoDetail      = document.getElementById('eco-detail-section');
    const ecoRules       = document.getElementById('eco-rules-section');
    const btnWisata      = document.getElementById('btn-wisata');
    const btnLingkungan  = document.getElementById('btn-lingkungan');

    if (type === 'wisata') {
        // Aktifkan Destinasi Wisata
        categoryInput.value = 'Destinasi Wisata';
        btnWisata.classList.add('active-entry');
        btnWisata.classList.remove('border-slate-200', 'bg-slate-50');
        btnWisata.classList.add('border-emerald-500', 'bg-emerald-50');
        btnLingkungan.classList.remove('active-entry', 'border-emerald-500', 'bg-emerald-50');
        btnLingkungan.classList.add('border-slate-200', 'bg-slate-50');

        // Kunci shape ke Marker
        shapeSelect.value = 'Marker';
        for (let opt of shapeSelect.options) {
            if (opt.classList.contains('env-shape-option')) {
                opt.disabled = true;
                opt.style.display = 'none';
            }
        }

        // Tampilkan eco-health sections
        ecoDetail.style.display = '';
        ecoRules.style.display  = '';

    } else {
        // Aktifkan Kondisi Lingkungan
        categoryInput.value = 'Kondisi Lingkungan';
        btnLingkungan.classList.add('active-entry');
        btnLingkungan.classList.remove('border-slate-200', 'bg-slate-50');
        btnLingkungan.classList.add('border-emerald-500', 'bg-emerald-50');
        btnWisata.classList.remove('active-entry', 'border-emerald-500', 'bg-emerald-50');
        btnWisata.classList.add('border-slate-200', 'bg-slate-50');

        // Semua shape tersedia, pilih Polygon sebagai default
        for (let opt of shapeSelect.options) {
            opt.disabled = false;
            opt.style.display = '';
        }
        shapeSelect.value = 'Polygon';
        shapeSelect.dispatchEvent(new Event('change'));

        // Sembunyikan eco-health sections
        ecoDetail.style.display = 'none';
        ecoRules.style.display  = 'none';
    }

    // Rebuild toolbar peta sesuai shape baru
    if (typeof rebuildToolbar === 'function') rebuildToolbar();
}
</script>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/@geoman-io/leaflet-geoman-free@2.16.0/dist/leaflet-geoman.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ─── Elements ─────────────────────────────────── */
    const shapeSelect      = document.getElementById('shape_type');
    const radiusInput      = document.getElementById('radius');
    const coordInput       = document.getElementById('coordinates-input');
    const coordPreview     = document.getElementById('coord-preview');
    const clearBtn         = document.getElementById('clear-shape-btn');

    /* ─── 1. Radius toggle ──────────────────────────── */
    function toggleRadius() {
        const isCircle = shapeSelect.value === 'Circle';
        radiusInput.disabled = !isCircle;
        radiusInput.required = isCircle;
        if (!isCircle) radiusInput.value = '';
    }
    shapeSelect.addEventListener('change', () => { toggleRadius(); rebuildToolbar(); });
    toggleRadius();

    /* ─── 2. Init Leaflet map ───────────────────────── */
    const map = L.map('coord-map', { center: [-6.5971, 106.8060], zoom: 10 });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    // Fix map sizing after DOM is ready
    setTimeout(() => map.invalidateSize(), 200);

    /* ─── 3. Geoman helper ──────────────────────────── */
    let drawnLayer = null;

    function setCoord(jsonStr) {
        coordInput.value = jsonStr;
        coordPreview.textContent = jsonStr;
        clearBtn.classList.remove('hidden');
    }

    function clearCoord() {
        coordInput.value = '';
        coordPreview.innerHTML = '<span class="text-slate-400 italic">Belum ada koordinat dipilih...</span>';
        clearBtn.classList.add('hidden');
    }

    function removeCurrentLayer() {
        if (drawnLayer) {
            map.removeLayer(drawnLayer);
            drawnLayer = null;
        }
    }

    /* ─── 4. Build Geoman toolbar based on shape type ── */
    function rebuildToolbar() {
        map.pm.removeControls();

        const type = shapeSelect.value;

        const options = {
            position: 'topleft',
            drawMarker:    type === 'Marker',
            drawPolyline:  type === 'Polyline',
            drawPolygon:   type === 'Polygon',
            drawRectangle: type === 'Rectangle',
            drawCircle:    type === 'Circle',
            drawCircleMarker: false,
            drawText:      false,
            editMode:      true,
            dragMode:      false,
            cutPolygon:    false,
            removalMode:   false,
        };

        map.pm.addControls(options);
    }
    rebuildToolbar();

    /* ─── 5. Listen to Geoman create events ────────── */
    map.on('pm:create', function (e) {
        // Remove previous layer (only 1 allowed)
        removeCurrentLayer();
        drawnLayer = e.layer;

        const type = shapeSelect.value;
        let coords;

        if (type === 'Marker') {
            const latlng = e.layer.getLatLng();
            coords = [latlng.lat, latlng.lng];

        } else if (type === 'Circle') {
            const latlng = e.layer.getLatLng();
            const r      = e.layer.getRadius();
            coords = [latlng.lat, latlng.lng];
            // Auto-fill radius field
            radiusInput.value = Math.round(r);

        } else if (type === 'Polyline') {
            coords = e.layer.getLatLngs().map(p => [p.lat, p.lng]);

        } else if (type === 'Polygon' || type === 'Rectangle') {
            // getLatLngs returns array of rings; take outer ring
            const ring = e.layer.getLatLngs()[0];
            coords = ring.map(p => [p.lat, p.lng]);

        } else {
            coords = [];
        }

        setCoord(JSON.stringify(coords));

        // Listen to edit on this layer
        e.layer.on('pm:edit', function () {
            let updated;
            if (type === 'Marker') {
                const latlng = e.layer.getLatLng();
                updated = [latlng.lat, latlng.lng];
            } else if (type === 'Circle') {
                const latlng = e.layer.getLatLng();
                updated = [latlng.lat, latlng.lng];
                radiusInput.value = Math.round(e.layer.getRadius());
            } else if (type === 'Polyline') {
                updated = e.layer.getLatLngs().map(p => [p.lat, p.lng]);
            } else {
                const ring = e.layer.getLatLngs()[0];
                updated = ring.map(p => [p.lat, p.lng]);
            }
            setCoord(JSON.stringify(updated));
        });
    });

    /* ─── 6. Clear button ───────────────────────────── */
    clearBtn.addEventListener('click', function () {
        removeCurrentLayer();
        clearCoord();
        if (shapeSelect.value === 'Circle') radiusInput.value = '';
    });

    /* ─── 7. Validate before submit (also serializes eco_rules) ── */
    document.querySelector('form').addEventListener('submit', function (e) {
        // Serialize eco_rules before anything else (rules[] is in outer scope via window)
        var hiddenEcoRules = document.getElementById('eco-rules-input');
        if (hiddenEcoRules && window.__ecoRules) {
            hiddenEcoRules.value = JSON.stringify(window.__ecoRules);
        }

        if (!coordInput.value.trim()) {
            e.preventDefault();
            alert('! Harap gambar lokasi di peta terlebih dahulu.');
            document.getElementById('coord-map').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

});
</script>

{{-- ─── Eco Rules Builder ─────────────────────────────── --}}
<script>
(function () {
    /* State */
    let rules = [];

    /* Elements */
    const listEl    = document.getElementById('eco-rules-list');
    const emptyEl   = document.getElementById('rules-empty');
    const badgeEl   = document.getElementById('rules-count-badge');
    const textInput = document.getElementById('rule-text-input');
    const typeSelect= document.getElementById('rule-type-select');
    const addBtn    = document.getElementById('add-rule-btn');
    const hiddenIn  = document.getElementById('eco-rules-input');

    /* Label map */
    const labels = {
        prohibited: { text: 'Dilarang',  cls: 'bg-red-50 text-red-600 border-red-200'   },
        allowed:    { text: 'Diizinkan', cls: 'bg-emerald-50 text-emerald-700 border-emerald-200' },
    };

    /* ── Sync rules to global so map validator can serialize them ── */
    function syncGlobal() {
        window.__ecoRules = rules;
    }
    syncGlobal(); // init

    /* ── Render list ──────────────────────────────────── */
    function render() {
        listEl.innerHTML = '';
        emptyEl.classList.toggle('hidden', rules.length > 0);

        badgeEl.textContent = rules.length + ' aturan';
        badgeEl.classList.toggle('hidden', rules.length === 0);

        rules.forEach(function (rule, idx) {
            const meta = labels[rule.type] || labels.prohibited;
            const row  = document.createElement('div');
            row.className = 'flex items-center gap-2 p-3 rounded-xl border ' + meta.cls + ' group transition-all';
            row.innerHTML =
                '<span class="flex-1 text-sm font-medium">' + escHtml(rule.text) + '</span>' +
                '<span class="text-xs font-semibold px-2 py-0.5 rounded-full border ' + meta.cls + '">' + meta.text + '</span>' +
                '<button type="button" data-idx="' + idx + '" ' +
                    'class="remove-rule-btn ml-1 text-slate-300 hover:text-red-500 transition text-lg leading-none font-bold" ' +
                    'title="Hapus aturan ini">&times;</button>';
            listEl.appendChild(row);
        });

        /* Attach remove listeners */
        listEl.querySelectorAll('.remove-rule-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                rules.splice(parseInt(this.dataset.idx), 1);
                syncGlobal();
                render();
            });
        });
    }

    /* ── Add rule ─────────────────────────────────────── */
    addBtn.addEventListener('click', function () {
        const text = textInput.value.trim();
        if (!text) {
            textInput.focus();
            textInput.classList.add('border-red-400', 'ring-2', 'ring-red-100');
            setTimeout(function () {
                textInput.classList.remove('border-red-400', 'ring-2', 'ring-red-100');
            }, 1500);
            return;
        }
        rules.push({ text: text, type: typeSelect.value });
        syncGlobal();
        textInput.value = '';
        textInput.focus();
        render();
    });

    /* Allow Enter key on text input to add */
    textInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); addBtn.click(); }
    });

    /* ── Hydrate from old() on validation failure ─────── */
    (function hydrate() {
        const raw = hiddenIn.value.trim();
        if (!raw) return;
        try {
            const parsed = JSON.parse(raw);
            if (Array.isArray(parsed)) { rules = parsed; syncGlobal(); render(); }
        } catch (err) { /* ignore */ }
    })();

    /* ── Helper: escape HTML ──────────────────────────── */
    function escHtml(str) {
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }
})();
</script>
@endpush
