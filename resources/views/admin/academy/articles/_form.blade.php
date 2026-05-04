@if ($errors->any())
    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-800">
        <ul class="list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="mb-5 text-lg font-bold text-slate-800">Informasi Materi</h2>
        <div class="space-y-4">
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Judul Materi</label>
                <input type="text" name="title" value="{{ old('title', $artikel->title ?? '') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" required>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Konten Materi</label>
                <textarea name="content" rows="14" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm leading-7 outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" required>{{ old('content', $artikel->content ?? '') }}</textarea>
                <p class="mt-2 text-xs text-slate-400">Gunakan baris kosong untuk memisahkan bagian seperti format materi Green Academy saat ini.</p>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="mb-5 text-lg font-bold text-slate-800">Detail Tambahan</h2>
        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Poin</label>
                <input type="number" name="points" min="0" value="{{ old('points', $artikel->points ?? 0) }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Durasi</label>
                <input type="text" name="duration" value="{{ old('duration', $artikel->duration ?? '') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="5 menit">
            </div>
            <div>
                <label class="mb-1.5 block text-sm font-semibold text-slate-700">Image Path</label>
                <input type="text" name="image_path" value="{{ old('image_path', $artikel->image_path ?? '') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100" placeholder="academy/nama-file.jpg">
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row">
        <button type="submit" class="flex-1 rounded-xl bg-emerald-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-100 transition hover:bg-emerald-700">
            Simpan Materi
        </button>
        <a href="{{ route('admin.academy.articles.index') }}" class="rounded-xl border border-slate-200 px-6 py-3.5 text-center text-sm font-bold text-slate-600 transition hover:bg-slate-50">
            Batal
        </a>
    </div>
</div>
