<?php $__env->startSection('title', 'Our Services'); ?>

<?php $__env->startSection('content'); ?>
<section class="section" style="padding-bottom:20px;">
    <div class="container">
        <div class="section__header">
            <span class="section__eyebrow">Full menu</span>
            <h2 class="section__title">Our Services</h2>
            <p>Every treatment we offer, with transparent pricing and durations.</p>
        </div>

        <form method="GET" action="<?php echo e(route('services.index')); ?>" class="filter-bar glass-card">
            <input type="text" name="search" class="form-control" placeholder="Search services…" value="<?php echo e(request('search')); ?>">
            <select name="category" class="form-control">
                <option value="">All Categories</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>" <?php if(request('category') == $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button type="submit" class="btn btn--primary btn--sm">Filter</button>
            <?php if(request('search') || request('category')): ?>
                <a href="<?php echo e(route('services.index')); ?>" class="btn btn--ghost btn--sm">Clear</a>
            <?php endif; ?>
        </form>
    </div>
</section>

<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="grid grid-3">
            <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="glass-card glass-card--hover service-card">
                    <img class="service-card__image" src="<?php echo e($service->image ? asset('storage/'.$service->image) : 'https://images.unsplash.com/photo-1522337660859-02fbefca4702?auto=format&fit=crop&w=500&q=60'); ?>" alt="<?php echo e($service->name); ?>">
                    <div class="service-card__body">
                        <span class="badge badge--active"><?php echo e($service->category->name); ?></span>
                        <h3 class="mt-1"><?php echo e($service->name); ?></h3>
                        <p><?php echo e(\Illuminate\Support\Str::limit($service->description, 90)); ?></p>
                        <div class="service-card__meta">
                            <span><i class="fa-regular fa-clock"></i> <?php echo e($service->formattedDuration()); ?></span>
                            <span class="service-card__price">$<?php echo e(number_format($service->price)); ?></span>
                        </div>
                        <div class="flex gap-sm mt-2">
                            <a href="<?php echo e(route('services.show', $service)); ?>" class="btn btn--outline btn--sm w-full">Details</a>
                            <a href="<?php echo e(route('booking.create')); ?>" class="btn btn--primary btn--sm w-full">Book</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p>No services match your search. Try a different keyword or category.</p>
            <?php endif; ?>
        </div>

        <?php echo e($services->links()); ?>

    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\smartsalonbooking\resources\views/services.blade.php ENDPATH**/ ?>