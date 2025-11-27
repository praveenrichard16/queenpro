

<?php $__env->startSection('title', 'Assign Modules to Staff'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Assign Modules to Staff</h6>
			<p class="text-secondary-light mb-0">Manage module permissions for staff members.</p>
		</div>
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
			<li class="fw-medium text-secondary-light">Assign Modules</li>
		</ul>
	</div>

	<?php if(session('success')): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?php echo e(session('success')); ?>

			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>
	<?php if(session('error')): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<?php echo e(session('error')); ?>

			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<div class="card border-0 shadow-none bg-base mb-24">
		<div class="card-body p-24">
			<form method="GET" action="<?php echo e(route('admin.users.assign-modules')); ?>" class="row gy-3 gx-3 align-items-end">
				<div class="col-xl-6 col-lg-8">
					<label class="form-label text-secondary-light">Search</label>
					<div class="icon-field">
						<span class="icon top-50 translate-middle-y">
							<iconify-icon icon="mage:search"></iconify-icon>
						</span>
						<input type="text" name="search" value="<?php echo e($search ?? ''); ?>" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Search name or email">
					</div>
				</div>
				<div class="col-xl-3 col-lg-4 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="<?php echo e(route('admin.users.assign-modules')); ?>" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
					</a>
				</div>
			</form>
		</div>
	</div>

	<div class="card border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th scope="col">Staff Member</th>
							<th scope="col">Contact</th>
							<th scope="col">Assigned Modules</th>
							<th scope="col" class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<tr>
								<td>
									<div class="d-flex align-items-center gap-3">
										<?php if (isset($component)) { $__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.avatar','data' => ['user' => $user,'size' => 'md','class' => 'w-48-px h-48-px']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['user' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user),'size' => 'md','class' => 'w-48-px h-48-px']); ?>
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
										<div>
											<h6 class="text-md fw-semibold mb-4"><?php echo e($user->name); ?></h6>
											<?php if($user->designation): ?>
												<span class="text-sm text-secondary-light"><?php echo e($user->designation); ?></span>
											<?php endif; ?>
										</div>
									</div>
								</td>
								<td>
									<div class="d-flex flex-column gap-1">
										<a href="mailto:<?php echo e($user->email); ?>" class="text-decoration-none text-secondary-light"><?php echo e($user->email); ?></a>
										<?php if($user->phone): ?>
											<a href="tel:<?php echo e($user->phone); ?>" class="text-decoration-none text-secondary-light"><?php echo e($user->phone); ?></a>
										<?php endif; ?>
									</div>
								</td>
								<td>
									<?php if($user->modules->count() > 0): ?>
										<div class="d-flex flex-wrap gap-1">
											<?php $__currentLoopData = $user->modules->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<span class="badge bg-primary"><?php echo e(config("modules.modules.{$module->module_name}.name", $module->module_name)); ?></span>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php if($user->modules->count() > 5): ?>
												<span class="badge bg-secondary">+<?php echo e($user->modules->count() - 5); ?> more</span>
											<?php endif; ?>
										</div>
									<?php else: ?>
										<span class="text-sm text-secondary-light">No modules assigned</span>
									<?php endif; ?>
								</td>
								<td class="text-end">
									<button type="button" class="btn btn-primary btn-sm radius-12" data-bs-toggle="modal" data-bs-target="#assignModulesModal<?php echo e($user->id); ?>">
										<iconify-icon icon="solar:settings-linear"></iconify-icon>
										Assign Modules
									</button>
								</td>
							</tr>

							<!-- Assign Modules Modal -->
							<div class="modal fade" id="assignModulesModal<?php echo e($user->id); ?>" tabindex="-1" aria-labelledby="assignModulesModalLabel<?php echo e($user->id); ?>" aria-hidden="true">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<form method="POST" action="<?php echo e(route('admin.users.update-modules', $user)); ?>">
											<?php echo csrf_field(); ?>
											<input type="hidden" name="from_assign_page" value="1">
											<div class="modal-header">
												<h5 class="modal-title" id="assignModulesModalLabel<?php echo e($user->id); ?>">Assign Modules to <?php echo e($user->name); ?></h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">
												<p class="text-secondary-light mb-3">Select which modules this staff member can access:</p>
												<div class="row g-3">
													<?php $__currentLoopData = config('modules.modules'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $moduleKey => $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
														<div class="col-lg-6 col-md-6">
															<div class="form-check p-3 border radius-12">
																<input class="form-check-input" type="checkbox" name="modules[]" value="<?php echo e($moduleKey); ?>" id="module_<?php echo e($user->id); ?>_<?php echo e($moduleKey); ?>" 
																	<?php if(in_array($moduleKey, $user->assigned_modules)): echo 'checked'; endif; ?>>
																<label class="form-check-label w-100" for="module_<?php echo e($user->id); ?>_<?php echo e($moduleKey); ?>">
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
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
												<button type="submit" class="btn btn-primary">Save Changes</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
							<tr>
								<td colspan="4" class="text-center py-80">
									<img src="<?php echo e(asset('wowdash/assets/images/notification/empty-message-icon1.png')); ?>" alt="No staff" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No staff members found</h6>
									<p class="text-secondary-light mb-0">Try adjusting your filters or add a new staff member.</p>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<?php if($staff->hasPages()): ?>
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					<?php echo e($staff->links()); ?>

				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/users/assign-modules.blade.php ENDPATH**/ ?>