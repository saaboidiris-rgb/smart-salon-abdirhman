<?php $__env->startSection('title', 'Services'); ?>
<?php $__env->startSection('page_title', 'Services'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex-between mb-3">
    <p class="text-muted mb-0">Manage everything customers can book.</p>
    <a href="<?php echo e(route('admin.services.create')); ?>" class="btn btn--primary"><i class="fa-solid fa-plus"></i> Add Service</a>
</div>

<form method="GET" class="filter-bar glass-card">
    <input type="text" name="search" class="form-control" placeholder="Search services…" value="<?php echo e(request('search')); ?>">
    <select name="category" class="form-control">
        <option value="">All Categories</option>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($c->id); ?>" <?php if(request('category') == $c->id): echo 'selected'; endif; ?>><?php echo e($c->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    <select name="status" class="form-control">
        <option value="">All Statuses</option>
        <option value="active" <?php if(request('status') === 'active'): echo 'selected'; endif; ?>>Active</option>
        <option value="inactive" <?php if(request('status') === 'inactive'): echo 'selected'; endif; ?>>Inactive</option>
    </select>
    <button type="submit" class="btn btn--primary btn--sm">Filter</button>
</form>

<div class="table-wrapper glass-card" style="padding:0;">
    <table class="table">
        <thead><tr><th>Service</th><th>Category</th><th>Duration</th><th>Price</th><th>Status</th><th></th></tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($service->name); ?></td>
                    <td><?php echo e($service->category->name); ?></td>
                    <td><?php echo e($service->formattedDuration()); ?></td>
                    <td>$<?php echo e(number_format($service->price)); ?></td>
                    <td><span class="badge badge--<?php echo e($service->status); ?>"><?php echo e(ucfirst($service->status)); ?></span></td>
                    <td>
                        <div class="row-actions">
                            <a href="<?php echo e(route('admin.services.edit', $service)); ?>" class="btn btn--ghost btn--sm">Edit</a>
                            <form method="POST" action="<?php echo e(route('admin.services.destroy', $service)); ?>"
                                  data-confirm-submit data-confirm-title="Delete this service?" data-confirm-label="Delete">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn--danger btn--sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center text-muted">No services found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php echo e($services->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\saabi\Desktop\thesis\smartsalonbooking\smartsalonbooking\resources\views/admin/services/index.blade.php ENDPATH**/ ?>