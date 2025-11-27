

<?php $__env->startSection('title', 'Assign Leads'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Assign Leads</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.leads.index')); ?>" class="hover-text-primary">Leads</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Assign</li>
		</ul>
	</div>

	<?php if(session('success')): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?php echo e(session('success')); ?>

			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<div class="card border-0 shadow-none bg-base mb-24">
		<div class="card-body p-24">
			<form method="GET" class="row g-3 align-items-end">
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Lead Source</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="lead_source_id">
						<option value="">All Sources</option>
						<?php $__currentLoopData = $leadSources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($source->id); ?>" <?php if(request('lead_source_id') == $source->id): echo 'selected'; endif; ?>><?php echo e($source->name); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Lead Stage</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="lead_stage_id">
						<option value="">All Stages</option>
						<?php $__currentLoopData = $leadStages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($stage->id); ?>" <?php if(request('lead_stage_id') == $stage->id): echo 'selected'; endif; ?>><?php echo e($stage->name); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
				<div class="col-lg-2">
					<label class="form-label text-secondary-light">Filter</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="filter">
						<option value="">All Leads</option>
						<option value="unassigned" <?php if(request('filter') === 'unassigned'): echo 'selected'; endif; ?>>Unassigned Only</option>
					</select>
				</div>
				<div class="col-lg-4 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="<?php echo e(route('admin.leads.assign')); ?>" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
					</a>
				</div>
			</form>
		</div>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="<?php echo e(route('admin.leads.assign.update')); ?>" id="assignForm">
				<?php echo csrf_field(); ?>
				<div class="row g-3 mb-4">
					<div class="col-lg-6">
						<label class="form-label text-secondary-light">Assign Selected Leads To Staff <span class="text-danger">*</span></label>
						<select name="assigned_to" class="form-select bg-neutral-50 radius-12 h-56-px" required>
							<option value="">Select Staff Member</option>
							<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
						<div class="form-text mt-1">Leads can only be assigned to staff members.</div>
					</div>
					<div class="col-lg-6 d-flex align-items-end">
						<button type="submit" class="btn btn-primary radius-12 h-56-px">Assign Selected Leads</button>
					</div>
				</div>

				<div class="table-responsive">
					<table class="table bordered-table mb-0 align-middle">
						<thead>
							<tr>
								<th width="50">
									<input type="checkbox" id="selectAll" class="form-check-input">
								</th>
								<th>Name</th>
								<th>Email</th>
								<th>Phone</th>
								<th>Source</th>
								<th>Stage</th>
								<th>Currently Assigned To</th>
								<th>Created</th>
							</tr>
						</thead>
						<tbody>
							<?php $__empty_1 = true; $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
								<tr>
									<td>
										<input type="checkbox" name="lead_ids[]" value="<?php echo e($lead->id); ?>" class="form-check-input lead-checkbox">
									</td>
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
									<td><?php echo e($lead->created_at?->format('d M Y H:i')); ?></td>
								</tr>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
								<tr>
									<td colspan="8" class="text-center py-4 text-secondary-light">No leads found.</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
				<?php if($leads->hasPages()): ?>
					<div class="mt-3">
						<?php echo e($leads->links()); ?>

					</div>
				<?php endif; ?>
			</form>
		</div>
	</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
	document.getElementById('selectAll').addEventListener('change', function() {
		const checkboxes = document.querySelectorAll('.lead-checkbox');
		checkboxes.forEach(checkbox => {
			checkbox.checked = this.checked;
		});
	});

	document.getElementById('assignForm').addEventListener('submit', function(e) {
		const checked = document.querySelectorAll('.lead-checkbox:checked');
		if (checked.length === 0) {
			e.preventDefault();
			alert('Please select at least one lead to assign.');
			return false;
		}
	});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/leads/assign.blade.php ENDPATH**/ ?>