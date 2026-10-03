<?php $__env->startSection('title', $service->name); ?>

<?php $__env->startSection('content'); ?>
<section class="section">
    <div class="container">
        <div class="grid grid-2" style="align-items:flex-start;">
            <img src="<?php echo e($service->image ? asset('storage/'.$service->image) : 'https://images.unsplash.com/photo-1522337660859-02fbefca4702?auto=format&fit=crop&w=700&q=70'); ?>"
                 alt="<?php echo e($service->name); ?>" style="border-radius:var(--radius-lg); box-shadow:var(--shadow-lg);">

            <div>
                <span class="badge badge--active"><?php echo e($service->category->name); ?></span>
                <h1 class="mt-1"><?php echo e($service->name); ?></h1>
                <p class="lead"><?php echo e($service->description); ?></p>

                <div class="glass-card">
                    <div class="flex-between mb-2">
                        <span class="text-muted">Duration</span>
                        <strong><i class="fa-regular fa-clock"></i> <?php echo e($service->formattedDuration()); ?></strong>
                    </div>
                    <div class="flex-between mb-3">
                        <span class="text-muted">Price</span>
                        <span class="service-card__price">$<?php echo e(number_format($service->price)); ?></span>
                    </div>
                    <a href="<?php echo e(route('booking.create')); ?>" class="btn btn--primary btn--block">Book This Service</a>
                </div>

                <?php if($service->employees->count()): ?>
                    <h4 class="mt-3">Specialists who offer this</h4>
                    <div class="flex gap-sm" style="flex-wrap:wrap;">
                        <?php $__currentLoopData = $service->employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="badge badge--completed"><?php echo e($employee->name); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if($related->count()): ?>
            <div class="section__header mt-4">
                <h2 class="section__title">You Might Also Like</h2>
            </div>
            <div class="grid grid-3">
                <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="glass-card glass-card--hover service-card">
                        <div class="service-card__body">
                            <h3><?php echo e($r->name); ?></h3>
                            <div class="service-card__meta">
                                <span><i class="fa-regular fa-clock"></i> <?php echo e($r->formattedDuration()); ?></span>
                                <span class="service-card__price">$<?php echo e(number_format($r->price)); ?></span>
                            </div>
                            <a href="<?php echo e(route('services.show', $r)); ?>" class="btn btn--outline btn--sm w-full mt-2">View</a>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\smartsalonbooking\resources\views/service-show.blade.php ENDPATH**/ ?>