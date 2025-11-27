

<?php $__env->startSection('title', $token ? 'Edit API Key' : 'Create API Key'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0"><?php echo e($token ? 'Edit API Key' : 'Create API Key'); ?></h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.api-tokens.index')); ?>" class="hover-text-primary">API Keys</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light"><?php echo e($token ? 'Edit' : 'Create'); ?></li>
		</ul>
	</div>

	<?php if(session('error')): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<?php echo e(session('error')); ?>

			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="<?php echo e($token ? route('admin.api-tokens.update', $token) : route('admin.api-tokens.store')); ?>" class="row g-4">
				<?php echo csrf_field(); ?>
				<?php if($token): ?>
					<?php echo method_field('PUT'); ?>
				<?php endif; ?>

				<div class="col-12">
					<label class="form-label text-secondary-light">Token Name <span class="text-danger">*</span></label>
					<input type="text" name="name" value="<?php echo e(old('name', $token?->name)); ?>" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required placeholder="e.g., Production API Key, Development Token">
					<?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					<div class="form-text mt-1">A descriptive name for this API token to help you identify it later.</div>
				</div>

				<?php if(!$token): ?>
					<div class="col-12">
						<label class="form-label text-secondary-light">User <span class="text-danger">*</span></label>
						<select name="user_id" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
							<option value="">Select a user</option>
							<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<option value="<?php echo e($user->id); ?>" <?php echo e(old('user_id') == $user->id ? 'selected' : ''); ?>>
									<?php echo e($user->name); ?> (<?php echo e($user->email); ?>) 
									<?php if($user->is_admin || $user->is_super_admin): ?>
										- Admin
									<?php elseif($user->is_staff): ?>
										- Staff
									<?php else: ?>
										- Customer
									<?php endif; ?>
								</option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
						<?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
						<div class="form-text mt-1">Select the user for whom this API token will be created.</div>
					</div>
				<?php endif; ?>

				<div class="col-12">
					<label class="form-label text-secondary-light mb-3">Abilities (Permissions)</label>
					<?php
						$hasAllAbilities = $token && is_array($token->abilities) && in_array('*', $token->abilities);
						$abilities = $token && is_array($token->abilities) ? $token->abilities : [];
						// If token has all abilities, show all checkboxes as checked (user can uncheck to restrict)
						// If token has specific abilities, show only those checked
						// If new token, show none checked (will default to all permissions)
					?>
					<div class="d-flex flex-column gap-2">
						<div class="form-check style-check d-flex align-items-center">
							<input class="form-check-input border border-neutral-300" type="checkbox" value="read" id="ability_read" name="abilities[]" 
								<?php if($hasAllAbilities || in_array('read', $abilities)): echo 'checked'; endif; ?>>
							<label class="form-check-label ms-8" for="ability_read">
								<strong>Read</strong> - Allow reading/viewing data
							</label>
						</div>
						<div class="form-check style-check d-flex align-items-center">
							<input class="form-check-input border border-neutral-300" type="checkbox" value="write" id="ability_write" name="abilities[]"
								<?php if($hasAllAbilities || in_array('write', $abilities)): echo 'checked'; endif; ?>>
							<label class="form-check-label ms-8" for="ability_write">
								<strong>Write</strong> - Allow creating/updating data
							</label>
						</div>
						<div class="form-check style-check d-flex align-items-center">
							<input class="form-check-input border border-neutral-300" type="checkbox" value="delete" id="ability_delete" name="abilities[]"
								<?php if($hasAllAbilities || in_array('delete', $abilities)): echo 'checked'; endif; ?>>
							<label class="form-check-label ms-8" for="ability_delete">
								<strong>Delete</strong> - Allow deleting data
							</label>
						</div>
						<div class="form-check style-check d-flex align-items-center">
							<input class="form-check-input border border-neutral-300" type="checkbox" value="admin" id="ability_admin" name="abilities[]"
								<?php if($hasAllAbilities || in_array('admin', $abilities)): echo 'checked'; endif; ?>>
							<label class="form-check-label ms-8" for="ability_admin">
								<strong>Admin</strong> - Full administrative access
							</label>
						</div>
					</div>
					<div class="form-text mt-2">
						<?php if($hasAllAbilities): ?>
							<strong class="text-info">This token currently has all permissions (*). Uncheck abilities above to restrict access to specific permissions only.</strong>
						<?php else: ?>
							Leave all unchecked to grant all permissions (*). Select specific abilities to restrict access.
						<?php endif; ?>
					</div>
					<?php $__errorArgs = ['abilities'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					<?php $__errorArgs = ['abilities.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>

				<?php if($token): ?>
					<div class="col-12">
						<div class="alert alert-info">
							<h6 class="fw-semibold mb-2">Token Information</h6>
							<p class="mb-1"><strong>User:</strong> <?php echo e($token->tokenable->name ?? 'N/A'); ?> (<?php echo e($token->tokenable->email ?? 'N/A'); ?>)</p>
							<p class="mb-1"><strong>Created:</strong> <?php echo e($token->created_at->format('M d, Y h:i A')); ?></p>
							<?php if($token->last_used_at): ?>
								<p class="mb-0"><strong>Last Used:</strong> <?php echo e($token->last_used_at->format('M d, Y h:i A')); ?> (<?php echo e($token->last_used_at->diffForHumans()); ?>)</p>
							<?php else: ?>
								<p class="mb-0"><strong>Last Used:</strong> Never</p>
							<?php endif; ?>
							<p class="mb-0 mt-2 text-danger"><strong>Note:</strong> The token value cannot be retrieved after creation. If you need a new token, delete this one and create a new one.</p>
						</div>
					</div>
				<?php endif; ?>

				<div class="col-12 d-flex gap-3 mt-12">
					<button class="btn btn-primary radius-12 px-24"><?php echo e($token ? 'Update API Key' : 'Create API Key'); ?></button>
					<a href="<?php echo e(route('admin.api-tokens.index')); ?>" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/api-tokens/form.blade.php ENDPATH**/ ?>