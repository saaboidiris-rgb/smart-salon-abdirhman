<?php $__env->startSection('title', 'My Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="dash-topbar">
    <h1>Welcome back, <?php echo e(explode(' ', auth()->user()->name)[0]); ?> 👋</h1>
    <a href="<?php echo e(route('booking.create')); ?>" class="btn btn--primary"><i class="fa-solid fa-plus"></i> Book New Service</a>
</div>

<div class="grid grid-3 mb-3">
    <div class="glass-card stat-card">
        <div class="stat-card__icon"><i class="fa-solid fa-calendar-check text-primary"></i></div>
        <div><div class="stat-card__value"><?php echo e($totalBookings); ?></div><div class="stat-card__label">Total Bookings</div></div>
    </div>
    <div class="glass-card stat-card">
        <div class="stat-card__icon"><i class="fa-solid fa-hourglass-half text-primary"></i></div>
        <div><div class="stat-card__value"><?php echo e($upcomingCount); ?></div><div class="stat-card__label">Upcoming</div></div>
    </div>
    <div class="glass-card stat-card">
        <div class="stat-card__icon"><i class="fa-solid fa-circle-check text-primary"></i></div>
        <div><div class="stat-card__value"><?php echo e($completedCount); ?></div><div class="stat-card__label">Completed</div></div>
    </div>
</div>

<div class="grid grid-2" style="align-items:flex-start;">
    <div class="glass-card">
        <h3>Upcoming Appointments</h3>
        <?php $__empty_1 = true; $__currentLoopData = $upcoming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex-between" style="padding:12px 0; border-bottom:1px solid rgba(0,0,0,.06);">
                <div>
                    <strong><?php echo e($appointment->service->name); ?></strong>
                    <div class="text-muted" style="font-size:.85rem;">
                        <?php echo e($appointment->appointment_date->format('d M Y')); ?> at <?php echo e(\Illuminate\Support\Carbon::parse($appointment->start_time)->format('g:i A')); ?>

                        with <?php echo e($appointment->employee->name); ?>

                    </div>
                </div>
                <span class="badge badge--<?php echo e($appointment->status); ?>"><?php echo e(ucfirst($appointment->status)); ?></span>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-muted">No upcoming appointments. <a href="<?php echo e(route('booking.create')); ?>">Book one now</a>.</p>
        <?php endif; ?>
        <a href="<?php echo e(route('customer.appointments.index')); ?>" class="btn btn--outline btn--sm mt-2">View All Appointments</a>
    </div>

    <div class="glass-card">
        <h3>Notifications</h3>
        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="padding:12px 0; border-bottom:1px solid rgba(0,0,0,.06);">
                <strong><?php echo e($note->title); ?></strong>
                <p class="mb-0" style="font-size:.85rem;"><?php echo e($note->message); ?></p>
                <span class="text-muted" style="font-size:.75rem;"><?php echo e($note->created_at->diffForHumans()); ?></span>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-muted">No notifications yet.</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\smartsalonbooking\resources\views/customer/dashboard.blade.php ENDPATH**/ ?>