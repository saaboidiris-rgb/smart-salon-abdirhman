<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'My Account'); ?> | <?php echo e(config('app.salon_name')); ?></title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
<div class="dash-shell">
    <aside class="dash-sidebar">
        <a href="<?php echo e(route('home')); ?>" class="dash-sidebar__brand"><i class="fa-solid fa-wand-magic-sparkles text-primary"></i> <?php echo e(config('app.salon_name')); ?></a>
        <nav class="dash-sidebar__nav">
            <a href="<?php echo e(route('customer.dashboard')); ?>" class="<?php echo e(request()->routeIs('customer.dashboard') ? 'is-active' : ''); ?>"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="<?php echo e(route('customer.appointments.index')); ?>" class="<?php echo e(request()->routeIs('customer.appointments.*') ? 'is-active' : ''); ?>"><i class="fa-solid fa-calendar-check"></i> My Appointments</a>
            <a href="<?php echo e(route('booking.create')); ?>"><i class="fa-solid fa-plus"></i> Book New Service</a>
            <a href="<?php echo e(route('customer.profile.edit')); ?>" class="<?php echo e(request()->routeIs('customer.profile.*') ? 'is-active' : ''); ?>"><i class="fa-solid fa-user"></i> Profile</a>
        </nav>
        <div class="dash-sidebar__footer">
            <a href="<?php echo e(route('home')); ?>"><i class="fa-solid fa-arrow-left"></i> Back to site</a>
            <form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-1">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn--outline btn--sm w-full">Logout</button>
            </form>
        </div>
    </aside>

    <div class="dash-main">
        <?php if(session('success')): ?>
            <div class="alert alert--success" data-flash="success" data-auto-dismiss><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert--danger" data-flash="error" data-auto-dismiss><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </div>
</div>

<div id="toast-container"></div>
<script src="<?php echo e(asset('js/app.js')); ?>"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\smartsalonbooking\resources\views/layouts/dashboard.blade.php ENDPATH**/ ?>