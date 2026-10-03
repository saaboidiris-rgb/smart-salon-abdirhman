<?php $__env->startSection('title', 'Book an Appointment'); ?>

<?php $__env->startSection('content'); ?>
<section class="booking-page">
    <div class="container" style="max-width:900px;">

        
        <div class="booking-page__header">
            <div class="booking-page__eyebrow"><i class="fa-solid fa-scissors"></i> Smart Salon</div>
            <h1 class="booking-page__title">Book Your Appointment</h1>
            <p class="booking-page__sub">Walk through 5 simple steps and secure your slot in seconds.</p>
        </div>

        
        <div class="booking-tracker" id="booking-tracker">
            <div class="booking-tracker__line"><div class="booking-tracker__progress" id="tracker-progress"></div></div>
            <div class="booking-step-pill" data-step-indicator="1">
                <div class="booking-step-pill__circle"><i class="fa-solid fa-scissors"></i></div>
                <span class="booking-step-pill__label">Service</span>
            </div>
            <div class="booking-step-pill" data-step-indicator="2">
                <div class="booking-step-pill__circle"><i class="fa-solid fa-user-tie"></i></div>
                <span class="booking-step-pill__label">Specialist</span>
            </div>
            <div class="booking-step-pill" data-step-indicator="3">
                <div class="booking-step-pill__circle"><i class="fa-solid fa-calendar-days"></i></div>
                <span class="booking-step-pill__label">Date & Time</span>
            </div>
            <div class="booking-step-pill" data-step-indicator="4">
                <div class="booking-step-pill__circle"><i class="fa-solid fa-user-pen"></i></div>
                <span class="booking-step-pill__label">Details</span>
            </div>
            <div class="booking-step-pill" data-step-indicator="5">
                <div class="booking-step-pill__circle"><i class="fa-solid fa-clipboard-check"></i></div>
                <span class="booking-step-pill__label">Confirm</span>
            </div>
        </div>

        <?php if($errors->any()): ?>
            <div class="alert alert--danger mb-3">
                <strong><i class="fa-solid fa-circle-exclamation"></i> Please fix the following:</strong>
                <ul class="mt-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('booking.store')); ?>" id="booking-form" data-slots-url="<?php echo e(route('booking.slots')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="appointment_date" id="appointment_date_hidden" value="<?php echo e(old('appointment_date', $minDate)); ?>">

            <div class="booking-slides-container">

                
                <div class="booking-slide glass-card" data-step="1">
                    <div class="booking-slide__head">
                        <div class="booking-slide__step-badge">Step 1 of 5</div>
                        <h2 class="booking-slide__title"><i class="fa-solid fa-scissors"></i> Choose a Service</h2>
                        <p class="booking-slide__desc text-muted">Select the treatment you'd like to book.</p>
                    </div>

                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($category->services->count()): ?>
                            <div class="service-category-label"><?php echo e($category->name); ?></div>
                            <div class="option-grid mb-3">
                                <?php $__currentLoopData = $category->services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="option-card" for="service_<?php echo e($service->id); ?>">
                                        <input type="radio" id="service_<?php echo e($service->id); ?>" name="service_id" value="<?php echo e($service->id); ?>"
                                               data-duration="<?php echo e($service->duration_minutes); ?>"
                                               data-price="<?php echo e($service->price); ?>"
                                               data-name="<?php echo e($service->name); ?>"
                                               <?php if(old('service_id') == $service->id): echo 'checked'; endif; ?>>
                                        <div class="option-card__select-dot"></div>
                                        <div class="option-card__service-icon"><i class="fa-solid fa-spa"></i></div>
                                        <strong class="option-card__name"><?php echo e($service->name); ?></strong>
                                        <div class="option-card__meta">
                                            <span><i class="fa-regular fa-clock"></i> <?php echo e($service->formattedDuration()); ?></span>
                                            <span class="option-card__price">$<?php echo e(number_format($service->price, 2)); ?></span>
                                        </div>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="booking-slide__nav">
                        <span></span>
                        <button type="button" class="btn btn--primary btn-next-glow" data-next-step disabled>
                            Next: Specialist <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                
                <div class="booking-slide glass-card" data-step="2">
                    <div class="booking-slide__head">
                        <div class="booking-slide__step-badge">Step 2 of 5</div>
                        <h2 class="booking-slide__title"><i class="fa-solid fa-user-tie"></i> Pick Your Specialist</h2>
                        <p class="booking-slide__desc text-muted">Only specialists who offer your selected service are shown.</p>
                    </div>

                    <div class="option-grid option-grid--specialists" id="employee-options">
                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="option-card option-card--specialist" for="employee_<?php echo e($employee->id); ?>"
                                   data-services="<?php echo e($employee->services->pluck('id')->implode(',')); ?>">
                                <input type="radio" id="employee_<?php echo e($employee->id); ?>" name="employee_id"
                                       value="<?php echo e($employee->id); ?>" data-name="<?php echo e($employee->name); ?>"
                                       <?php if(old('employee_id') == $employee->id): echo 'checked'; endif; ?>>
                                <div class="option-card__select-dot"></div>
                                <div class="option-card__avatar-wrapper">
                                    <img src="<?php echo e($employee->photo ? asset('storage/'.$employee->photo) : 'https://i.pravatar.cc/100?u='.$employee->id); ?>"
                                         alt="<?php echo e($employee->name); ?>" class="option-card__avatar">
                                </div>
                                <strong class="option-card__name"><?php echo e($employee->name); ?></strong>
                                <div class="text-muted" style="font-size:.8rem;"><?php echo e($employee->specialization); ?></div>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="booking-slide__nav">
                        <button type="button" class="btn btn--ghost" data-prev-step>
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </button>
                        <button type="button" class="btn btn--primary btn-next-glow" data-next-step disabled>
                            Next: Date & Time <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                
                <div class="booking-slide glass-card" data-step="3">
                    <div class="booking-slide__head">
                        <div class="booking-slide__step-badge">Step 3 of 5</div>
                        <h2 class="booking-slide__title"><i class="fa-solid fa-calendar-days"></i> Pick a Date & Time</h2>
                        <p class="booking-slide__desc text-muted">Select your preferred date and available time slot.</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="appointment_date">
                            <i class="fa-regular fa-calendar"></i> Appointment Date
                        </label>
                        <div class="input-with-icon">
                            <i class="fa-regular fa-calendar-check input-icon"></i>
                            <input type="date" id="appointment_date" class="form-control"
                                   min="<?php echo e($minDate); ?>" value="<?php echo e(old('appointment_date', $minDate)); ?>"
                                   onchange="document.getElementById('appointment_date_hidden').value = this.value">
                        </div>
                    </div>

                    <div class="slot-section-heading">
                        <i class="fa-regular fa-clock"></i> Available Times
                    </div>
                    <div id="slot-grid" class="slot-container">
                        <div class="slot-empty-state">
                            <i class="fa-regular fa-calendar-xmark"></i>
                            <p>Choose a service, a specialist and a date to see open times.</p>
                        </div>
                    </div>
                    <input type="hidden" name="start_time" id="start_time_input" value="<?php echo e(old('start_time')); ?>">

                    <div class="booking-slide__nav">
                        <button type="button" class="btn btn--ghost" data-prev-step>
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </button>
                        <button type="button" class="btn btn--primary btn-next-glow" data-next-step disabled>
                            Next: Your Details <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                
                <div class="booking-slide glass-card" data-step="4">
                    <div class="booking-slide__head">
                        <div class="booking-slide__step-badge">Step 4 of 5</div>
                        <h2 class="booking-slide__title"><i class="fa-solid fa-user-pen"></i> Your Details</h2>
                        <p class="booking-slide__desc text-muted">We need a few details to complete your booking.</p>
                    </div>

                    <?php if(auth()->guard()->check()): ?>
                        <div class="auth-notice">
                            <i class="fa-solid fa-circle-user"></i>
                            <div>
                                <strong>Booking as <?php echo e(auth()->user()->name); ?></strong>
                                <span class="text-muted"><?php echo e(auth()->user()->email); ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-3" style="font-size:.9rem;">
                            <i class="fa-solid fa-shield-halved"></i>
                            Creating an account lets you view, cancel or reschedule this booking anytime.
                        </p>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="name">Full Name</label>
                                <div class="input-with-icon">
                                    <i class="fa-solid fa-user input-icon"></i>
                                    <input type="text" id="name" name="name"
                                           class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('name')); ?>" placeholder="Jane Doe" required>
                                </div>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="email">Email Address</label>
                                <div class="input-with-icon">
                                    <i class="fa-solid fa-envelope input-icon"></i>
                                    <input type="email" id="email" name="email"
                                           class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('email')); ?>" placeholder="jane@email.com" required>
                                </div>
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="phone">Phone Number</label>
                                <div class="input-with-icon">
                                    <i class="fa-solid fa-phone input-icon"></i>
                                    <input type="text" id="phone" name="phone"
                                           class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('phone')); ?>" placeholder="+1 555 000 0000" required>
                                </div>
                                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="password">Create a Password</label>
                                <div class="input-with-icon">
                                    <i class="fa-solid fa-lock input-icon"></i>
                                    <input type="password" id="password" name="password"
                                           class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           placeholder="At least 8 characters" required>
                                </div>
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="form-error"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                            <div class="input-with-icon">
                                <i class="fa-solid fa-lock-open input-icon"></i>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="form-control" placeholder="Re-enter your password" required>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label class="form-label" for="notes">
                            <i class="fa-regular fa-note-sticky"></i> Notes (optional)
                        </label>
                        <textarea id="notes" name="notes" class="form-control"
                                  rows="3" placeholder="Any special requests or things we should know…"><?php echo e(old('notes')); ?></textarea>
                    </div>

                    <div class="booking-slide__nav">
                        <button type="button" class="btn btn--ghost" data-prev-step>
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </button>
                        <button type="button" class="btn btn--primary btn-next-glow" data-next-step>
                            Review Booking <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                
                <div class="booking-slide glass-card" data-step="5">
                    <div class="booking-slide__head">
                        <div class="booking-slide__step-badge">Step 5 of 5</div>
                        <h2 class="booking-slide__title"><i class="fa-solid fa-clipboard-check"></i> Review & Confirm</h2>
                        <p class="booking-slide__desc text-muted">Everything look good? Confirm to lock in your appointment.</p>
                    </div>

                    <div id="booking-summary-box">
                        <p class="text-muted">Complete the previous steps to see your summary.</p>
                    </div>

                    <div class="booking-slide__nav">
                        <button type="button" class="btn btn--ghost" data-prev-step>
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </button>
                        <button type="submit" class="btn btn--primary btn--lg confirm-btn">
                            <i class="fa-solid fa-check-double"></i> Confirm & Book
                        </button>
                    </div>
                </div>

            </div>
        </form>

    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/booking.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\smartsalonbooking\resources\views/booking/create.blade.php ENDPATH**/ ?>