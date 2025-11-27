

<?php $__env->startSection('title', 'Lead Stage'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Lead Stage</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Lead Stage</li>
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

	<?php if($errors->any()): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			Please review the highlighted fields below.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<div class="row">
		<div class="col-lg-3 mb-24">
			<div class="card border-0 h-100">
				<div class="card-body p-16">
					<div class="nav flex-column nav-pills gap-2" id="lead-stage-tabs" role="tablist" aria-orientation="vertical">
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e((request('tab') === 'add' || !request('tab')) ? 'active' : ''); ?>" id="lead-stage-tab-add" data-bs-toggle="pill" data-bs-target="#lead-stage-pane-add" type="button" role="tab" aria-controls="lead-stage-pane-add" aria-selected="<?php echo e((request('tab') === 'add' || !request('tab')) ? 'true' : 'false'); ?>">
							<span>Add Lead Stage</span>
							<iconify-icon icon="solar:add-circle-linear" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(request('tab') === 'manage' ? 'active' : ''); ?>" id="lead-stage-tab-manage" data-bs-toggle="pill" data-bs-target="#lead-stage-pane-manage" type="button" role="tab" aria-controls="lead-stage-pane-manage" aria-selected="<?php echo e(request('tab') === 'manage' ? 'true' : 'false'); ?>">
							<span>Manage Lead Stage</span>
							<iconify-icon icon="solar:list-check-linear" class="text-lg"></iconify-icon>
						</button>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-9">
			<div class="tab-content" id="lead-stage-tabs-content">
				<!-- Add Lead Stage Tab -->
				<div class="tab-pane fade <?php echo e((request('tab') === 'add' || !request('tab')) ? 'show active' : ''); ?>" id="lead-stage-pane-add" role="tabpanel" aria-labelledby="lead-stage-tab-add">
					<div class="card border-0">
						<div class="card-body p-24">
							<form method="POST" action="<?php echo e(route('admin.lead-stages.store')); ?>" class="row g-4">
								<?php echo csrf_field(); ?>
								<input type="hidden" name="tab" value="add">

								<div class="col-12">
									<h6 class="fw-semibold mb-16">Lead Stage Information</h6>
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Name <span class="text-danger">*</span></label>
									<input type="text" name="name" value="<?php echo e(old('name')); ?>" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['name'];
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
									<label class="form-label text-secondary-light">Slug</label>
									<input type="text" name="slug" value="<?php echo e(old('slug')); ?>" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Auto-generated if left empty">
									<?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									<div class="form-text mt-1">Leave empty to auto-generate from name</div>
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Sort Order</label>
									<input type="number" name="sort_order" value="<?php echo e(old('sort_order', 0)); ?>" min="0" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['sort_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
									<?php $__errorArgs = ['sort_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									<div class="form-text mt-1">Lower numbers appear first</div>
								</div>

								<div class="col-12">
									<hr class="my-4">
									<h6 class="fw-semibold mb-16">Stage Flags</h6>
								</div>

								<div class="col-12">
									<div class="form-check form-switch mb-3">
										<input class="form-check-input" type="checkbox" name="is_default" id="is_default" value="1" <?php if(old('is_default')): echo 'checked'; endif; ?>>
										<label class="form-check-label" for="is_default">Set as Default Stage</label>
										<div class="form-text">New leads will be assigned to this stage by default</div>
									</div>
									<div class="form-check form-switch mb-3">
										<input class="form-check-input" type="checkbox" name="is_won" id="is_won" value="1" <?php if(old('is_won')): echo 'checked'; endif; ?>>
										<label class="form-check-label" for="is_won">Mark as Won Stage</label>
										<div class="form-text">Leads in this stage are considered successful conversions</div>
									</div>
									<div class="form-check form-switch">
										<input class="form-check-input" type="checkbox" name="is_lost" id="is_lost" value="1" <?php if(old('is_lost')): echo 'checked'; endif; ?>>
										<label class="form-check-label" for="is_lost">Mark as Lost Stage</label>
										<div class="form-text">Leads in this stage are considered lost opportunities</div>
									</div>
								</div>

								<div class="col-12">
									<div class="d-flex gap-2">
										<button type="submit" class="btn btn-primary radius-12 h-56-px">Create Lead Stage</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>

				<!-- Manage Lead Stage Tab -->
				<div class="tab-pane fade <?php echo e(request('tab') === 'manage' ? 'show active' : ''); ?>" id="lead-stage-pane-manage" role="tabpanel" aria-labelledby="lead-stage-tab-manage">
					<div class="card border-0">
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table bordered-table mb-0 align-middle">
									<thead>
										<tr>
											<th>Name</th>
											<th>Slug</th>
											<th>Sort Order</th>
											<th>Flags</th>
											<th class="text-end">Leads</th>
											<th class="text-end">Actions</th>
										</tr>
									</thead>
									<tbody>
										<?php $__empty_1 = true; $__currentLoopData = $leadStages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leadStage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
											<tr>
												<td>
													<h6 class="text-md mb-2 fw-medium"><?php echo e($leadStage->name); ?></h6>
													<p class="text-sm text-secondary-light mb-0"><?php echo e($leadStage->slug); ?></p>
												</td>
												<td><?php echo e($leadStage->slug); ?></td>
												<td><?php echo e($leadStage->sort_order); ?></td>
												<td>
													<div class="d-flex gap-2">
														<?php if($leadStage->is_default): ?>
															<span class="badge bg-primary">Default</span>
														<?php endif; ?>
														<?php if($leadStage->is_won): ?>
															<span class="badge bg-success">Won</span>
														<?php endif; ?>
														<?php if($leadStage->is_lost): ?>
															<span class="badge bg-danger">Lost</span>
														<?php endif; ?>
													</div>
												</td>
												<td class="text-end"><?php echo e($leadStage->leads_count); ?></td>
												<td class="text-end">
													<div class="d-inline-flex gap-2">
														<a href="<?php echo e(route('admin.lead-stages.edit', $leadStage)); ?>?tab=manage" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center">
															<iconify-icon icon="lucide:edit"></iconify-icon>
														</a>
														<form action="<?php echo e(route('admin.lead-stages.destroy', $leadStage)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this lead stage?');" class="d-inline">
															<?php echo csrf_field(); ?>
															<?php echo method_field('DELETE'); ?>
															<input type="hidden" name="tab" value="manage">
															<button type="submit" class="w-36-px h-36-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center border-0">
																<iconify-icon icon="lucide:trash-2"></iconify-icon>
															</button>
														</form>
													</div>
												</td>
											</tr>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
											<tr>
												<td colspan="6" class="text-center py-4 text-secondary-light">No lead stages found.</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
	// Preserve tab state on form submissions
	document.querySelectorAll('form').forEach(form => {
		form.addEventListener('submit', function() {
			const activeTab = document.querySelector('.nav-pills .active');
			if (activeTab) {
				const tabId = activeTab.id.replace('lead-stage-tab-', '');
				const hiddenInput = document.createElement('input');
				hiddenInput.type = 'hidden';
				hiddenInput.name = 'tab';
				hiddenInput.value = tabId;
				form.appendChild(hiddenInput);
			}
		});
	});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/lead-stages/index-tabbed.blade.php ENDPATH**/ ?>