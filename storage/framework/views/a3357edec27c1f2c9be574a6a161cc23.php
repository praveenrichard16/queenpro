

<?php $__env->startSection('title', $endpoint->exists ? 'Edit Webhook Endpoint' : 'Create Webhook Endpoint'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0"><?php echo e($endpoint->exists ? 'Edit Webhook Endpoint' : 'Create Webhook Endpoint'); ?></h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">Dashboard</a>
			</li>
			<li>-</li>
			<li class="fw-medium"><a href="<?php echo e(route('admin.api.webhooks.index')); ?>" class="hover-text-primary">Webhooks</a></li>
			<li>-</li>
			<li class="fw-medium text-secondary-light"><?php echo e($endpoint->exists ? 'Edit' : 'Create'); ?></li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="<?php echo e($endpoint->exists ? route('admin.api.webhooks.update', $endpoint) : route('admin.api.webhooks.store')); ?>">
				<?php echo csrf_field(); ?>
				<?php if($endpoint->exists): ?>
					<?php echo method_field('PUT'); ?>
				<?php endif; ?>

				<div class="row g-4">
					<div class="col-md-6">
						<label class="form-label">Name <span class="text-danger">*</span></label>
						<input type="text" name="name" value="<?php echo e(old('name', $endpoint->name)); ?>" class="form-control bg-neutral-50 radius-12 h-56-px" required>
						<?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger text-sm mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					</div>

					<div class="col-md-6">
						<label class="form-label">Source</label>
						<input type="text" name="source" value="<?php echo e(old('source', $endpoint->source)); ?>" class="form-control bg-neutral-50 radius-12 h-56-px" placeholder="e.g., lead, customer">
						<?php $__errorArgs = ['source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger text-sm mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					</div>

					<div class="col-12">
						<label class="form-label">URL <span class="text-danger">*</span></label>
						<input type="url" name="url" value="<?php echo e(old('url', $endpoint->url)); ?>" class="form-control bg-neutral-50 radius-12 h-56-px" required>
						<?php $__errorArgs = ['url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger text-sm mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					</div>

					<div class="col-12">
						<label class="form-label">Secret</label>
						<input type="text" name="secret" value="<?php echo e(old('secret', $endpoint->secret)); ?>" class="form-control bg-neutral-50 radius-12 h-56-px">
						<small class="text-secondary-light">Leave empty to auto-generate</small>
						<?php $__errorArgs = ['secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="text-danger text-sm mt-1"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					</div>

					<div class="col-12">
						<label class="form-label">Description</label>
						<textarea name="description" rows="3" class="form-control bg-neutral-50 radius-12"><?php echo e(old('description', $endpoint->description)); ?></textarea>
					</div>

					<div class="col-md-6">
						<label class="form-label">Timeout (seconds)</label>
						<input type="number" name="timeout" value="<?php echo e(old('timeout', $endpoint->timeout ?? 30)); ?>" min="1" max="300" class="form-control bg-neutral-50 radius-12 h-56-px">
					</div>

					<div class="col-md-6">
						<label class="form-label">Max Attempts</label>
						<input type="number" name="max_attempts" value="<?php echo e(old('max_attempts', $endpoint->max_attempts ?? 3)); ?>" min="1" max="10" class="form-control bg-neutral-50 radius-12 h-56-px">
					</div>

					<div class="col-12">
						<div class="form-check">
							<input type="checkbox" name="is_active" value="1" id="is_active" class="form-check-input" <?php if(old('is_active', $endpoint->is_active ?? true)): echo 'checked'; endif; ?>>
							<label for="is_active" class="form-check-label">Active</label>
						</div>
					</div>
				</div>

				<div class="d-flex gap-3 mt-24">
					<button type="submit" class="btn btn-primary radius-12 px-24">Save</button>
					<a href="<?php echo e(route('admin.api.webhooks.index')); ?>" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/api/webhooks/form.blade.php ENDPATH**/ ?>