<div class="tab-pane fade show active" id="user-pane-staff" role="tabpanel">
	<div class="card border-0 shadow-none bg-base mb-24">
		<div class="card-body p-24">
			<form method="GET" action="<?php echo e(route('admin.users.index')); ?>" class="row gy-3 gx-3 align-items-end">
				<input type="hidden" name="tab" value="staff">
				<div class="col-xl-6 col-lg-8">
					<label class="form-label text-secondary-light">Search</label>
					<div class="icon-field">
						<span class="icon top-50 translate-middle-y">
							<iconify-icon icon="mage:search"></iconify-icon>
						</span>
						<input type="text" name="search" value="<?php echo e($search ?? ''); ?>" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Search name, email or phone">
					</div>
				</div>
				<div class="col-xl-3 col-lg-4 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="<?php echo e(route('admin.users.index', ['tab' => 'staff'])); ?>" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
					</a>
				</div>
				<div class="col-xl-3 col-lg-12 text-lg-end">
					<a href="<?php echo e(route('admin.users.create', ['role' => 'staff'])); ?>" class="btn btn-warning radius-12 text-white d-inline-flex align-items-center gap-2 h-56-px">
						<iconify-icon icon="solar:user-plus-linear"></iconify-icon>
						Add Staff
					</a>
				</div>
			</form>
		</div>
	</div>

	<div class="card basic-data-table border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th scope="col">User</th>
							<th scope="col">Contact</th>
							<th scope="col">Modules</th>
							<th scope="col">Activities</th>
							<th scope="col">Last Activity</th>
							<th scope="col" class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $users ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
											<?php $__currentLoopData = $user->modules->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<span class="badge bg-primary"><?php echo e(config("modules.modules.{$module->module_name}.name", $module->module_name)); ?></span>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php if($user->modules->count() > 3): ?>
												<span class="badge bg-secondary">+<?php echo e($user->modules->count() - 3); ?> more</span>
											<?php endif; ?>
										</div>
									<?php else: ?>
										<span class="text-sm text-secondary-light">No modules assigned</span>
									<?php endif; ?>
								</td>
								<td>
									<span class="badge bg-info"><?php echo e($user->activity_logs_count ?? 0); ?></span>
								</td>
								<td>
									<?php
										$lastActivity = $user->activityLogs()->latest()->first();
									?>
									<?php if($lastActivity): ?>
										<span class="text-sm text-secondary-light"><?php echo e($lastActivity->created_at->diffForHumans()); ?></span>
									<?php else: ?>
										<span class="text-sm text-secondary-light">Never</span>
									<?php endif; ?>
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="<?php echo e(route('admin.users.staff-activity', $user)); ?>" class="w-36-px h-36-px bg-info-focus text-info-main rounded-circle d-inline-flex align-items-center justify-content-center" title="View Activity">
											<iconify-icon icon="solar:history-outline"></iconify-icon>
										</a>
										<a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center" title="Edit">
											<iconify-icon icon="lucide:edit"></iconify-icon>
										</a>
										<form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" onsubmit="return confirm('Delete this staff member?')" class="d-inline">
											<?php echo csrf_field(); ?>
											<?php echo method_field('DELETE'); ?>
											<button type="submit" class="w-36-px h-36-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center" title="Delete">
												<iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
											</button>
										</form>
									</div>
								</td>
							</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
							<tr>
								<td colspan="6" class="text-center py-80">
									<img src="<?php echo e(asset('wowdash/assets/images/notification/empty-message-icon1.png')); ?>" alt="No staff" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No staff found</h6>
									<p class="text-secondary-light mb-0">Try adjusting your filters or add a new staff member.</p>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<?php if(isset($users) && $users->hasPages()): ?>
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					<?php echo e($users->links()); ?>

				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/users/partials/staff.blade.php ENDPATH**/ ?>