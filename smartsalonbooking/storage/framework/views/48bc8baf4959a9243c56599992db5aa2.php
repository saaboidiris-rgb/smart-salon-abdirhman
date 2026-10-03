<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page_title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>

<div class="grid grid-4 mb-3">
    <div class="glass-card stat-card">
        <div class="stat-card__icon"><i class="fa-solid fa-users text-primary"></i></div>
        <div><div class="stat-card__value"><?php echo e($stats['total_customers']); ?></div><div class="stat-card__label">Total Customers</div></div>
    </div>
    <div class="glass-card stat-card">
        <div class="stat-card__icon"><i class="fa-solid fa-calendar-day text-primary"></i></div>
        <div><div class="stat-card__value"><?php echo e($stats['today_bookings']); ?></div><div class="stat-card__label">Today's Bookings</div></div>
    </div>
    <div class="glass-card stat-card">
        <div class="stat-card__icon"><i class="fa-solid fa-circle-check text-primary"></i></div>
        <div><div class="stat-card__value"><?php echo e($stats['completed_services']); ?></div><div class="stat-card__label">Completed Services</div></div>
    </div>
    <div class="glass-card stat-card">
        <div class="stat-card__icon"><i class="fa-solid fa-dollar-sign text-primary"></i></div>
        <div><div class="stat-card__value">$<?php echo e(number_format($stats['revenue'])); ?></div><div class="stat-card__label">Revenue</div></div>
    </div>
</div>

<div class="grid grid-2 mb-3">
    <div class="glass-card chart-card">
        <h3>Revenue - Last 7 Days</h3>
        <canvas id="revenueChart" data-labels='<?php echo e($chartLabels->toJson()); ?>' data-values='<?php echo e($revenuePerDay->toJson()); ?>'></canvas>
    </div>
    <div class="glass-card chart-card">
        <h3>Bookings - Last 7 Days</h3>
        <canvas id="bookingsChart" data-labels='<?php echo e($chartLabels->toJson()); ?>' data-values='<?php echo e($bookingsPerDay->toJson()); ?>'></canvas>
    </div>
</div>

<div class="grid grid-2" style="align-items:flex-start;">
    <div class="glass-card">
        <h3>Recent Bookings</h3>
        <div class="table-wrapper" style="background:transparent;">
            <table class="table">
                <thead><tr><th>#</th><th>Customer</th><th>Service</th><th>Date</th><th>Status</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($booking->booking_number); ?></td>
                            <td><?php echo e($booking->customer?->user?->name ?? '—'); ?></td>
                            <td><?php echo e($booking->service->name); ?></td>
                            <td><?php echo e($booking->appointment_date->format('d M')); ?></td>
                            <td><span class="badge badge--<?php echo e($booking->status); ?>"><?php echo e(ucfirst($booking->status)); ?></span></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="5" class="text-center text-muted">No bookings yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <a href="<?php echo e(route('admin.appointments.index')); ?>" class="btn btn--outline btn--sm mt-2">View All Bookings</a>
    </div>

    <div class="glass-card">
        <h3>Upcoming Schedule</h3>
        <?php $__empty_1 = true; $__currentLoopData = $upcomingSchedule; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex-between" style="padding:10px 0; border-bottom:1px solid rgba(0,0,0,.06);">
                <div>
                    <strong><?php echo e($booking->service->name); ?></strong>
                    <div class="text-muted" style="font-size:.82rem;"><?php echo e($booking->customer?->user?->name); ?> · <?php echo e($booking->employee->name); ?></div>
                </div>
                <div class="text-muted" style="font-size:.82rem;"><?php echo e($booking->appointment_date->format('d M')); ?>, <?php echo e(\Illuminate\Support\Carbon::parse($booking->start_time)->format('g:i A')); ?></div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-muted">Nothing scheduled yet.</p>
        <?php endif; ?>
    </div>
</div>

<div class="glass-card mt-3">
    <h3>Recent Customers</h3>
    <div class="table-wrapper" style="background:transparent;">
        <table class="table">
            <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Joined</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recentCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($customer->user->name); ?></td>
                        <td><?php echo e($customer->user->email); ?></td>
                        <td><?php echo e($customer->user->phone); ?></td>
                        <td><?php echo e($customer->created_at->format('d M Y')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4" class="text-center text-muted">No customers yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>
<script src="<?php echo e(asset('js/admin-charts.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\saabi\Desktop\thesis\smartsalonbooking\smartsalonbooking\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>