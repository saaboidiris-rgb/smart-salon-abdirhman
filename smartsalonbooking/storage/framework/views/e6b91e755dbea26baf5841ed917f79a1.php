<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Admin'); ?> | <?php echo e(config('app.salon_name')); ?></title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
<div class="dash-shell">
    <aside class="dash-sidebar">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="dash-sidebar__brand"><i class="fa-solid fa-wand-magic-sparkles text-primary"></i> <?php echo e(config('app.salon_name')); ?> Admin</a>
        <nav class="dash-sidebar__nav">
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="<?php echo e(request()->routeIs('admin.dashboard') ? 'is-active' : ''); ?>"><i class="fa-solid fa-chart-line"></i> Dashboard</a>

            <div class="dash-sidebar__section">Bookings</div>
            <a href="<?php echo e(route('admin.appointments.index')); ?>" class="<?php echo e(request()->routeIs('admin.appointments.*') ? 'is-active' : ''); ?>"><i class="fa-solid fa-calendar-check"></i> Appointments</a>

            <div class="dash-sidebar__section">Catalog</div>
            <a href="<?php echo e(route('admin.services.index')); ?>" class="<?php echo e(request()->routeIs('admin.services.*') ? 'is-active' : ''); ?>"><i class="fa-solid fa-scissors"></i> Services</a>
            <a href="<?php echo e(route('admin.categories.index')); ?>" class="<?php echo e(request()->routeIs('admin.categories.*') ? 'is-active' : ''); ?>"><i class="fa-solid fa-tags"></i> Categories</a>
            <a href="<?php echo e(route('admin.employees.index')); ?>" class="<?php echo e(request()->routeIs('admin.employees.*') ? 'is-active' : ''); ?>"><i class="fa-solid fa-user-tie"></i> Employees</a>

            <div class="dash-sidebar__section">People</div>
            <a href="<?php echo e(route('admin.customers.index')); ?>" class="<?php echo e(request()->routeIs('admin.customers.*') ? 'is-active' : ''); ?>"><i class="fa-solid fa-users"></i> Customers</a>

            <div class="dash-sidebar__section">Content</div>
            <a href="<?php echo e(route('admin.gallery.index')); ?>" class="<?php echo e(request()->routeIs('admin.gallery.*') ? 'is-active' : ''); ?>"><i class="fa-solid fa-images"></i> Gallery</a>
            <a href="<?php echo e(route('admin.testimonials.index')); ?>" class="<?php echo e(request()->routeIs('admin.testimonials.*') ? 'is-active' : ''); ?>"><i class="fa-solid fa-comment-dots"></i> Testimonials</a>
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
        <div class="dash-topbar">
            <h1><?php echo $__env->yieldContent('page_title', 'Dashboard'); ?></h1>
            <div class="dash-user">
                <span>👋 <?php echo e(auth()->user()->name); ?> <span class="badge badge--active"><?php echo e(ucfirst(auth()->user()->role)); ?></span></span>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert--success" data-flash="success" data-auto-dismiss><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert--danger" data-flash="error" data-auto-dismiss><?php echo e(session('error')); ?></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="alert alert--danger">
                <strong>Please fix the following:</strong>
                <ul class="mt-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </div>
</div>

<div id="toast-container"></div>
<script src="<?php echo e(asset('js/app.js')); ?>"></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\saabi\Desktop\thesis\smartsalonbooking\smartsalonbooking\resources\views/layouts/admin.blade.php ENDPATH**/ ?>