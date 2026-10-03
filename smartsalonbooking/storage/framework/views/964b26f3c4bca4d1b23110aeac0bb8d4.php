<?php $__env->startSection('title', 'Employees'); ?>
<?php $__env->startSection('page_title', 'Employees'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex-between mb-3">
    <p class="text-muted mb-0">Your team of specialists.</p>
    <a href="<?php echo e(route('admin.employees.create')); ?>" class="btn btn--primary">➕ Add Employee</a>
</div>

<form method="GET" class="filter-bar glass-card">
    <input type="text" name="search" class="form-control" placeholder="Search employees…" value="<?php echo e(request('search')); ?>">
    <select name="status" class="form-control">
        <option value="">All Statuses</option>
        <option value="active" <?php if(request('status') === 'active'): echo 'selected'; endif; ?>>Active</option>
        <option value="inactive" <?php if(request('status') === 'inactive'): echo 'selected'; endif; ?>>Inactive</option>
    </select>
    <button type="submit" class="btn btn--primary btn--sm">Filter</button>
</form>

<div class="table-wrapper glass-card" style="padding:0;">
    <table class="table">
        <thead><tr><th>Name</th><th>Specialization</th><th>Phone</th><th>Bookings</th><th>Status</th><th></th></tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($employee->name); ?></td>
                    <td><?php echo e($employee->specialization); ?></td>
                    <td><?php echo e($employee->phone); ?></td>
                    <td><?php echo e($employee->appointments_count); ?></td>
                    <td><span class="badge badge--<?php echo e($employee->status); ?>"><?php echo e(ucfirst($employee->status)); ?></span></td>
                    <td>
                        <div class="row-actions">
                            <a href="<?php echo e(route('admin.employees.edit', $employee)); ?>" class="btn btn--ghost btn--sm">Edit</a>
                            <form method="POST" action="<?php echo e(route('admin.employees.destroy', $employee)); ?>"
                                  data-confirm-submit data-confirm-title="Remove this employee?" data-confirm-label="Delete">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn--danger btn--sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center text-muted">No employees yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php echo e($employees->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\saabi\Desktop\thesis\smartsalonbooking\smartsalonbooking\resources\views/admin/employees/index.blade.php ENDPATH**/ ?>