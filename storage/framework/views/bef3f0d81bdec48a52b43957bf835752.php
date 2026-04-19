<?php $__env->startSection('content'); ?>
<div class="bg-gray-50 min-h-screen">
    <!-- GREEN HEADER WITH PODIUM -->
    <div class="bg-gradient-to-b from-emerald-700 to-emerald-600 rounded-b-3xl shadow-lg py-16" style="background: linear-gradient(to bottom, #098352, #10A96E);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-white text-center mb-12">Eco-Rankings 🏆</h1>
            
            <!-- PODIUM SECTION -->
            <div class="flex flex-col md:flex-row justify-center items-end gap-8 md:gap-6 h-96 md:h-80">
                <!-- Rank #2 (Left) - Height 60% -->
                <?php $rank2 = $topThree[1] ?? null; ?>
                <div class="flex-1 text-center flex flex-col items-center h-3/5">
                    <?php if($rank2): ?>
                        <div class="mb-3 flex justify-center">
                            <div class="w-14 h-14 bg-gradient-to-b from-gray-300 to-gray-400 rounded-2xl flex items-center justify-center text-white text-lg font-bold shadow-lg border-2 border-white">
                                <?php echo e(strtoupper(substr($rank2->name, 0, 2))); ?>

                            </div>
                        </div>
                        <div class="w-full flex-1 rounded-t-3xl flex items-center justify-center relative border-4 border-white shadow-lg" style="background: linear-gradient(to top, #10A96E, #1FD571);">
                            <div class="text-center">
                                <p class="text-5xl font-black text-white">2</p>
                            </div>
                        </div>
                        <p class="text-white text-sm font-bold mt-2 w-full py-2 px-2 rounded-b-2xl" style="background-color: #10A96E;"><?php echo e($rank2->name); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Rank #1 (Center) - Height 100% TALLEST -->
                <?php $rank1 = $topThree[0] ?? null; ?>
                <div class="flex-1 text-center flex flex-col items-center h-full mb-4">
                    <?php if($rank1): ?>
                        <div class="mb-4 animate-bounce">
                            <div class="text-5xl">👑</div>
                        </div>
                        <div class="mb-4 flex justify-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-yellow-300 to-yellow-500 rounded-3xl flex items-center justify-center text-white text-2xl font-black shadow-xl border-4 border-white transform scale-110">
                                <?php echo e(strtoupper(substr($rank1->name, 0, 2))); ?>

                            </div>
                        </div>
                        <div class="w-full flex-1 rounded-t-3xl flex items-center justify-center relative border-4 border-white shadow-2xl" style="background: linear-gradient(to top, #098352, #10A96E);">
                            <div class="text-center">
                                <p class="text-7xl font-black text-white">1</p>
                            </div>
                        </div>
                        <p class="text-white text-sm font-black mt-2 w-full py-2 px-2 rounded-b-2xl" style="background-color: #098352;"><?php echo e($rank1->name); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Rank #3 (Right) - Height 40% SHORTEST -->
                <?php $rank3 = $topThree[2] ?? null; ?>
                <div class="flex-1 text-center flex flex-col items-center h-2/5">
                    <?php if($rank3): ?>
                        <div class="mb-2 flex justify-center">
                            <div class="w-12 h-12 bg-gradient-to-b from-orange-300 to-orange-500 rounded-2xl flex items-center justify-center text-white text-base font-bold shadow-lg border-2 border-white">
                                <?php echo e(strtoupper(substr($rank3->name, 0, 2))); ?>

                            </div>
                        </div>
                        <div class="w-full flex-1 rounded-t-3xl flex items-center justify-center relative border-4 border-white shadow-lg" style="background: linear-gradient(to top, #10A96E, #1FD571);">
                            <div class="text-center">
                                <p class="text-4xl font-black text-white">3</p>
                            </div>
                        </div>
                        <p class="text-white text-sm font-bold mt-2 w-full py-2 px-2 rounded-b-2xl" style="background-color: #10A96E;"><?php echo e($rank3->name); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- REWARD VOUCHER (Full Width Below Header) -->
        <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Reward Voucher</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg p-4 border border-yellow-300">
                    <p class="font-bold text-yellow-700 text-sm">🥇 Rank #1</p>
                    <p class="text-xs text-gray-700 mt-1">🎫 Voucher Wisata Rp500.000</p>
                </div>
                <div class="bg-gradient-to-r from-gray-100 to-gray-200 rounded-lg p-4 border border-gray-400">
                    <p class="font-bold text-gray-700 text-sm">🥈 Rank #2</p>
                    <p class="text-xs text-gray-700 mt-1">🎫 Voucher Wisata Rp250.000</p>
                </div>
                <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg p-4 border border-orange-300">
                    <p class="font-bold text-orange-700 text-sm">🥉 Rank #3</p>
                    <p class="text-xs text-gray-700 mt-1">🎫 Voucher Wisata Rp100.000</p>
                </div>
            </div>
        </div>

        <!-- PAPAN PERINGKAT -->
        <div class="bg-white rounded-2xl shadow-md overflow-hidden mb-8">
            <div class="p-6 border-b" style="background: linear-gradient(to right, rgba(16, 169, 110, 0.1), rgba(31, 213, 113, 0.1));">
                <h2 class="text-2xl font-bold text-gray-900">Papan Peringkat</h2>
            </div>
            
            <?php if($leaderboard->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2" style="border-color: #10A96E; background-color: #f9fafb;">
                                <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700 w-16">Rank</th>
                                <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Nama</th>
                                <th class="text-left py-4 px-6 text-sm font-semibold text-gray-700">Level</th>
                                <th class="text-right py-4 px-6 text-sm font-semibold text-gray-700">Poin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $leaderboard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-b hover:bg-opacity-50 transition" style="border-color: rgba(16, 169, 110, 0.2); background-color: transparent;" onmouseover="this.style.backgroundColor = 'rgba(16, 169, 110, 0.05)'" onmouseout="this.style.backgroundColor = 'transparent'">
                                    <td class="py-4 px-6 font-bold" style="color: #10A96E;">
                                        <?php if($index + 1 == 1): ?> 🥇
                                        <?php elseif($index + 1 == 2): ?> 🥈
                                        <?php elseif($index + 1 == 3): ?> 🥉
                                        <?php else: ?> #<?php echo e($index + 1); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td class="py-4 px-6 font-medium text-gray-900"><?php echo e($user->name); ?></td>
                                    <td class="py-4 px-6">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                            <?php if($user->level == 'Eco-Ranger'): ?> text-white
                                            <?php elseif($user->level == 'Eco-Warrior'): ?> bg-blue-100 text-blue-700
                                            <?php else: ?> bg-gray-100 text-gray-600 <?php endif; ?>" style="<?php if($user->level == 'Eco-Ranger'): ?> background-color: #10A96E; <?php endif; ?>">
                                            <?php echo e($user->level); ?>

                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right font-bold" style="color: #10A96E;"><?php echo e(number_format($user->total_points)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-12 text-gray-400">Belum ada data ranking</div>
            <?php endif; ?>
        </div>

        <!-- ATURAN POIN -->
        <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Aturan Poin</h2>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <div class="flex justify-between items-center p-4 rounded-lg border" style="background-color: rgba(16, 169, 110, 0.05); border-color: #10A96E;">
                    <span class="text-sm font-medium text-gray-800">📋 Lapor Isu</span>
                    <span class="font-bold" style="color: #10A96E;">+10</span>
                </div>
                <div class="flex justify-between items-center p-4 rounded-lg border" style="background-color: rgba(16, 169, 110, 0.05); border-color: #10A96E;">
                    <span class="text-sm font-medium text-gray-800">🤝 Ikut Aksi</span>
                    <span class="font-bold" style="color: #10A96E;">+50</span>
                </div>
                <div class="flex justify-between items-center p-4 rounded-lg border" style="background-color: rgba(16, 169, 110, 0.05); border-color: #10A96E;">
                    <span class="text-sm font-medium text-gray-800">✓ Verifikasi</span>
                    <span class="font-bold" style="color: #10A96E;">+5</span>
                </div>
                <div class="flex justify-between items-center p-4 rounded-lg border" style="background-color: rgba(16, 169, 110, 0.05); border-color: #10A96E;">
                    <span class="text-sm font-medium text-gray-800">💬 Forum</span>
                    <span class="font-bold" style="color: #10A96E;">+15</span>
                </div>
                <div class="flex justify-between items-center p-4 rounded-lg border" style="background-color: rgba(16, 169, 110, 0.05); border-color: #10A96E;">
                    <span class="text-sm font-medium text-gray-800">📸 Bagikan</span>
                    <span class="font-bold" style="color: #10A96E;">+20</span>
                </div>
            </div>
        </div>

        <!-- LENCANA & PENCAPAIAN (Full Width Below) -->
        <div class="bg-white rounded-2xl shadow-md p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Lencana & Pencapaian</h2>
            
            <?php if(Auth::check() && count($badges) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <?php $__currentLoopData = $badges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-4 bg-gray-50 rounded-lg border" style="border-color: #10A96E;">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-2xl"><?php echo e($badge['icon']); ?></span>
                                <p class="font-semibold text-sm text-gray-900"><?php echo e($badge['name']); ?></p>
                            </div>
                            <p class="text-xs text-gray-600 mb-3"><?php echo e($badge['target']); ?></p>
                            <div class="w-full bg-gray-300 rounded-full h-2 overflow-hidden mb-2">
                                <div class="h-full transition-all" style="width: <?php echo e($badge['progress']); ?>%; background-color: #10A96E;"></div>
                            </div>
                            <p class="text-xs font-bold text-gray-700 text-center"><?php echo e((int)$badge['current']); ?>/<?php echo e($badge['max']); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8 text-gray-500 text-sm">
                    📝 Login untuk melihat pencapaian Anda
                </div>
            <?php endif; ?>
        </div>

        <!-- POSISI USER SAAT INI (Paling Bawah) -->
        <?php if(Auth::check()): ?>
            <div class="rounded-2xl shadow-md p-8 text-white text-center" style="background: linear-gradient(to right, #098352, #10A96E);">
                <p class="text-sm opacity-90 font-semibold">POSISI ANDA SAAT INI</p>
                <p class="text-5xl font-bold mt-4">Rank #<?php echo e($currentUserRank ?? '?'); ?></p>
                <p class="text-lg mt-3"><?php echo e(number_format($currentUserPoints ?? 0)); ?> poin</p>
                <p class="text-xs opacity-75 mt-4">Terus berkontribusi untuk naik peringkat!</p>
            </div>
        <?php else: ?>
            <div class="rounded-2xl shadow-md p-8 text-white text-center bg-gray-600">
                <p class="text-sm opacity-90 font-semibold">Silakan login untuk melihat posisi Anda</p>
                <a href="<?php echo e(route('login')); ?>" class="mt-4 inline-block px-8 py-3 bg-white rounded-lg font-semibold hover:bg-gray-100 transition" style="color: #10A96E;">
                    Login
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\ECO-RANGER-TOURISM\resources\views/eco-rankings.blade.php ENDPATH**/ ?>