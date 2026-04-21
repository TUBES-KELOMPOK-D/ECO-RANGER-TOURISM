@extends('layouts.app')

@section('title', 'Edit Marker — ' . ($marker->title ?? 'Lokasi'))

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
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
        </div>
        <div>
            <h1 class="text-2xl font-black text-slate-900">Edit Marker</h1>
            <p class="text-sm text-slate-500">ID: #{{ $marker->id }} — {{ $marker->shape_type }}</p>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl mb-6 font-semibold text-sm flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('markers.update', $marker->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Basic Info Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-5">Informasi Dasar</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Marker</label>
                    <input type="text" name="title" value="{{ old('title', $marker->title) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm" placeholder="Nama marker">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lokasi</label>
                    <input type="text" name="location_name" value="{{ old('location_name', $marker->location_name) }}"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm" placeholder="Misal: Muara Gembong, Jawa Barat">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm resize-none" placeholder="Deskripsi lokasi...">{{ old('description', $marker->description) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Status</label>
                        <select name="status" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm">
                            <option value="green" {{ $marker->status === 'green' ? 'selected' : '' }}>🟢 Aman (Hijau)</option>
                            <option value="yellow" {{ $marker->status === 'yellow' ? 'selected' : '' }}>🟡 Waspada (Kuning)</option>
                            <option value="red" {{ $marker->status === 'red' ? 'selected' : '' }}>🔴 Bahaya (Merah)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori</label>
                        <select name="category" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm">
                            <option value="">— Pilih —</option>
                            <option value="Sangat Terjaga" {{ ($marker->category ?? '') === 'Sangat Terjaga' ? 'selected' : '' }}>Sangat Terjaga</option>
                            <option value="Terjaga" {{ ($marker->category ?? '') === 'Terjaga' ? 'selected' : '' }}>Terjaga</option>
                            <option value="Perlu Perhatian" {{ ($marker->category ?? '') === 'Perlu Perhatian' ? 'selected' : '' }}>Perlu Perhatian</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Gambar Lokasi</label>
                    @if($marker->image_path)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $marker->image_path) }}" alt="Current image" class="w-full h-40 object-cover rounded-xl border border-slate-200">
                        <p class="text-xs text-slate-400 mt-1">Gambar saat ini. Upload baru untuk mengganti.</p>
                    </div>
                    @endif
                    <input type="file" name="image" accept="image/*"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition">
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
                        <input type="number" name="eco_health_score" value="{{ old('eco_health_score', $marker->eco_health_score) }}" step="0.1" min="0" max="10"
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm" placeholder="8.5">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kebersihan</label>
                        <input type="text" name="kebersihan" value="{{ old('kebersihan', $marker->kebersihan) }}"
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm" placeholder="4/5">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Akses</label>
                        <select name="akses" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm">
                            <option value="">— Pilih —</option>
                            <option value="Mudah" {{ ($marker->akses ?? '') === 'Mudah' ? 'selected' : '' }}>Mudah</option>
                            <option value="Sedang" {{ ($marker->akses ?? '') === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="Sulit" {{ ($marker->akses ?? '') === 'Sulit' ? 'selected' : '' }}>Sulit</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Populasi</label>
                        <select name="populasi" class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm">
                            <option value="">— Pilih —</option>
                            <option value="Rendah" {{ ($marker->populasi ?? '') === 'Rendah' ? 'selected' : '' }}>Rendah</option>
                            <option value="Sedang" {{ ($marker->populasi ?? '') === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                            <option value="Tinggi" {{ ($marker->populasi ?? '') === 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
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
                class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition text-sm font-mono resize-none" placeholder='[{"text":"Aturan...","type":"allowed"}]'>{{ old('eco_rules', $marker->eco_rules ? json_encode($marker->eco_rules, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}</textarea>
        </div>

        {{-- Coordinate Info (read-only) --}}
        <div class="bg-slate-50 rounded-2xl border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-3">Info Koordinat</h2>
            <div class="grid grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-slate-400 font-semibold text-xs uppercase mb-1">Shape Type</p>
                    <p class="font-bold text-slate-700">{{ $marker->shape_type }}</p>
                </div>
                <div>
                    <p class="text-slate-400 font-semibold text-xs uppercase mb-1">Coordinates</p>
                    <p class="font-bold text-slate-700 text-xs">{{ is_array($marker->coordinates) ? json_encode($marker->coordinates) : $marker->coordinates }}</p>
                </div>
                <div>
                    <p class="text-slate-400 font-semibold text-xs uppercase mb-1">Radius</p>
                    <p class="font-bold text-slate-700">{{ $marker->radius ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-4">
            <button type="submit" class="flex-1 bg-emerald-600 text-white px-6 py-3.5 rounded-xl font-bold text-sm hover:bg-emerald-700 hover:-translate-y-0.5 transition-all shadow-lg shadow-emerald-100 active:translate-y-0">
                💾 Simpan Perubahan
            </button>
            <a href="/" class="px-6 py-3.5 rounded-xl font-bold text-sm text-slate-500 hover:bg-slate-100 transition border border-slate-200">
                Batal
            </a>
        </div>
    </form>

    {{-- Delete Section --}}
    <div class="mt-8 pt-6 border-t border-slate-200">
        <form action="{{ route('markers.destroy', $marker->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus marker ini?')">
            @csrf
            <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-700 transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                Hapus Marker Ini
            </button>
        </form>
    </div>
</div>
@endsection
