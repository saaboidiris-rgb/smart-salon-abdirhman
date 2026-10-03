<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', config('app.salon_name')); ?> | <?php echo e(config('app.salon_name')); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'Book premium salon services online in minutes.'); ?>">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

    <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if(session('success')): ?>
        <div class="container mt-3"><div class="alert alert--success" data-flash="success" data-auto-dismiss><?php echo e(session('success')); ?></div></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="container mt-3"><div class="alert alert--danger" data-flash="error" data-auto-dismiss><?php echo e(session('error')); ?></div></div>
    <?php endif; ?>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div id="toast-container"></div>

    <script src="<?php echo e(asset('js/app.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\smartsalonbooking\resources\views/layouts/app.blade.php ENDPATH**/ ?>