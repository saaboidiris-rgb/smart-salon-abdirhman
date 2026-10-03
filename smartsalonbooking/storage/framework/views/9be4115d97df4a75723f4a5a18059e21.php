<?php $__env->startSection('title', $category->exists ? 'Edit Category' : 'Add Category'); ?>
<?php $__env->startSection('page_title', $category->exists ? 'Edit Category' : 'Add Category'); ?>

<?php $__env->startSection('content'); ?>
<div class="glass-card" style="max-width:560px;">
    <form method="POST" action="<?php echo e($category->exists ? route('admin.categories.update', $category) : route('admin.categories.store')); ?>">
        <?php echo csrf_field(); ?>
        <?php if($category->exists): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

        <div class="form-group">
            <label class="form-label" for="name">Category name</label>
            <input type="text" id="name" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name', $category->name)); ?>" required>
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
            <label class="form-label" for="description">Description</label>
            <textarea id="description" name="description" class="form-control"><?php echo e(old('description', $category->description)); ?></textarea>
        </div>

        <div class="flex-between">
            <a href="<?php echo e(route('admin.categories.index')); ?>" class="btn btn--ghost">Cancel</a>
            <button type="submit" class="btn btn--primary"><?php echo e($category->exists ? 'Save Changes' : 'Create Category'); ?></button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\saabi\Desktop\thesis\smartsalonbooking\smartsalonbooking\resources\views/admin/categories/form.blade.php ENDPATH**/ ?>