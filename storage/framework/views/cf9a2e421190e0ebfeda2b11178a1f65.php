

<?php $__env->startSection('title', 'Manage Leads'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Manage Leads</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Leads</li>
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
			<form method="GET" class="row g-3 align-items-end">
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Search</label>
					<div class="icon-field">
						<span class="icon top-50 translate-middle-y"><iconify-icon icon="mage:search"></iconify-icon></span>
						<input type="text" class="form-control h-56-px bg-neutral-50 radius-12" name="search" value="<?php echo e(request('search')); ?>" placeholder="Name, email, phone">
					</div>
				</div>
				<div class="col-lg-2">
					<label class="form-label text-secondary-light">Lead Source</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="lead_source_id">
						<option value="">All Sources</option>
						<?php $__currentLoopData = $leadSources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($source->id); ?>" <?php if(request('lead_source_id') == $source->id): echo 'selected'; endif; ?>><?php echo e($source->name); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
				<div class="col-lg-2">
					<label class="form-label text-secondary-light">Lead Stage</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="lead_stage_id">
						<option value="">All Stages</option>
						<?php $__currentLoopData = $leadStages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($stage->id); ?>" <?php if(request('lead_stage_id') == $stage->id): echo 'selected'; endif; ?>><?php echo e($stage->name); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
				<div class="col-lg-2">
					<label class="form-label text-secondary-light">Assigned To Staff</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="assigned_to">
						<option value="">All Staff</option>
						<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($user->id); ?>" <?php if(request('assigned_to') == $user->id): echo 'selected'; endif; ?>><?php echo e($user->name); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
				<div class="col-lg-3 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="<?php echo e(route('admin.leads.index')); ?>" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
					</a>
				</div>
				<div class="col-lg-12">
					<a href="<?php echo e(route('admin.leads.create')); ?>" class="btn btn-warning radius-12 text-white h-56-px d-inline-flex align-items-center justify-content-center gap-2">
						<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
						Add Lead
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
							<th>Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Source</th>
							<th>Stage</th>
							<th>Assigned To</th>
							<th>Expected Value</th>
							<th>Created</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<tr>
								<td><?php echo e($lead->name); ?></td>
								<td><?php echo e($lead->email ?? '-'); ?></td>
								<td><?php echo e($lead->phone ?? '-'); ?></td>
								<td>
									<?php if($lead->source): ?>
										<span class="badge bg-info"><?php echo e($lead->source->name); ?></span>
									<?php else: ?>
										<span class="text-secondary-light">-</span>
									<?php endif; ?>
								</td>
								<td>
									<?php if($lead->stage): ?>
										<span class="badge bg-primary"><?php echo e($lead->stage->name); ?></span>
									<?php else: ?>
										<span class="text-secondary-light">-</span>
									<?php endif; ?>
								</td>
								<td>
									<?php if($lead->assignee): ?>
										<?php echo e($lead->assignee->name); ?>

									<?php else: ?>
										<span class="text-secondary-light">Unassigned</span>
									<?php endif; ?>
								</td>
								<td>
									<?php if($lead->expected_value): ?>
										<?php echo e(currency($lead->expected_value)); ?>

									<?php else: ?>
										<span class="text-secondary-light">-</span>
									<?php endif; ?>
								</td>
								<td><?php echo e($lead->created_at?->format('d M Y H:i')); ?></td>
								<td>
									<div class="d-flex align-items-center gap-2">
										<a href="<?php echo e(route('admin.leads.edit', $lead)); ?>" class="btn btn-sm btn-outline-primary" title="Edit">
											<iconify-icon icon="solar:pen-outline"></iconify-icon>
										</a>
										<form action="<?php echo e(route('admin.leads.destroy', $lead)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this lead?');">
											<?php echo csrf_field(); ?>
											<?php echo method_field('DELETE'); ?>
											<button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
												<iconify-icon icon="solar:trash-bin-outline"></iconify-icon>
											</button>
										</form>
									</div>
								</td>
							</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
							<tr>
								<td colspan="9" class="text-center py-4 text-secondary-light">No leads found.</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
			<?php if($leads->hasPages()): ?>
				<div class="p-3">
					<?php echo e($leads->links()); ?>

				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/leads/index.blade.php ENDPATH**/ ?>