<?php $__env->startSection('title', 'Aksi Event - Eco Ranger Tourism'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* ── Animations ── */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes shimmer {
        0%   { background-position: -200% center; }
        100% { background-position:  200% center; }
    }
    .animate-fadeinup { animation: fadeInUp 0.5s ease both; }
    .animate-delay-100 { animation-delay: 0.1s; }
    .animate-delay-200 { animation-delay: 0.2s; }
    .animate-delay-300 { animation-delay: 0.3s; }

    /* ── Card hover lift ── */
    .event-card {
        transition: transform 0.3s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s ease;
    }
    .event-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 24px 60px rgba(15,23,42,0.13);
    }

    /* ── Hero gradient ── */
    .hero-gradient {
        background: linear-gradient(135deg, #064e3b 0%, #065f46 40%, #047857 70%, #10b981 100%);
    }

    /* ── Badge pill ── */
    .badge-month { background: rgba(255,255,255,0.18); backdrop-filter: blur(8px); }

    /* ── Overlay / Modal ── */
    .modal-overlay {
        display: none;
        position: fixed; inset: 0; z-index: 999;
        background: rgba(15,23,42,0.55);
        backdrop-filter: blur(4px);
        align-items: center; justify-content: center;
    }
    .modal-overlay.active { display: flex; }
    .modal-box {
        background: #fff;
        border-radius: 1.5rem;
        padding: 2rem;
        width: 100%;
        max-width: 540px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 32px 80px rgba(15,23,42,0.2);
        animation: fadeInUp 0.3s ease both;
    }

    /* ── Event image placeholder ── */
    .event-img {
        width: 100%; height: 180px;
        object-fit: cover; border-radius: 1rem;
        background: linear-gradient(135deg,#d1fae5,#a7f3d0);
    }
    .event-img-placeholder {
        width: 100%; height: 180px;
        border-radius: 1rem;
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        font-size: 3rem;
    }

    /* ── Members list ── */
    .member-avatar {
        width: 2.25rem; height: 2.25rem;
        border-radius: 9999px;
        background: linear-gradient(135deg, #10b981, #065f46);
        color: #fff; font-weight: 700; font-size: 0.75rem;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    /* ── Input focus ring ── */
    input:focus, textarea:focus, select:focus {
        outline: none;
        ring: 2px;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16,185,129,0.15);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<div class="hero-gradient text-white py-16 px-4 sm:px-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between animate-fadeinup">
            <div>
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest badge-month mb-4">
                    Halaman Aksi
                </span>
                <h1 class="text-4xl sm:text-5xl font-black tracking-tight leading-tight">
                    Event <span class="text-emerald-300">Lingkungan</span>
                </h1>
                <p class="mt-3 text-emerald-100 text-base max-w-lg">
                    Bergabunglah dalam kegiatan pelestarian alam bersama komunitas Eco Ranger Tourism.
                </p>
            </div>

            
            <div class="flex flex-wrap gap-3 animate-fadeinup animate-delay-100">
                <div class="flex items-center gap-2 bg-white/10 backdrop-blur rounded-2xl px-4 py-3">
                    <span class="text-2xl font-black text-white"><?php echo e($events->count()); ?></span>
                    <span class="text-xs text-emerald-200 font-semibold">Event<br>Tersedia</span>
                </div>
                <?php if(auth()->guard()->check()): ?>
                    <?php $joinedCount = $events->where('is_joined', true)->count(); ?>
                    <?php if(auth()->user()->role !== 'admin'): ?>
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur rounded-2xl px-4 py-3">
                        <span class="text-2xl font-black text-emerald-300"><?php echo e($joinedCount); ?></span>
                        <span class="text-xs text-emerald-200 font-semibold">Sudah<br>Diikuti</span>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?php if(session('success') || session('error')): ?>
<div class="max-w-6xl mx-auto px-4 sm:px-6 mt-6">
    <?php if(session('success')): ?>
    <div class="flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-200 px-5 py-4 text-emerald-800 text-sm font-semibold shadow-sm">
        <span class="text-lg">✅</span> <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="flex items-center gap-3 rounded-2xl bg-rose-50 border border-rose-200 px-5 py-4 text-rose-800 text-sm font-semibold shadow-sm">
        <span class="text-lg">⚠️</span> <?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>
</div>
<?php endif; ?>


<div class="max-w-6xl mx-auto px-4 sm:px-6 mt-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

        <?php if(auth()->guard()->check()): ?>

            
            <?php if(auth()->user()->role === 'admin'): ?>
            <div>
                <button
                    onclick="openModal('modal-add-event')"
                    class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-lg hover:bg-emerald-700 hover:-translate-y-0.5 transition-all active:translate-y-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Event
                </button>
            </div>
            <div class="flex items-center gap-2 text-sm font-semibold text-slate-500">
                <span class="px-3 py-1.5 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">👑 Mode Admin</span>
                <span><?php echo e($events->count()); ?> Event Terdaftar</span>
            </div>

            
            <?php else: ?>
            <div class="flex items-center gap-3 flex-wrap">
                <span class="text-sm font-bold text-slate-700">Filter Bulan:</span>
                <button
                    onclick="toggleMonthFilter()"
                    id="btn-month-filter"
                    class="inline-flex items-center gap-2 border border-slate-200 bg-white px-4 py-2 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <?php echo e($month ? \Carbon\Carbon::create()->month((int)$month)->translatedFormat('F') : 'Semua Bulan'); ?>

                    <svg id="filter-caret" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </button>

                
                <div id="month-dropdown" class="hidden absolute mt-2 z-50">
                    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-3 grid grid-cols-3 gap-2 w-64">
                        <a href="<?php echo e(route('aksi.index')); ?>"
                           class="text-center px-2 py-2 rounded-xl text-xs font-semibold <?php echo e(!$month ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-50'); ?> transition">
                            Semua
                        </a>
                        <?php $__currentLoopData = range(1,12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('aksi.index', ['month' => $m])); ?>"
                           class="text-center px-2 py-2 rounded-xl text-xs font-semibold <?php echo e($month == $m ? 'bg-emerald-600 text-white' : 'text-slate-600 hover:bg-slate-50'); ?> transition">
                            <?php echo e(\Carbon\Carbon::create()->month($m)->translatedFormat('M')); ?>

                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <?php if($month): ?>
                <a href="<?php echo e(route('aksi.index')); ?>" class="text-xs text-slate-400 hover:text-slate-700 underline transition">Reset</a>
                <?php endif; ?>
            </div>
            <span class="text-sm text-slate-500 font-semibold"><?php echo e($events->count()); ?> Event Ditemukan</span>
            <?php endif; ?>

        <?php else: ?>
            
            <p class="text-sm text-slate-600 font-semibold"><?php echo e($events->count()); ?> Event tersedia. <a href="<?php echo e(route('login')); ?>" class="text-emerald-600 underline">Login</a> untuk bergabung.</p>
        <?php endif; ?>

    </div>
</div>


<div class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
    <?php if($events->isEmpty()): ?>
        <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50 py-20 text-center">
            <div class="text-5xl mb-4"></div>
            <p class="text-lg font-bold text-slate-700">Belum ada event tersedia</p>
            <p class="mt-2 text-sm text-slate-400">
                <?php if($month): ?>
                    Tidak ada event di bulan ini. <a href="<?php echo e(route('aksi.index')); ?>" class="text-emerald-600 underline">Lihat semua bulan</a>
                <?php else: ?>
                    <?php if(auth()->guard()->check()): ?> <?php if(auth()->user()->role === 'admin'): ?> Tambahkan event pertama menggunakan tombol di atas! <?php endif; ?> <?php endif; ?>
                <?php endif; ?>
            </p>
        </div>
    <?php else: ?>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="event-card rounded-3xl bg-white border border-slate-100 overflow-hidden shadow-sm animate-fadeinup"
             style="animation-delay: <?php echo e($index * 0.07); ?>s">

            
            <?php if($event->image_path): ?>
                <img src="<?php echo e(asset('storage/' . $event->image_path)); ?>"
                     alt="<?php echo e($event->name); ?>" class="event-img" />
            <?php else: ?>
                <div class="event-img-placeholder">🌿</div>
            <?php endif; ?>

            <div class="p-5">
                
                <div class="flex items-start justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-base font-extrabold text-slate-900 leading-snug truncate">
                            <?php echo e($event->name); ?>

                        </h2>
                        <p class="text-xs text-emerald-700 font-semibold mt-0.5">
                            <?php echo e($event->organizer ?? 'Eco Ranger Tourism'); ?>

                        </p>
                    </div>
                    
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->role !== 'admin' && $event->is_joined): ?>
                        <span class="flex-shrink-0 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">✓ Joined</span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                
                <div class="mt-4 space-y-2">
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <span><?php echo e($event->event_date ? $event->event_date->isoFormat('D MMMM YYYY') : '-'); ?></span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span class="truncate"><?php echo e($event->location ?? 'Lokasi belum ditentukan'); ?></span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span><?php echo e($event->participants_count); ?> Peserta</span>
                    </div>
                </div>

                
                <?php if($event->description): ?>
                <p class="mt-3 text-xs text-slate-500 line-clamp-2 leading-relaxed">
                    <?php echo e($event->description); ?>

                </p>
                <?php endif; ?>

                
                <?php if(auth()->guard()->check()): ?>
                    
                    <?php if(auth()->user()->role === 'admin'): ?>
                    <div class="mt-5 flex flex-wrap gap-2">
                        
                        <a href="<?php echo e(route('aksi.chat', $event)); ?>"
                           class="flex-1 inline-flex justify-center items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            Chat
                        </a>
                        
                        <button
                            onclick="openEditModal(<?php echo e($event->id); ?>, '<?php echo e(addslashes($event->name)); ?>', '<?php echo e(addslashes($event->description)); ?>', '<?php echo e($event->event_date?->format('Y-m-d')); ?>', '<?php echo e(addslashes($event->location)); ?>', '<?php echo e(addslashes($event->organizer)); ?>')"
                            class="flex-1 inline-flex justify-center items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-sky-50 text-sky-700 hover:bg-sky-100 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            Edit
                        </button>
                        
                        <button
                            onclick="openMembersModal(<?php echo e($event->id); ?>, '<?php echo e(addslashes($event->name)); ?>')"
                            class="flex-1 inline-flex justify-center items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-violet-50 text-violet-700 hover:bg-violet-100 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            Anggota
                        </button>
                        
                        <button
                            onclick="confirmDelete(<?php echo e($event->id); ?>, '<?php echo e(addslashes($event->name)); ?>')"
                            class="flex-none inline-flex justify-center items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 hover:bg-rose-100 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                            Hapus
                        </button>
                    </div>

                    
                    <?php else: ?>
                    <div class="mt-5 flex gap-2">
                        <?php if($event->is_joined): ?>
                            
                            <a href="<?php echo e(route('aksi.chat', $event)); ?>"
                               class="flex-1 inline-flex justify-center items-center gap-2 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-700 transition-all shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                Chat Grup
                            </a>
                            
                            <form method="POST" action="<?php echo e(route('aksi.leave', $event)); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit"
                                    onclick="return confirm('Batal ikut event ini?')"
                                    class="flex-none inline-flex justify-center items-center gap-1.5 px-3 py-2.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-rose-50 hover:text-rose-700 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    Batal Ikut
                                </button>
                            </form>
                        <?php else: ?>
                            
                            <form method="POST" action="<?php echo e(route('aksi.join', $event)); ?>" class="flex-1">
                                <?php echo csrf_field(); ?>
                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center gap-2 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-700 transition-all shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                                    Join Event
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                <?php else: ?>
                    
                    <a href="<?php echo e(route('login')); ?>"
                       class="mt-5 block text-center py-2.5 rounded-xl text-xs font-bold border border-emerald-200 text-emerald-700 hover:bg-emerald-50 transition-all">
                        Login untuk Bergabung
                    </a>
                <?php endif; ?>

            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>
</div>



<?php if(auth()->guard()->check()): ?>
<?php if(auth()->user()->role === 'admin'): ?>
<div id="modal-add-event" class="modal-overlay" onclick="closeOnBackdrop(event, 'modal-add-event')">
    <div class="modal-box">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-extrabold text-slate-900">Tambah Event Baru</h3>
                <p class="text-xs text-slate-400 mt-1">Isi detail event yang akan ditambahkan</p>
            </div>
            <button onclick="closeModal('modal-add-event')" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form method="POST" action="<?php echo e(route('aksi.store')); ?>" enctype="multipart/form-data" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Event <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required placeholder="contoh: Tanam Pohon di Pantai Kuta"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Event <span class="text-rose-500">*</span></label>
                    <input type="date" name="event_date" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Lokasi</label>
                    <input type="text" name="location" placeholder="contoh: Pantai Kuta, Bali"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Penyelenggara</label>
                <input type="text" name="organizer" placeholder="contoh: Tim Eco Ranger"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3" placeholder="Ceritakan detail event ini..."
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition resize-none"></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Foto Event</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-500 file:mr-3 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-add-event')"
                    class="flex-1 py-2.5 rounded-xl text-sm font-bold border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl text-sm font-bold bg-emerald-600 text-white hover:bg-emerald-700 transition shadow-md">
                    Simpan Event
                </button>
            </div>
        </form>
    </div>
</div>


<div id="modal-edit-event" class="modal-overlay" onclick="closeOnBackdrop(event, 'modal-edit-event')">
    <div class="modal-box">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-extrabold text-slate-900">Edit Event</h3>
                <p class="text-xs text-slate-400 mt-1">Perbarui informasi event</p>
            </div>
            <button onclick="closeModal('modal-edit-event')" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form id="form-edit-event" method="POST" action="" enctype="multipart/form-data" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Event <span class="text-rose-500">*</span></label>
                <input type="text" id="edit-name" name="name" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Event <span class="text-rose-500">*</span></label>
                    <input type="date" id="edit-date" name="event_date" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Lokasi</label>
                    <input type="text" id="edit-location" name="location"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Penyelenggara</label>
                <input type="text" id="edit-organizer" name="organizer"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi</label>
                <textarea id="edit-description" name="description" rows="3"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-800 focus:border-emerald-500 transition resize-none"></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Ganti Foto Event (opsional)</label>
                <input type="file" name="image" accept="image/*"
                    class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-500 file:mr-3 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 transition">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modal-edit-event')"
                    class="flex-1 py-2.5 rounded-xl text-sm font-bold border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 rounded-xl text-sm font-bold bg-sky-600 text-white hover:bg-sky-700 transition shadow-md">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>


<div id="modal-delete-event" class="modal-overlay" onclick="closeOnBackdrop(event, 'modal-delete-event')">
    <div class="modal-box" style="max-width:400px">
        <div class="text-center">
            <div class="w-14 h-14 rounded-full bg-rose-100 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#e11d48" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
            </div>
            <h3 class="text-lg font-extrabold text-slate-900">Hapus Event?</h3>
            <p class="mt-2 text-sm text-slate-500">Event "<span id="delete-event-name" class="font-semibold text-slate-700"></span>" akan dihapus permanen beserta semua datanya.</p>
        </div>
        <form id="form-delete-event" method="POST" action="" class="flex gap-3 mt-6">
            <?php echo csrf_field(); ?>
            <button type="button" onclick="closeModal('modal-delete-event')"
                class="flex-1 py-2.5 rounded-xl text-sm font-bold border border-slate-200 text-slate-600 hover:bg-slate-50 transition">
                Batal
            </button>
            <button type="submit"
                class="flex-1 py-2.5 rounded-xl text-sm font-bold bg-rose-600 text-white hover:bg-rose-700 transition shadow-md">
                Ya, Hapus
            </button>
        </form>
    </div>
</div>


<div id="modal-members" class="modal-overlay" onclick="closeOnBackdrop(event, 'modal-members')">
    <div class="modal-box" style="max-width:480px">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Kelola Anggota</h3>
                <p id="members-event-name" class="text-xs text-emerald-700 font-semibold mt-0.5"></p>
            </div>
            <button onclick="closeModal('modal-members')" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div id="members-list" class="space-y-2 max-h-72 overflow-y-auto pr-1"></div>
        <p id="members-empty" class="hidden text-center text-sm text-slate-400 py-8">Belum ada anggota yang bergabung.</p>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>


<?php if(auth()->guard()->check()): ?>
<?php if(auth()->user()->role === 'admin'): ?>
<?php
    $eventsJson = $events->map(function ($e) {
        return [
            'id'           => $e->id,
            'name'         => $e->name,
            'participants' => $e->participants->map(function ($p) {
                return ['id' => $p->id, 'name' => $p->name];
            })->values(),
        ];
    })->values();
?>
<script>
const eventsData = <?php echo json_encode($eventsJson, 15, 512) ?>;
</script>
<?php endif; ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// ── Modal Helpers ──────────────────────────────────────────────
function openModal(id) {
    document.getElementById(id).classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.remove('active');
    document.body.style.overflow = '';
}
function closeOnBackdrop(e, id) {
    if (e.target === e.currentTarget) closeModal(id);
}

// ── Admin: Edit Event ──────────────────────────────────────────
function openEditModal(id, name, description, date, location, organizer) {
    document.getElementById('edit-name').value        = name;
    document.getElementById('edit-description').value = description;
    document.getElementById('edit-date').value        = date;
    document.getElementById('edit-location').value    = location;
    document.getElementById('edit-organizer').value   = organizer;
    document.getElementById('form-edit-event').action = '/aksi/' + id + '/update';
    openModal('modal-edit-event');
}

// ── Admin: Konfirmasi Hapus Event ──────────────────────────────
function confirmDelete(id, name) {
    document.getElementById('delete-event-name').textContent  = name;
    document.getElementById('form-delete-event').action = '/aksi/' + id + '/delete';
    openModal('modal-delete-event');
}

// ── Admin: Kelola Anggota ──────────────────────────────────────
function openMembersModal(eventId, eventName) {
    document.getElementById('members-event-name').textContent = eventName;
    const listEl  = document.getElementById('members-list');
    const emptyEl = document.getElementById('members-empty');
    listEl.innerHTML = '';

    const eventObj = eventsData.find(e => e.id === eventId);
    const members  = eventObj ? eventObj.participants : [];

    if (members.length === 0) {
        listEl.classList.add('hidden');
        emptyEl.classList.remove('hidden');
    } else {
        listEl.classList.remove('hidden');
        emptyEl.classList.add('hidden');
        members.forEach(member => {
            const initials = member.name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();
            listEl.innerHTML += `
                <div class="flex items-center justify-between gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="member-avatar">${initials}</div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">${member.name}</p>
                        </div>
                    </div>
                    <form method="POST" action="/aksi/${eventId}/members/${member.id}/remove" onsubmit="return confirm('Hapus ${member.name} dari event ini?')">
                        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                        <button type="submit"
                            class="px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 hover:bg-rose-100 transition">
                            Hapus
                        </button>
                    </form>
                </div>`;
        });
    }

    openModal('modal-members');
}

// ── User: Month Filter Dropdown ────────────────────────────────
const monthDropdown = document.getElementById('month-dropdown');
const btnMonthFilter = document.getElementById('btn-month-filter');

if (btnMonthFilter) {
    // Place dropdown relative to button
    btnMonthFilter.parentElement.style.position = 'relative';
    monthDropdown.style.top    = '100%';
    monthDropdown.style.left   = '0';
    monthDropdown.style.marginTop = '8px';

    function toggleMonthFilter() {
        monthDropdown.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (btnMonthFilter && !btnMonthFilter.contains(e.target) && !monthDropdown.contains(e.target)) {
            monthDropdown.classList.add('hidden');
        }
    });
}

// Close all modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        ['modal-add-event','modal-edit-event','modal-delete-event','modal-members'].forEach(closeModal);
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\ECO-RANGER-TOURISM\resources\views/aksi/index.blade.php ENDPATH**/ ?>