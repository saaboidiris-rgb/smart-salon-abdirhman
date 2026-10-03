<?php $__env->startSection('title', 'Categories'); ?>
<?php $__env->startSection('page_title', 'Categories'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex-between mb-3">
    <p class="text-muted mb-0">Group your services so customers can browse them more easily.</p>
    <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn--primary">➕ Add Category</a>
</div>

<div class="table-wrapper glass-card" style="padding:0;">
    <table class="table">
        <thead><tr><th>Name</th><th>Slug</th><th>Services</th><th></th></tr></thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($category->name); ?></td>
                    <td class="text-muted"><?php echo e($category->slug); ?></td>
                    <td><?php echo e($category->services_count); ?></td>
                    <td>
                        <div class="row-actions">
                            <a href="<?php echo e(route('admin.categories.edit', $category)); ?>" class="btn btn--ghost btn--sm">Edit</a>
                            <form method="POST" action="<?php echo e(route('admin.categories.destroy', $category)); ?>"
                                  data-confirm-submit data-confirm-title="Delete this category?" data-confirm-label="Delete">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn--danger btn--sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4" class="text-center text-muted">No categories yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php echo e($categories->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\saabi\Desktop\thesis\smartsalonbooking\smartsalonbooking\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>