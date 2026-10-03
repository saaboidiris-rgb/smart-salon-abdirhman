<?php if($paginator->hasPages()): ?>
    <nav class="flex-center gap-sm mt-4" aria-label="Pagination">
        <?php if($paginator->onFirstPage()): ?>
            <span class="btn btn--ghost btn--sm" style="opacity:.5;">← Prev</span>
        <?php else: ?>
            <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="btn btn--ghost btn--sm">← Prev</a>
        <?php endif; ?>

        <span class="text-muted" style="font-size:.85rem;">
            Page <?php echo e($paginator->currentPage()); ?> of <?php echo e($paginator->lastPage()); ?>

        </span>

        <?php if($paginator->hasMorePages()): ?>
            <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="btn btn--ghost btn--sm">Next →</a>
        <?php else: ?>
            <span class="btn btn--ghost btn--sm" style="opacity:.5;">Next →</span>
        <?php endif; ?>
    </nav>
<?php endif; ?>
<?php /**PATH C:\Users\saabi\Desktop\thesis\smartsalonbooking\smartsalonbooking\resources\views/vendor/pagination/custom.blade.php ENDPATH**/ ?>