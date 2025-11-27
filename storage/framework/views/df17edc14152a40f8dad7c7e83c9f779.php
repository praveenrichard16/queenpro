

<?php $__env->startSection('title', 'Header Toolbar'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row g-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="h3 mb-0">Header Toolbar</h1>
                <p class="text-muted mb-0">Manage scrolling toolbar items displayed above the header.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('admin.cms.header.toolbar.settings')); ?>" class="btn btn-outline-secondary">
                    <iconify-icon icon="solar:settings-linear" class="me-1"></iconify-icon>
                    Settings
                </a>
                <a href="<?php echo e(route('admin.cms.header.toolbar.create')); ?>" class="btn btn-primary">
                    <iconify-icon icon="solar:add-circle-linear" class="me-1"></iconify-icon>
                    New Item
                </a>
            </div>
        </div>

        <div class="col-12">
            <div class="card radius-16 border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                            <tr>
                                <th style="width: 80px;">Order</th>
                                <th>Content</th>
                                <th>Link</th>
                                <th>Colors</th>
                                <th>Status</th>
                                <th>Updated</th>
                                <th class="text-end" style="width: 160px;">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo e($item->sort_order); ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if($item->icon): ?>
                                                <span style="font-size: 1.2rem;"><?php echo e($item->icon); ?></span>
                                            <?php endif; ?>
                                            <span class="fw-semibold"><?php echo e($item->text); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($item->link): ?>
                                            <a href="<?php echo e($item->link); ?>" target="_blank" class="text-decoration-none small">
                                                <iconify-icon icon="solar:link-external-linear" class="me-1"></iconify-icon>
                                                Link
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="small text-muted">BG:</span>
                                                <div class="rounded" style="width: 24px; height: 24px; background: <?php echo e($item->background_color ? ($item->background_color === 'gradient' ? 'linear-gradient(135deg, var(--brand-accent) 0%, #ff6b35 100%)' : $item->background_color) : 'linear-gradient(135deg, var(--brand-accent) 0%, #ff6b35 100%)'); ?>; border: 1px solid #ddd;"></div>
                                            </div>
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="small text-muted">Text:</span>
                                                <div class="rounded" style="width: 24px; height: 24px; background: <?php echo e($item->text_color ?: '#ffffff'); ?>; border: 1px solid #ddd;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($item->is_active): ?>
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary">Hidden</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted">
                                        <?php echo e($item->updated_at->format('d M Y, H:i')); ?>

                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <a href="<?php echo e(route('admin.cms.header.toolbar.edit', $item)); ?>" class="btn btn-sm btn-outline-secondary">
                                                Edit
                                            </a>
                                            <form action="<?php echo e(route('admin.cms.header.toolbar.destroy', $item)); ?>" method="POST" onsubmit="return confirm('Delete this toolbar item?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        No toolbar items created yet. Click "New Item" to add your first toolbar item.
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/cms/header-toolbar/index.blade.php ENDPATH**/ ?>