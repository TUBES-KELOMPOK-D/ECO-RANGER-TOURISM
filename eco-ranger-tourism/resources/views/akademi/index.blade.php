<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akademi – Eco Ranger Tourism</title>
    <meta name="description" content="Green Academy: Pelajari materi edukasi lingkungan dan raih poin reward.">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen font-sans text-slate-900">

    <x-navbar />

    <main class="max-w-3xl mx-auto px-4 sm:px-6 py-8 space-y-8">

        {{-- Header --}}
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-black text-slate-800">Akademi</h1>
        </div>

        {{-- Flash Messages --}}
        @if(session('info'))
            <div class="bg-blue-50 border border-blue-100 text-blue-700 px-5 py-4 rounded-2xl text-sm font-bold">
                {{ session('info') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-100 text-red-600 px-5 py-4 rounded-2xl text-sm font-bold">
                {{ session('error') }}
            </div>
        @endif

        {{-- Materi Tersedia --}}
        <div>
            <h3 class="text-xl font-black text-slate-800 mb-4">Materi Tersedia</h3>
            <div class="space-y-4">
                @forelse($materials as $material)
                    @php $isDone = in_array($material->id, $completedIds); @endphp
                    <a href="{{ url('/akademi/materi/' . $material->slug) }}"
                       class="bg-white p-5 rounded-[28px] border border-slate-100 shadow-sm flex gap-5 items-center cursor-pointer hover:border-emerald-200 hover:shadow-md transition-all group decoration-transparent block">

                        {{-- Icon --}}
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0
                            {{ $isDone ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-50 text-slate-400 group-hover:bg-emerald-50 group-hover:text-emerald-500' }}">
                            @if($isDone)
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2.5"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                                </svg>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <h4 class="font-black text-slate-800 leading-tight mb-1 truncate">{{ $material->title }}</h4>
                            <p class="text-xs text-slate-500 font-medium truncate">{{ $material->description }}</p>
                        </div>

                        {{-- Poin Badge --}}
                        <div class="flex-shrink-0 text-right">
                            <div class="inline-flex items-center gap-1 bg-amber-50 text-amber-600 px-2.5 py-1 rounded-lg text-xs font-black uppercase tracking-widest">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                                +{{ $material->points }}
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="bg-white p-16 rounded-[28px] text-center border border-slate-100 border-dashed">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                            </svg>
                        </div>
                        <p class="text-slate-500 font-medium text-sm">Belum ada materi tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </main>

</body>
</html>
