

<?php $__env->startSection('title', $user->exists ? 'Edit User' : 'Add User'); ?>

<?php $__env->startSection('content'); ?>
	<div class="dashboard-main-body">
		<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
			<h6 class="fw-semibold mb-0"><?php echo e($user->exists ? 'Edit User' : 'Add User'); ?></h6>
			<ul class="d-flex align-items-center gap-2">
				<li class="fw-medium">
					<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
						<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
						Dashboard
					</a>
				</li>
				<li>-</li>
				<li class="fw-medium">
					<a href="<?php echo e(route('admin.users.index')); ?>" class="hover-text-primary">Users</a>
				</li>
				<li>-</li>
				<li class="fw-medium text-secondary-light"><?php echo e($user->exists ? 'Edit' : 'Add'); ?></li>
			</ul>
		</div>

		<?php
			$isSuperAdmin = auth()->user()?->is_super_admin;
		?>

		<div class="card border-0">
			<div class="card-body p-24">
				<form method="POST" enctype="multipart/form-data" action="<?php echo e($user->exists ? route('admin.users.update', $user) : route('admin.users.store')); ?>" class="row g-4">
					<?php echo csrf_field(); ?>
					<?php if($user->exists): ?>
						<?php echo method_field('PUT'); ?>
					<?php endif; ?>

					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Full Name</label>
						<input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['name'];
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
						<label class="form-label text-secondary-light">Email</label>
						<input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
						<?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					</div>

					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Phone</label>
						<input type="text" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="+971 55 000 1234">
						<?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					</div>

					<?php if($isSuperAdmin): ?>
						<div class="col-lg-6">
							<label class="form-label text-secondary-light">Designation <span class="text-secondary-light">(optional)</span></label>
							<input type="text" name="designation" value="<?php echo e(old('designation', $user->designation)); ?>" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['designation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="e.g. Sales Consultant">
							<?php $__errorArgs = ['designation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
						</div>
					<?php endif; ?>

					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Password <?php echo e($user->exists ? '(leave blank to keep current)' : ''); ?></label>
						<input type="password" name="password" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php echo e($user->exists ? '' : 'required'); ?>>
						<?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					</div>

					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Confirm Password</label>
						<input type="password" name="password_confirmation" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" <?php echo e($user->exists ? '' : 'required'); ?>>
						<?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					</div>

					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Avatar</label>
						<input type="file" name="avatar" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
						<?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
						<div class="form-text mt-1">Recommended size: 300 × 300px (JPG/PNG, max 2MB).</div>
					</div>

					<?php if($user->exists): ?>
						<div class="col-lg-6">
							<label class="form-label text-secondary-light d-block">Current Avatar</label>
							<div class="p-16 border radius-12 bg-neutral-50 d-inline-flex">
								<?php if (isset($component)) { $__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.avatar','data' => ['user' => $user,'size' => 'xl','class' => 'rounded','style' => 'max-height:140px']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['user' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user),'size' => 'xl','class' => 'rounded','style' => 'max-height:140px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b)): ?>
<?php $attributes = $__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b; ?>
<?php unset($__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b)): ?>
<?php $component = $__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b; ?>
<?php unset($__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b); ?>
<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>

					<?php if($isSuperAdmin): ?>
						<div class="col-lg-6">
							<?php
								$roleValue = old('role', $user->is_admin ? 'admin' : ($user->is_staff ? 'staff' : 'customer'));
							?>
							<label class="form-label text-secondary-light d-block">Role</label>
							<select name="role" id="user_role" class="form-select bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
								<option value="customer" <?php if($roleValue === 'customer'): echo 'selected'; endif; ?>>Customer</option>
								<option value="staff" <?php if($roleValue === 'staff'): echo 'selected'; endif; ?>>Staff</option>
								<option value="admin" <?php if($roleValue === 'admin'): echo 'selected'; endif; ?>>Administrator</option>
							</select>
							<?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
							<div class="form-text mt-1">Only super admins can create administrators or internal staff.</div>
						</div>
					<?php endif; ?>

					<?php if($isSuperAdmin): ?>
						<?php
							$showModules = false;
							if ($user->exists) {
								$showModules = $user->is_staff && !$user->is_admin;
							} else {
								$roleValue = old('role', request('role'));
								$showModules = $roleValue === 'staff';
							}
						?>
						<div class="col-12" id="modules_section" style="<?php echo e(!$showModules ? 'display:none;' : ''); ?>">
							<hr class="my-4">
							<h6 class="fw-semibold mb-16">Module Permissions</h6>
							<p class="text-secondary-light mb-3">Select which modules this user can access. Admins have access to all modules by default.</p>
							<div class="row g-3">
								<?php $__currentLoopData = config('modules.modules'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $moduleKey => $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<div class="col-lg-4 col-md-6">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" name="modules[]" value="<?php echo e($moduleKey); ?>" id="module_<?php echo e($moduleKey); ?>" 
												<?php if($user->exists && in_array($moduleKey, $user->assigned_modules) && !$user->is_super_admin && !$user->is_admin): echo 'checked'; endif; ?>
												<?php if($user->exists && ($user->is_super_admin || $user->is_admin)): echo 'disabled'; endif; ?>>
											<label class="form-check-label" for="module_<?php echo e($moduleKey); ?>">
												<div class="d-flex align-items-center gap-2">
													<iconify-icon icon="<?php echo e($module['icon']); ?>" class="text-lg"></iconify-icon>
													<div>
														<div class="fw-medium"><?php echo e($module['name']); ?></div>
														<div class="text-sm text-secondary-light"><?php echo e($module['description']); ?></div>
													</div>
												</div>
											</label>
										</div>
									</div>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</div>
							<?php if($user->exists && ($user->is_super_admin || $user->is_admin)): ?>
								<div class="alert alert-info mt-3">
									Admins and super admins have access to all modules automatically.
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<div class="col-12 d-flex gap-2 mt-8">
						<button class="btn btn-primary radius-12 px-24"><?php echo e($user->exists ? 'Update User' : 'Create User'); ?></button>
						<a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const roleSelect = document.getElementById('user_role');
		const modulesSection = document.getElementById('modules_section');
		
		if (roleSelect && modulesSection) {
			roleSelect.addEventListener('change', function() {
				if (this.value === 'staff' || this.value === 'admin') {
					modulesSection.style.display = 'block';
				} else {
					modulesSection.style.display = 'none';
				}
			});
		}
	});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/users/form.blade.php ENDPATH**/ ?>