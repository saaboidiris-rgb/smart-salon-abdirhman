<?php $__env->startSection('title', 'Page Not Found'); ?>

<?php $__env->startSection('content'); ?>
<section class="section text-center">
    <div class="container">
        <div class="glass-card" style="max-width:520px; margin:0 auto; padding:60px 40px;">
            <h1 style="font-size:4rem;">404</h1>
            <h2>Page Not Found</h2>
            <p class="mb-3">The page you're looking for doesn't exist or may have moved.</p>
            <a href="<?php echo e(route('home')); ?>" class="btn btn--primary">Back to Home</a>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\saabi\Desktop\thesis\smartsalonbooking\smartsalonbooking\resources\views/errors/404.blade.php ENDPATH**/ ?>