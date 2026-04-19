<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 20px;">
    <!-- Header -->
    <div class="header" style="text-align: center; margin-bottom: 40px; padding: 30px 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 20px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12); color: white;">
        <div style="font-size: 2.5em; font-weight: 700; margin-bottom: 10px;">Leaderboard Eco-Warriors</div>
        <div style="font-size: 1em; opacity: 0.95; font-weight: 300;">Ranking global semua eco-warriors</div>
    </div>

    <!-- Top 3 Section -->
    <div style="margin-bottom: 40px;">
        <h2 style="font-size: 1.5em; font-weight: 700; margin-bottom: 20px; color: #1f2937;">Top 3 Eco-Warriors</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
            <?php $__empty_1 = true; $__currentLoopData = $topRankers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ranker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div style="background: white; border-radius: 15px; padding: 25px; text-align: center; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); border: 2px solid <?php echo e($ranker['rank'] == 1 ? '#fbbf24' : ($ranker['rank'] == 2 ? '#c0cfd9' : '#d97706')); ?>; transition: all 0.3s ease;">
                    <div style="font-size: 1.3em; font-weight: 700; color: <?php echo e($ranker['rank'] == 1 ? '#fbbf24' : ($ranker['rank'] == 2 ? '#c0cfd9' : '#d97706')); ?>; margin-bottom: 10px;">RANK #<?php echo e($ranker['rank']); ?></div>
                    <div style="font-size: 1.2em; font-weight: 700; margin-bottom: 8px; color: #1f2937;"><?php echo e($ranker['name']); ?></div>
                    <div style="font-size: 0.9em; color: #10b981; font-weight: 600; margin-bottom: 15px;"><?php echo e($ranker['level']); ?></div>
                    <div style="font-size: 2.2em; font-weight: 700; color: #10b981; margin-bottom: 5px;"><?php echo e($ranker['points']); ?></div>
                    <div style="font-size: 0.85em; color: #6b7280;">Total Points</div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p style="text-align: center; color: #6b7280; grid-column: 1 / -1;">Belum ada data ranking</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Full Leaderboard Table -->
    <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);">
        <h2 style="font-size: 1.5em; font-weight: 700; margin-bottom: 20px; color: #1f2937;">Leaderboard Lengkap</h2>
        
        <?php if($leaderboard['users']->count() > 0): ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e5e7eb;">
                            <th style="padding: 15px; text-align: left; font-weight: 700;">Rank</th>
                            <th style="padding: 15px; text-align: left; font-weight: 700;">Name</th>
                            <th style="padding: 15px; text-align: left; font-weight: 700;">Level</th>
                            <th style="padding: 15px; text-align: right; font-weight: 700;">Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $leaderboard['users']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ranker): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr style="border-bottom: 1px solid #e5e7eb; transition: all 0.3s ease;">
                                <td style="padding: 15px;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <?php if($ranker['rank'] <= 3): ?>
                                            <span style="font-size: 1.3em;"><?php echo e($ranker['rank'] == 1 ? '🥇' : ($ranker['rank'] == 2 ? '🥈' : '🥉')); ?></span>
                                        <?php else: ?>
                                            <span style="font-weight: 700; color: #10b981;">#<?php echo e($ranker['rank']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td style="padding: 15px;">
                                    <span style="font-weight: 600; color: #1f2937;"><?php echo e($ranker['name']); ?></span>
                                </td>
                                <td style="padding: 15px;">
                                    <span style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 6px 12px; border-radius: 8px; font-weight: 600; font-size: 0.85em;"><?php echo e($ranker['level']); ?></span>
                                </td>
                                <td style="padding: 15px; text-align: right;">
                                    <span style="font-weight: 700; font-size: 1.1em; color: #10b981;"><?php echo e($ranker['points']); ?> PTS</span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" style="padding: 30px; text-align: center; color: #6b7280;">Belum ada data</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Info -->
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 0.9em;">
                Menampilkan <?php echo e((($leaderboard['page']-1)*$leaderboard['limit'])+1); ?> - <?php echo e(min($leaderboard['page']*$leaderboard['limit'], $leaderboard['total'])); ?> dari <?php echo e($leaderboard['total']); ?> users
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 40px; color: #6b7280;">
                <p>Belum ada data ranking tersedia</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Point Rules Reference -->
    <div style="margin-top: 40px; background: linear-gradient(135deg, #f0fdf4 0%, #f9fafb 100%); border-radius: 15px; padding: 25px; border-left: 4px solid #10b981;">
        <h3 style="font-size: 1.2em; font-weight: 700; margin-bottom: 15px; color: #1f2937;">Bagaimana cara mendapatkan poin?</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <?php $__currentLoopData = $pointRules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="background: white; padding: 15px; border-radius: 10px; border-left: 3px solid #10b981;">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                        <span style="font-size: 1.5em;"><?php echo e($rule['icon']); ?></span>
                        <span style="font-weight: 600; color: #1f2937;"><?php echo e($rule['title']); ?></span>
                    </div>
                    <div style="font-size: 0.85em; color: #6b7280; margin-bottom: 8px;"><?php echo e($rule['description']); ?></div>
                    <div style="font-weight: 700; color: #10b981;">+<?php echo e($rule['points']); ?> Points</div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<style>
    table tbody tr:hover {
        background-color: #f9fafb !important;
    }
    
    @media (max-width: 768px) {
        .container {
            padding: 15px !important;
        }
        
        table {
            font-size: 0.9em !important;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Project\ECO-RANGER-TOURISM\eco-ranger-tourism\resources\views/rankings/index.blade.php ENDPATH**/ ?>