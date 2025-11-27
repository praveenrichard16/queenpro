

<?php $__env->startSection('title', $campaign->id ? 'Edit Campaign' : 'Create Campaign'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0"><?php echo e($campaign->id ? 'Edit Campaign' : 'Create Campaign'); ?></h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.marketing.campaigns.index')); ?>" class="hover-text-primary">Campaigns</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light"><?php echo e($campaign->id ? 'Edit' : 'Create'); ?></li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="<?php echo e($campaign->exists ? route('admin.marketing.campaigns.update', $campaign) : route('admin.marketing.campaigns.store')); ?>" class="row g-4">
				<?php echo csrf_field(); ?>
				<?php if($campaign->exists): ?>
					<?php echo method_field('PUT'); ?>
				<?php endif; ?>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Campaign Name <span class="text-danger">*</span></label>
					<input type="text" name="name" value="<?php echo e(old('name', $campaign->name)); ?>" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
					<?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Template <span class="text-danger">*</span></label>
					<select name="template_id" class="form-select bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['template_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
						<option value="">Select template</option>
						<?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($template->id); ?>" <?php if(old('template_id', $campaign->template_id) == $template->id): echo 'selected'; endif; ?>>
								<?php echo e($template->name); ?> (<?php echo e(ucfirst($template->type)); ?>)
							</option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
					<?php $__errorArgs = ['template_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Status</label>
					<select name="status" class="form-select bg-neutral-50 radius-12 h-56-px">
						<option value="draft" <?php if(old('status', $campaign->status ?? 'draft') === 'draft'): echo 'selected'; endif; ?>>Draft</option>
						<option value="scheduled" <?php if(old('status', $campaign->status) === 'scheduled'): echo 'selected'; endif; ?>>Scheduled</option>
					</select>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Scheduled At</label>
					<input type="datetime-local" name="scheduled_at" value="<?php echo e(old('scheduled_at', $campaign->scheduled_at?->format('Y-m-d\TH:i'))); ?>" class="form-control bg-neutral-50 radius-12 h-56-px">
				</div>

				<div class="col-12">
					<h6 class="fw-semibold mb-16">Recipient Filters</h6>
					<div class="row g-3">
						<div class="col-lg-4">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="recipient_filters[has_orders]" value="1" id="has_orders" <?php if(old('recipient_filters.has_orders', $campaign->recipient_filters['has_orders'] ?? false)): echo 'checked'; endif; ?>>
								<label class="form-check-label" for="has_orders">Has Orders</label>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="recipient_filters[cart_abandoners]" value="1" id="cart_abandoners" <?php if(old('recipient_filters.cart_abandoners', $campaign->recipient_filters['cart_abandoners'] ?? false)): echo 'checked'; endif; ?>>
								<label class="form-check-label" for="cart_abandoners">Cart Abandoners</label>
							</div>
						</div>
						<div class="col-lg-4">
							<label class="form-label text-secondary-light">Min Orders</label>
							<input type="number" name="recipient_filters[min_orders]" value="<?php echo e(old('recipient_filters.min_orders', $campaign->recipient_filters['min_orders'] ?? '')); ?>" class="form-control bg-neutral-50 radius-12 h-56-px" min="0">
						</div>
					</div>
				</div>

				<div class="col-12 d-flex gap-3 mt-12">
					<button class="btn btn-primary radius-12 px-24"><?php echo e($campaign->id ? 'Update Campaign' : 'Create Campaign'); ?></button>
					<a href="<?php echo e(route('admin.marketing.campaigns.index')); ?>" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/marketing/campaigns/form.blade.php ENDPATH**/ ?>