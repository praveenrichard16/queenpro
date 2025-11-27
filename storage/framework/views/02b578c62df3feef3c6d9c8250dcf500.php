

<?php $__env->startSection('title', 'Manage Enquiries'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Manage Enquiries</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Enquiries</li>
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
				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Search</label>
					<div class="icon-field">
						<span class="icon top-50 translate-middle-y"><iconify-icon icon="mage:search"></iconify-icon></span>
						<input type="text" class="form-control h-56-px bg-neutral-50 radius-12" name="search" value="<?php echo e(request('search')); ?>" placeholder="Subject, message, customer name">
					</div>
				</div>
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Status</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="status">
						<option value="">All Statuses</option>
						<option value="new" <?php if(request('status') === 'new'): echo 'selected'; endif; ?>>New</option>
						<option value="in_progress" <?php if(request('status') === 'in_progress'): echo 'selected'; endif; ?>>In Progress</option>
						<option value="converted" <?php if(request('status') === 'converted'): echo 'selected'; endif; ?>>Converted</option>
						<option value="closed" <?php if(request('status') === 'closed'): echo 'selected'; endif; ?>>Closed</option>
					</select>
				</div>
				<div class="col-lg-3 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="<?php echo e(route('admin.enquiries.index')); ?>" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
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
							<th>Customer</th>
							<th>Product</th>
							<th>Subject</th>
							<th>Status</th>
							<th>Converted To Lead</th>
							<th>Created</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $enquiries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enquiry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<tr>
								<td>
									<div class="fw-medium"><?php echo e($enquiry->user->name ?? 'Guest'); ?></div>
									<div class="text-sm text-secondary-light"><?php echo e($enquiry->user->email ?? '-'); ?></div>
								</td>
								<td><?php echo e($enquiry->product->name ?? '-'); ?></td>
								<td><?php echo e(\Illuminate\Support\Str::limit($enquiry->subject ?? 'No subject', 50)); ?></td>
								<td>
									<span class="badge bg-<?php echo e($enquiry->status === 'converted' ? 'success' : ($enquiry->status === 'closed' ? 'secondary' : ($enquiry->status === 'in_progress' ? 'info' : 'warning'))); ?>">
										<?php echo e(ucfirst(str_replace('_', ' ', $enquiry->status))); ?>

									</span>
								</td>
								<td>
									<?php if($enquiry->lead): ?>
										<a href="<?php echo e(route('admin.leads.edit', $enquiry->lead)); ?>" class="text-primary"><?php echo e($enquiry->lead->name); ?></a>
									<?php else: ?>
										<span class="text-secondary-light">-</span>
									<?php endif; ?>
								</td>
								<td><?php echo e($enquiry->created_at->format('d M Y H:i')); ?></td>
								<td>
									<a href="<?php echo e(route('admin.enquiries.show', $enquiry)); ?>" class="btn btn-sm btn-outline-primary" title="View">
										<iconify-icon icon="solar:eye-outline"></iconify-icon>
									</a>
								</td>
							</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
							<tr>
								<td colspan="7" class="text-center py-4 text-secondary-light">No enquiries found.</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
			<?php if($enquiries->hasPages()): ?>
				<div class="p-3">
					<?php echo e($enquiries->links()); ?>

				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/enquiries/index.blade.php ENDPATH**/ ?>