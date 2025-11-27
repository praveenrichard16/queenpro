

<?php $__env->startSection('title', $affiliate->exists ? 'Edit Affiliate' : 'Add Affiliate'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0"><?php echo e($affiliate->exists ? 'Edit Affiliate' : 'Add Affiliate'); ?></h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.affiliates.index')); ?>" class="hover-text-primary">Affiliates</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light"><?php echo e($affiliate->exists ? 'Edit' : 'Create'); ?></li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="<?php echo e($affiliate->exists ? route('admin.affiliates.update', $affiliate) : route('admin.affiliates.store')); ?>" class="row g-4">
				<?php echo csrf_field(); ?>
				<?php if($affiliate->exists): ?>
					<?php echo method_field('PUT'); ?>
				<?php endif; ?>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">User <span class="text-danger">*</span></label>
					<select name="user_id" class="form-select bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required <?php echo e($affiliate->exists ? 'disabled' : ''); ?>>
						<option value="">Select user</option>
						<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($user->id); ?>" <?php if(old('user_id', $affiliate->user_id) == $user->id): echo 'selected'; endif; ?>>
								<?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
							</option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
					<?php if($affiliate->exists): ?>
						<input type="hidden" name="user_id" value="<?php echo e($affiliate->user_id); ?>">
					<?php endif; ?>
					<?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>

				<?php if($affiliate->exists): ?>
				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Affiliate Code</label>
					<input type="text" value="<?php echo e($affiliate->affiliate_code); ?>" class="form-control bg-neutral-50 radius-12 h-56-px" disabled>
					<div class="form-text mt-1">Referral URL: <a href="<?php echo e($affiliate->referral_url); ?>" target="_blank"><?php echo e($affiliate->referral_url); ?></a></div>
				</div>
				<?php endif; ?>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Commission Rate (%) <span class="text-danger">*</span></label>
					<input type="number" step="0.01" min="0" max="100" name="commission_rate" value="<?php echo e(old('commission_rate', $affiliate->commission_rate ?? 10)); ?>" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['commission_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
					<?php $__errorArgs = ['commission_rate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Status <span class="text-danger">*</span></label>
					<select name="status" class="form-select bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
						<option value="pending" <?php if(old('status', $affiliate->status) === 'pending'): echo 'selected'; endif; ?>>Pending</option>
						<option value="active" <?php if(old('status', $affiliate->status) === 'active'): echo 'selected'; endif; ?>>Active</option>
						<option value="suspended" <?php if(old('status', $affiliate->status) === 'suspended'): echo 'selected'; endif; ?>>Suspended</option>
					</select>
					<?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Payment Information (JSON)</label>
					<textarea name="payment_info" rows="4" class="form-control bg-neutral-50 radius-12 <?php $__errorArgs = ['payment_info'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder='{"bank_name": "Bank Name", "account_number": "123456", "paypal_email": "email@example.com"}'><?php echo e(old('payment_info', $affiliate->payment_info ? json_encode($affiliate->payment_info, JSON_PRETTY_PRINT) : '')); ?></textarea>
					<?php $__errorArgs = ['payment_info'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					<div class="form-text mt-1">Enter payment information as JSON format.</div>
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Notes</label>
					<textarea name="notes" rows="3" class="form-control bg-neutral-50 radius-12 <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Additional notes"><?php echo e(old('notes', $affiliate->notes)); ?></textarea>
					<?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>

				<div class="col-12 d-flex gap-3 mt-12">
					<button class="btn btn-primary radius-12 px-24"><?php echo e($affiliate->exists ? 'Update Affiliate' : 'Create Affiliate'); ?></button>
					<a href="<?php echo e(route('admin.affiliates.index')); ?>" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/affiliates/form.blade.php ENDPATH**/ ?>