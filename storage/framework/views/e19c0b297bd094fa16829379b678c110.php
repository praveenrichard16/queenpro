

<?php $__env->startSection('title', 'Admin Activity Logs'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">Activity Logs - <?php echo e($user->name); ?></h6>
			<p class="text-secondary-light mb-0">View all activities performed by this admin user.</p>
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
				<a href="<?php echo e(route('admin.users.index', ['tab' => 'admins'])); ?>" class="hover-text-primary">Admins</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Activity Logs</li>
		</ul>
	</div>

	<div class="card border-0 mb-24">
		<div class="card-body p-24">
			<div class="d-flex align-items-center gap-3 mb-4">
				<?php if (isset($component)) { $__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.avatar','data' => ['user' => $user,'size' => 'lg','class' => 'w-64-px h-64-px']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['user' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($user),'size' => 'lg','class' => 'w-64-px h-64-px']); ?>
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
					<h6 class="fw-semibold mb-1"><?php echo e($user->name); ?></h6>
					<p class="text-secondary-light mb-0"><?php echo e($user->email); ?></p>
					<?php if($user->designation): ?>
						<span class="badge bg-primary"><?php echo e($user->designation); ?></span>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="card border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th>Action</th>
							<th>Description</th>
							<th>Route</th>
							<th>IP Address</th>
							<th>Date & Time</th>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<tr>
								<td>
									<span class="badge bg-<?php echo e($activity->action_type === 'create' ? 'success' : ($activity->action_type === 'update' ? 'info' : ($activity->action_type === 'delete' ? 'danger' : 'secondary'))); ?>">
										<?php echo e(ucfirst($activity->action_type)); ?>

									</span>
								</td>
								<td><?php echo e($activity->description); ?></td>
								<td>
									<code class="text-sm"><?php echo e($activity->route ?? '-'); ?></code>
								</td>
								<td><?php echo e($activity->ip_address ?? '-'); ?></td>
								<td><?php echo e($activity->created_at->format('d M Y H:i:s')); ?></td>
							</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
							<tr>
								<td colspan="5" class="text-center py-80">
									<img src="<?php echo e(asset('wowdash/assets/images/notification/empty-message-icon1.png')); ?>" alt="No activities" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No activities found</h6>
									<p class="text-secondary-light mb-0">This admin user has no recorded activities yet.</p>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<?php if($activities->hasPages()): ?>
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					<?php echo e($activities->links()); ?>

				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/users/admin-activity.blade.php ENDPATH**/ ?>