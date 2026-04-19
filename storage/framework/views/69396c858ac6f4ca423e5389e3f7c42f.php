<?php $__env->startSection('title', 'Chat Grup - ' . $event->name . ' | Eco Ranger Tourism'); ?>

<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto px-4 sm:px-6 py-8">

    
    <a href="<?php echo e(route('aksi.index')); ?>"
       class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-emerald-700 transition mb-6">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke Daftar Event
    </a>

    
    <div class="rounded-3xl bg-white border border-slate-100 shadow-soft overflow-hidden mb-4">
        <div class="bg-gradient-to-r from-emerald-600 to-toscagreen px-6 py-5 flex items-center gap-4">
            <?php if($event->image_path): ?>
                <img src="<?php echo e(asset('storage/' . $event->image_path)); ?>" alt="<?php echo e($event->name); ?>"
                     class="w-14 h-14 rounded-2xl object-cover border-2 border-white/30 flex-shrink-0">
            <?php else: ?>
                <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-2xl flex-shrink-0">🌿</div>
            <?php endif; ?>
            <div class="min-w-0">
                <h1 class="text-lg font-extrabold text-white leading-snug truncate"><?php echo e($event->name); ?></h1>
                <div class="flex flex-wrap items-center gap-3 mt-1">
                    <span class="text-xs text-emerald-100 font-semibold">
                        📅 <?php echo e($event->event_date?->isoFormat('D MMMM YYYY')); ?>

                    </span>
                    <?php if($event->location): ?>
                    <span class="text-xs text-emerald-100 font-semibold">📍 <?php echo e($event->location); ?></span>
                    <?php endif; ?>
                    <span class="text-xs bg-white/20 text-white px-2 py-0.5 rounded-full font-bold">
                        <?php echo e($event->participants_count ?? $event->participants->count()); ?> Peserta
                    </span>
                </div>
            </div>
        </div>
    </div>

    
    <?php if(session('success')): ?>
    <div class="flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-emerald-800 text-sm font-semibold mb-4">
        ✅ <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    
    <div class="rounded-3xl bg-white border border-slate-100 shadow-soft flex flex-col overflow-hidden">

        
        <div class="px-5 py-3.5 border-b border-slate-100 flex items-center gap-2">
            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></div>
            <span class="text-sm font-bold text-slate-700">Chat Grup Event</span>
            <span class="ml-auto text-xs text-slate-400"><?php echo e($messages->count()); ?> pesan</span>
        </div>

        
        <div id="chat-scroll" class="chat-scroll px-5 py-4 space-y-4 bg-slate-50/50">
            <?php if($messages->isEmpty()): ?>
                <div class="flex flex-col items-center justify-center h-full py-12 text-center">
                    <div class="text-4xl mb-3">💬</div>
                    <p class="text-sm font-bold text-slate-600">Belum ada pesan</p>
                    <p class="text-xs text-slate-400 mt-1">Jadilah yang pertama mengirim pesan di grup ini!</p>
                </div>
            <?php else: ?>
                <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $isMine = $msg->user_id === auth()->id(); ?>
                    <div class="message-bubble flex <?php echo e($isMine ? 'justify-end' : 'justify-start'); ?> gap-2.5">
                        <?php if(!$isMine): ?>
                            
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-500 to-toscagreen text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-1">
                                <?php echo e(strtoupper(substr($msg->user->name, 0, 2))); ?>

                            </div>
                        <?php endif; ?>
                        <div class="max-w-[72%]">
                            <?php if(!$isMine): ?>
                                <p class="text-xs font-bold text-slate-600 mb-1 ml-0.5"><?php echo e($msg->user->name); ?></p>
                            <?php endif; ?>
                            <div class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed
                                <?php echo e($isMine
                                    ? 'bg-emerald-600 text-white rounded-br-md'
                                    : 'bg-white border border-slate-100 text-slate-800 shadow-sm rounded-bl-md'); ?>">
                                <?php echo e($msg->message); ?>

                            </div>
                            <div class="flex items-center gap-2 mt-1 <?php echo e($isMine ? 'justify-end' : ''); ?>">
                                <p class="text-xs text-slate-400">
                                    <?php echo e($msg->created_at->diffForHumans()); ?>

                                </p>
                                <?php if(auth()->user()->role === 'admin'): ?>
                                    <form method="POST" action="<?php echo e(route('aksi.chat.delete', [$event->id, $msg->id])); ?>" onsubmit="return confirm('Hapus pesan ini?')">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="text-[10px] text-rose-500 hover:text-rose-700 hover:underline font-bold transition">Hapus</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if($isMine): ?>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-500 to-toscagreen text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-1">
                                <?php echo e(strtoupper(substr(auth()->user()->name, 0, 2))); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>

        
        <div class="px-5 py-4 border-t border-slate-100 bg-white">
            <form method="POST" action="<?php echo e(route('aksi.chat.send', $event)); ?>" class="flex items-end gap-3">
                <?php echo csrf_field(); ?>
                <div class="flex-1">
                    <textarea name="message" rows="1" required
                        id="chat-input"
                        placeholder="Ketik pesan..."
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\ECO-RANGER-TOURISM\resources\views/aksi/chat.blade.php ENDPATH**/ ?>