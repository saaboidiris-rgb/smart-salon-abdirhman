<?php $__env->startSection('title', 'Booking Confirmed'); ?>

<?php $__env->startSection('content'); ?>
<section class="booking-page" style="padding-top:80px;">
    <div class="container" style="max-width:600px;">

        
        <div style="text-align:center; margin-bottom:36px;">
            <div style="width:80px;height:80px;border-radius:50%;background:var(--color-secondary);display:flex;align-items:center;justify-content:center;margin:0 auto 18px;box-shadow:0 0 0 10px rgba(157,27,86,.08);">
                <i class="fa-solid fa-circle-check" style="font-size:2.4rem;color:var(--color-success);"></i>
            </div>
            <h1 style="font-size:2rem;margin-bottom:8px;">Booking Confirmed!</h1>
            <p class="text-muted">We've sent the details to your account. Here's your booking reference:</p>
            <div class="badge badge--confirmed" style="font-size:1.1rem;padding:10px 26px;margin-top:10px;display:inline-block;">
                <?php echo e($appointment->booking_number); ?>

            </div>
        </div>

        
        <div class="receipt-card slide-up">
            <div class="receipt-header">
                <span class="receipt-icon"><i class="fa-solid fa-receipt"></i></span>
                <div>
                    <div class="receipt-salon-name">Smart Salon</div>
                    <div class="text-muted" style="font-size:.82rem;">Appointment Summary</div>
                </div>
            </div>

            <hr class="receipt-divider">

            <div class="receipt-body">
                <div class="receipt-row">
                    <span><i class="fa-solid fa-scissors"></i> Service</span>
                    <strong><?php echo e($appointment->service->name); ?></strong>
                </div>
                <div class="receipt-row">
                    <span><i class="fa-solid fa-user-tie"></i> Specialist</span>
                    <strong><?php echo e($appointment->employee->name); ?></strong>
                </div>
                <div class="receipt-row">
                    <span><i class="fa-regular fa-calendar"></i> Date</span>
                    <strong><?php echo e($appointment->appointment_date->format('l, d M Y')); ?></strong>
                </div>
                <div class="receipt-row">
                    <span><i class="fa-regular fa-clock"></i> Time</span>
                    <strong>
                        <?php echo e(\Illuminate\Support\Carbon::parse($appointment->start_time)->format('g:i A')); ?>

                        – <?php echo e(\Illuminate\Support\Carbon::parse($appointment->end_time)->format('g:i A')); ?>

                    </strong>
                </div>
                <div class="receipt-row">
                    <span><i class="fa-regular fa-circle-dot"></i> Status</span>
                    <strong><span class="badge badge--<?php echo e($appointment->status); ?>"><?php echo e(ucfirst($appointment->status)); ?></span></strong>
                </div>
            </div>

            <hr class="receipt-divider">

            <div class="receipt-footer">
                <span>Total Due</span>
                <span class="receipt-total">$<?php echo e(number_format($appointment->price, 2)); ?></span>
            </div>
        </div>

        
        <div class="flex gap-sm mt-3" style="justify-content:center;">
            <a href="<?php echo e(route('customer.dashboard')); ?>" class="btn btn--primary">
                <i class="fa-solid fa-gauge-high"></i> My Dashboard
            </a>
            <a href="<?php echo e(route('home')); ?>" class="btn btn--outline">
                <i class="fa-solid fa-house"></i> Back to Home
            </a>
        </div>

    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\smartsalonbooking\resources\views/booking/confirmation.blade.php ENDPATH**/ ?>