<header class="navbar">
    <div class="navbar__inner">
        <a href="<?php echo e(route('home')); ?>" class="navbar__brand"><i class="fa-solid fa-wand-magic-sparkles text-primary"></i> <?php echo e(config('app.salon_name')); ?><span>.</span></a>

        <nav class="navbar__links">
            <a href="<?php echo e(route('home')); ?>" class="navbar__link <?php echo e(request()->routeIs('home') ? 'is-active' : ''); ?>">Home</a>
            <a href="<?php echo e(route('services.index')); ?>" class="navbar__link <?php echo e(request()->routeIs('services.*') ? 'is-active' : ''); ?>">Services</a>
            <a href="<?php echo e(route('team.index')); ?>" class="navbar__link <?php echo e(request()->routeIs('team.*') ? 'is-active' : ''); ?>">Team</a>
            <a href="<?php echo e(route('gallery.index')); ?>" class="navbar__link <?php echo e(request()->routeIs('gallery.*') ? 'is-active' : ''); ?>">Gallery</a>
            <a href="<?php echo e(route('pricing.index')); ?>" class="navbar__link <?php echo e(request()->routeIs('pricing.*') ? 'is-active' : ''); ?>">Pricing</a>
            <a href="<?php echo e(route('contact.index')); ?>" class="navbar__link <?php echo e(request()->routeIs('contact.*') ? 'is-active' : ''); ?>">Contact</a>
        </nav>

        <div class="navbar__actions">
            <?php if(auth()->guard()->check()): ?>
                <?php if(auth()->user()->canAccessAdmin()): ?>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn--ghost btn--sm">Admin Panel</a>
                <?php else: ?>
                    <a href="<?php echo e(route('customer.dashboard')); ?>" class="btn btn--ghost btn--sm">My Account</a>
                <?php endif; ?>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn--outline btn--sm">Logout</button>
                </form>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="btn btn--ghost btn--sm">Login</a>
            <?php endif; ?>
            <a href="<?php echo e(route('booking.create')); ?>" class="btn btn--primary btn--sm">Book Now</a>
            <button type="button" class="navbar__toggle" data-mobile-toggle aria-label="Toggle menu"><i class="fa-solid fa-bars"></i></button>
        </div>
    </div>

    <div class="mobile-menu" data-mobile-menu>
        <a href="<?php echo e(route('home')); ?>">Home</a>
        <a href="<?php echo e(route('services.index')); ?>">Services</a>
        <a href="<?php echo e(route('team.index')); ?>">Team</a>
        <a href="<?php echo e(route('gallery.index')); ?>">Gallery</a>
        <a href="<?php echo e(route('pricing.index')); ?>">Pricing</a>
        <a href="<?php echo e(route('testimonials.index')); ?>">Testimonials</a>
        <a href="<?php echo e(route('contact.index')); ?>">Contact</a>
        <?php if(auth()->guard()->check()): ?>
            <?php if(auth()->user()->canAccessAdmin()): ?>
                <a href="<?php echo e(route('admin.dashboard')); ?>">Admin Panel</a>
            <?php else: ?>
                <a href="<?php echo e(route('customer.dashboard')); ?>">My Account</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="<?php echo e(route('login')); ?>">Login</a>
            <a href="<?php echo e(route('register')); ?>">Register</a>
        <?php endif; ?>
        <a href="<?php echo e(route('booking.create')); ?>">Book Now</a>
    </div>
</header>
<?php /**PATH C:\Users\saabi\Desktop\thesis\smartsalonbooking\smartsalonbooking\resources\views/components/navbar.blade.php ENDPATH**/ ?>