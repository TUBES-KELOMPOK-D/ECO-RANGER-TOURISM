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
            <h1 class="text-2xl font-black text-slate-900">Tambah Marker Manual</h1>
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
            <p class="text-xs text-slate-400 mb-5">Masukkan data bentuk dan koordinat untuk dirender di peta.</p>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Bentuk (Shape Type)</label>
                        <select name="shape_type" id="shape_type" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm">
                            <option value="Marker" {{ old('shape_type') === 'Marker' ? 'selected' : '' }}>Marker (Titik)</option>
                            <option value="Polygon" {{ old('shape_type') === 'Polygon' ? 'selected' : '' }}>Polygon (Area Bebas)</option>
                            <option value="Rectangle" {{ old('shape_type') === 'Rectangle' ? 'selected' : '' }}>Rectangle (Area Kotak)</option>
                            <option value="Circle" {{ old('shape_type') === 'Circle' ? 'selected' : '' }}>Circle (Lingkaran)</option>
                            <option value="Polyline" {{ old('shape_type') === 'Polyline' ? 'selected' : '' }}>Polyline (Garis)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Radius (Khusus Circle)</label>
                        <input type="number" step="any" name="radius" id="radius" value="{{ old('radius') }}"
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm disabled:bg-slate-100 disabled:text-slate-400" placeholder="Dalam meter (contoh: 500)">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Koordinat (Array JSON)</label>
                    <p class="text-xs text-slate-400 mb-2">
                        Untuk Marker/Circle: <code>[lat, lng]</code><br>
                        Untuk Polygon/Polyline: <code>[[lat1, lng1], [lat2, lng2], ...]</code>
                    </p>
                    <textarea name="coordinates" rows="4" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm font-mono resize-none" placeholder="[-6.2088, 106.8456]">{{ old('coordinates') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Eco Detail Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-5">Detail Eco-Health</h2>

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

        {{-- Eco Rules Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-2">Aturan Wisata Hijau</h2>
            <p class="text-xs text-slate-400 mb-4">Format JSON. Contoh: <code class="bg-slate-100 px-1.5 py-0.5 rounded text-xs">[{"text":"Dilarang buang sampah","type":"allowed"}]</code></p>
            <textarea name="eco_rules" rows="5"
                class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm font-mono resize-none" placeholder='[{"text":"Aturan...","type":"allowed"}]'>{{ old('eco_rules') }}</textarea>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-4">
            <button type="submit" class="flex-1 bg-emerald-600 text-white px-6 py-3.5 rounded-xl font-bold text-sm hover:bg-emerald-700 hover:-translate-y-0.5 transition-all shadow-lg shadow-emerald-100 active:translate-y-0">
                ➕ Tambah Marker
            </button>
            <a href="/" class="px-6 py-3.5 rounded-xl font-bold text-sm text-slate-500 hover:bg-slate-100 transition border border-slate-200">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const shapeSelect = document.getElementById('shape_type');
        const radiusInput = document.getElementById('radius');

        function toggleRadius() {
            if (shapeSelect.value === 'Circle') {
                radiusInput.disabled = false;
                radiusInput.required = true;
            } else {
                radiusInput.disabled = true;
                radiusInput.required = false;
                radiusInput.value = '';
            }
        }

        shapeSelect.addEventListener('change', toggleRadius);
        toggleRadius(); // Initial check
    });
</script>
@endsection
