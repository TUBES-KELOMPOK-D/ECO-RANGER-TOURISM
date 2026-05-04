@extends('layouts.app')

@section('title', 'Chat Grup - ' . $event->name . ' | Eco Ranger Tourism')

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .message-bubble { animation: fadeInUp 0.25s ease both; }

    .chat-scroll {
        height: calc(100vh - 340px);
        min-height: 320px;
        overflow-y: auto;
        scroll-behavior: smooth;
    }
    .chat-scroll::-webkit-scrollbar { width: 4px; }
    .chat-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 9999px; }
    .chat-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
</style>
@endpush

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">

    {{-- Back button --}}
    <a href="{{ route('aksi.index') }}"
       class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-emerald-700 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke Daftar Event
    </a>

    {{-- Event Header --}}
    <div class="rounded-3xl bg-white border border-slate-100 shadow-soft overflow-hidden mb-4">
        <div class="bg-gradient-to-r from-emerald-600 to-toscagreen px-6 py-5 flex items-center gap-4">
            @if($event->image_path)
                <img src="{{ asset('storage/' . $event->image_path) }}" alt="{{ $event->name }}"
                     class="w-14 h-14 rounded-2xl object-cover border-2 border-white/30 flex-shrink-0">
            @else
                <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-2xl flex-shrink-0">🌿</div>
            @endif
            <div class="min-w-0">
                <h1 class="text-lg font-extrabold text-white leading-snug truncate">{{ $event->name }}</h1>
                <div class="flex flex-wrap items-center gap-3 mt-1">
                    <span class="text-xs text-emerald-100 font-semibold">
                        📅 {{ $event->event_date?->isoFormat('D MMMM YYYY') }}
                    </span>
                    @if($event->location)
                    <span class="text-xs text-emerald-100 font-semibold">📍 {{ $event->location }}</span>
                    @endif
                    <span class="text-xs bg-white/20 text-white px-2 py-0.5 rounded-full font-bold">
                        {{ $event->participants_count ?? $event->participants->count() }} Peserta
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-800 text-sm font-semibold mb-4">
        ✅ {{ session('success') }}
    </div>
    @endif

    {{-- Chat Box --}}
    <div class="rounded-3xl bg-white border border-slate-100 shadow-soft flex flex-col overflow-hidden">

        {{-- Chat Head --}}
        <div class="px-5 py-3.5 border-b border-slate-100 flex items-center gap-2">
            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
            <span class="text-sm font-bold text-slate-700">Chat Grup Event</span>
            <span class="ml-auto text-xs text-slate-400">{{ $messages->count() }} pesan</span>
        </div>

        {{-- Messages --}}
        <div id="chat-scroll" class="chat-scroll px-5 py-4 space-y-4 bg-slate-50/50">
            @if($messages->isEmpty())
                <div class="flex flex-col items-center justify-center h-full py-12 text-center">
                    <div class="text-4xl mb-3">💬</div>
                    <p class="text-sm font-bold text-slate-600">Belum ada pesan</p>
                    <p class="text-xs text-slate-400 mt-1">Jadilah yang pertama mengirim pesan di grup ini!</p>
                </div>
            @else
                @foreach($messages as $msg)
                    @php $isMine = $msg->user_id === auth()->id(); @endphp
                    <div class="message-bubble flex {{ $isMine ? 'justify-end' : 'justify-start' }} gap-2.5">
                        @if(!$isMine)
                            {{-- Avatar --}}
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-500 to-toscagreen text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-1">
                                {{ strtoupper(substr($msg->user->name, 0, 2)) }}
                            </div>
                        @endif
                        <div class="max-w-[72%]">
                            @if(!$isMine)
                                <p class="text-xs font-bold text-slate-600 mb-1 ml-0.5">{{ $msg->user->name }}</p>
                            @endif
                            <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed
                                {{ $isMine
                                    ? 'bg-emerald-600 text-white rounded-br-md'
                                    : 'bg-white border border-slate-100 text-slate-800 shadow-sm rounded-bl-md' }}">
                                @if($msg->image_path)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $msg->image_path) }}" alt="Foto Aksi" class="max-w-[200px] sm:max-w-xs w-full rounded-xl shadow-sm object-cover">
                                    </div>
                                @endif
                                @if($msg->message)
                                    {{ $msg->message }}
                                @endif
                            </div>
                            
                            {{-- Reactions --}}
                            <div class="flex flex-wrap items-center gap-1 mt-1 {{ $isMine ? 'justify-end' : '' }}">
                                @php
                                    $reactionsGroup = $msg->reactions->groupBy('reaction');
                                @endphp
                                @foreach($reactionsGroup as $reactionType => $reactionsList)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-50 border border-slate-200 text-[10px] font-bold text-slate-600 shadow-sm">
                                        {{ $reactionType }} {{ $reactionsList->count() }}
                                    </span>
                                @endforeach

                                {{-- React Button --}}
                                <div class="relative group ml-1">
                                    <button type="button" class="text-slate-400 hover:text-emerald-600 transition text-sm flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                    </button>
                                    {{-- Emoji Menu --}}
                                    <div class="absolute bottom-full {{ $isMine ? 'right-0' : 'left-0' }} pb-2 hidden group-hover:block z-10">
                                        <div class="flex items-center gap-1 bg-white border border-slate-100 shadow-md rounded-full px-2 py-1">
                                            @foreach(['👍', '❤️', '🔥', '👏', '🌿'] as $emoji)
                                                <form method="POST" action="{{ route('aksi.chat.react', [$event->id, $msg->id]) }}">
                                                    @csrf
                                                    <input type="hidden" name="reaction" value="{{ $emoji }}">
                                                    <button type="submit" class="hover:bg-slate-100 w-6 h-6 rounded-full flex items-center justify-center text-sm transition">{{ $emoji }}</button>
                                                </form>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mt-1 {{ $isMine ? 'justify-end' : '' }}">
                                <p class="text-xs text-slate-400">
                                    {{ $msg->created_at->diffForHumans() }}
                                </p>
                                @if(auth()->user()->role === 'admin')
                                    <form method="POST" action="{{ route('aksi.chat.delete', [$event->id, $msg->id]) }}" onsubmit="return confirm('Hapus pesan ini?')">
                                        @csrf
                                        <button type="submit" class="text-[10px] text-rose-500 hover:text-rose-700 hover:underline font-bold transition">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        @if($isMine)
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-500 to-toscagreen text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-1">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Send Message Form --}}
        <div class="px-5 py-4 border-t border-slate-100 bg-white">
            <form method="POST" action="{{ route('aksi.chat.send', $event) }}" class="flex items-end gap-3" enctype="multipart/form-data">
                @csrf
                
                {{-- Image Input --}}
                <label for="chat-image" class="flex-shrink-0 w-11 h-11 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center hover:bg-slate-200 transition-all cursor-pointer shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </label>
                <input type="file" name="image" id="chat-image" accept="image/*" class="hidden" onchange="previewImage(this)">

                <div class="flex-1 flex flex-col gap-2">
                    {{-- Image Preview Container --}}
                    <div id="image-preview-container" class="hidden relative w-32 h-32 rounded-xl overflow-hidden border border-slate-200 shadow-sm">
                        <img id="image-preview" class="w-full h-full object-cover">
                        <button type="button" onclick="removeImage()" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs shadow hover:bg-red-600 transition">&times;</button>
                    </div>
                    
                    <textarea name="message" rows="1"
                        id="chat-input"
                        placeholder="Ketik pesan atau lampirkan foto aksi..."
                        class="w-full border border-slate-200 rounded-2xl px-4 py-3 text-sm text-slate-800 resize-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition leading-relaxed"
                        oninput="autoResize(this)"
                        onkeydown="submitOnEnter(event, this.form)"></textarea>
                </div>
                <button type="submit"
                    class="flex-shrink-0 w-11 h-11 rounded-2xl bg-emerald-600 text-white flex items-center justify-center hover:bg-emerald-700 transition-all shadow-md hover:-translate-y-0.5 active:translate-y-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                </button>
            </form>
            <p class="text-xs text-slate-400 mt-2 px-1">Enter untuk baris baru, Shift+Enter untuk kirim.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-scroll ke bawah saat halaman load
    const chatScroll = document.getElementById('chat-scroll');
    if (chatScroll) chatScroll.scrollTop = chatScroll.scrollHeight;

    // Auto-resize textarea
    function autoResize(el) {
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 120) + 'px';
    }

    // Shift+Enter = kirim, Enter = baris baru
    function submitOnEnter(e, form) {
        if (e.key === 'Enter' && e.shiftKey) {
            e.preventDefault();
            form.submit();
        }
    }

    // Image Preview Logic
    function previewImage(input) {
        const previewContainer = document.getElementById('image-preview-container');
        const previewImg = document.getElementById('image-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage() {
        const input = document.getElementById('chat-image');
        const previewContainer = document.getElementById('image-preview-container');
        const previewImg = document.getElementById('image-preview');
        input.value = '';
        previewImg.src = '';
        previewContainer.classList.add('hidden');
    }
</script>
@endpush
