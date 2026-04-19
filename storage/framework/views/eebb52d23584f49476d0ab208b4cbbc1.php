<?php $__env->startSection('title', 'Laporan Saya - GreenTour'); ?>

<?php $__env->startSection('content'); ?>
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="rounded-3xl bg-white p-8 shadow-soft border border-slate-200">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Laporan Saya</h1>
                <p class="mt-2 text-sm text-slate-500">Kelola laporan isu lingkungan Anda.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="<?php echo e(route('profile.settings')); ?>" class="rounded-full border border-emerald-100 bg-emerald-50 px-5 py-3 text-sm font-semibold text-emerald-700 hover:bg-emerald-100">Pengaturan Profil</a>
                <a href="<?php echo e(route('profile.index')); ?>" class="rounded-full border border-slate-200 bg-slate-100 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200">Profil Saya</a>
            </div>
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <?php
                $filters = [
                    'all' => 'Semua',
                    'menunggu' => 'Menunggu',
                    'diverifikasi' => 'Diverifikasi',
                    'diterima' => 'Diterima',
                    'ditolak' => 'Ditolak',
                ];
            ?>
            <?php $__currentLoopData = $filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $isActive = $key === 'all' ? !$status : $status === $key;
                ?>
                <a href="<?php echo e(route('reports.index', ['status' => $key === 'all' ? null : $key])); ?>" class="rounded-full px-4 py-2 text-sm font-semibold <?php echo e($isActive ? 'bg-toscagreen text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'); ?> transition">
                    <?php echo e($label); ?>

                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-8 space-y-5">
            <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xl font-semibold text-slate-900"><?php echo e($report->title); ?></p>
                            <p class="mt-2 text-sm text-slate-600"><?php echo e($report->description); ?></p>
                        </div>
                        <span class="rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] <?php echo e($report->status === 'menunggu' ? 'bg-yellow-100 text-yellow-700' : ($report->status === 'diverifikasi' ? 'bg-sky-100 text-sky-700' : ($report->status === 'diterima' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'))); ?>"><?php echo e(strtoupper($report->status)); ?></span>
                    </div>
                    <div class="mt-5 flex flex-wrap items-center gap-4 text-sm text-slate-500">
                        <span>📅 <?php echo e($report->report_date->format('Y-m-d')); ?></span>
                        <span>📍 <?php echo e(number_format($report->latitude, 3)); ?>, <?php echo e(number_format($report->longitude, 3)); ?></span>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="rounded-3xl border border-dashed border-slate-200 bg-slate-50 p-10 text-center text-sm text-slate-500">
                    Belum ada laporan. Buat laporan baru untuk membantu lingkungan.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\ECO-RANGER-TOURISM\eco-ranger-tourism\resources\views/reports/index.blade.php ENDPATH**/ ?>