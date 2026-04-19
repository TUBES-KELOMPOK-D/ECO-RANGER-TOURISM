<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50">
    <?php if (isset($component)) { $__componentOriginala591787d01fe92c5706972626cdf7231 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala591787d01fe92c5706972626cdf7231 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.navbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $attributes = $__attributesOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__attributesOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $component = $__componentOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__componentOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>
    <div class="flex-grow flex items-center justify-center p-4">
    <div class="max-w-sm w-full bg-white p-8 rounded-3xl shadow-sm border border-slate-100 text-center">
        <h2 class="text-2xl font-black text-slate-800 mb-6 text-center">Masuk ke Akun</h2>
        
        <?php if($errors->any()): ?>
            <div class="bg-red-50 text-red-500 p-3 rounded-xl mb-4 text-sm font-bold">
                <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('login')); ?>" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Email</label>
                <input type="email" name="email" required class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500">
            </div>
            <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl hover:bg-emerald-700 transition-all">Login</button>
        </form>
        
        <p class="mt-4 text-center text-sm text-slate-500">Belum punya akun? <a href="<?php echo e(route('register')); ?>" class="text-emerald-600 font-bold decoration-transparent">Daftar</a></p>
    </div>
</body>
</html><?php /**PATH D:\Project\ECO-RANGER-TOURISM\eco-ranger-tourism\resources\views/auth/login.blade.php ENDPATH**/ ?>