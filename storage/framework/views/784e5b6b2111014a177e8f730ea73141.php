

<?php $__env->startSection('title', 'View Enquiry'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">View Enquiry</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.enquiries.index')); ?>" class="hover-text-primary">Enquiries</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">View</li>
		</ul>
	</div>

	<?php if(session('success')): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?php echo e(session('success')); ?>

			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<div class="row g-4">
		<div class="col-lg-8">
			<div class="card border-0">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Enquiry Details</h6>
					
					<div class="mb-4">
						<label class="text-secondary-light small">Subject</label>
						<div class="fw-medium"><?php echo e($enquiry->subject ?? 'No subject'); ?></div>
					</div>

					<?php if($enquiry->message): ?>
						<div class="mb-4">
							<label class="text-secondary-light small">Message</label>
							<div class="p-3 bg-neutral-50 rounded"><?php echo e($enquiry->message); ?></div>
						</div>
					<?php endif; ?>

					<?php if($enquiry->product): ?>
						<div class="mb-4">
							<label class="text-secondary-light small">Product</label>
							<div>
								<a href="<?php echo e(route('products.show', $enquiry->product)); ?>" target="_blank" class="text-primary"><?php echo e($enquiry->product->name); ?></a>
							</div>
						</div>
					<?php endif; ?>

					<div class="mb-4">
						<label class="text-secondary-light small">Customer</label>
						<div class="fw-medium"><?php echo e($enquiry->user->name ?? 'Guest'); ?></div>
						<?php if($enquiry->user): ?>
							<div class="text-sm text-secondary-light"><?php echo e($enquiry->user->email); ?></div>
							<?php if($enquiry->user->phone): ?>
								<div class="text-sm text-secondary-light"><?php echo e($enquiry->user->phone); ?></div>
							<?php endif; ?>
						<?php endif; ?>
					</div>

					<?php if($enquiry->lead): ?>
						<div class="mb-4">
							<label class="text-secondary-light small">Converted To Lead</label>
							<div>
								<a href="<?php echo e(route('admin.leads.edit', $enquiry->lead)); ?>" class="text-primary"><?php echo e($enquiry->lead->name); ?></a>
							</div>
						</div>
					<?php endif; ?>

					<div class="mb-4">
						<label class="text-secondary-light small">Created</label>
						<div><?php echo e($enquiry->created_at->format('d M Y H:i')); ?></div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-4">
			<div class="card border-0">
				<div class="card-body p-24">
					<h6 class="fw-semibold mb-16">Actions</h6>
					
					<form method="POST" action="<?php echo e(route('admin.enquiries.status', $enquiry)); ?>" class="mb-3">
						<?php echo csrf_field(); ?>
						<label class="form-label text-secondary-light">Update Status</label>
						<select name="status" class="form-select bg-neutral-50 radius-12 h-56-px mb-2" onchange="this.form.submit()">
							<option value="new" <?php if($enquiry->status === 'new'): echo 'selected'; endif; ?>>New</option>
							<option value="in_progress" <?php if($enquiry->status === 'in_progress'): echo 'selected'; endif; ?>>In Progress</option>
							<option value="converted" <?php if($enquiry->status === 'converted'): echo 'selected'; endif; ?> disabled>Converted</option>
							<option value="closed" <?php if($enquiry->status === 'closed'): echo 'selected'; endif; ?>>Closed</option>
						</select>
					</form>

					<?php if(!$enquiry->lead): ?>
						<hr class="my-4">
						<h6 class="fw-semibold mb-16">Convert to Lead</h6>
						<form method="POST" action="<?php echo e(route('admin.enquiries.convert-to-lead', $enquiry)); ?>">
							<?php echo csrf_field(); ?>
							<div class="mb-3">
								<label class="form-label text-secondary-light small">Lead Source</label>
								<select name="lead_source_id" class="form-select bg-neutral-50 radius-12 h-56-px">
									<option value="">Select Source</option>
									<?php $__currentLoopData = \App\Models\LeadSource::where('is_active', true)->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<option value="<?php echo e($source->id); ?>"><?php echo e($source->name); ?></option>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</select>
							</div>
							<div class="mb-3">
								<label class="form-label text-secondary-light small">Expected Value</label>
								<input type="number" name="expected_value" step="0.01" min="0" class="form-control bg-neutral-50 radius-12 h-56-px">
							</div>
							<div class="mb-3">
								<label class="form-label text-secondary-light small">Notes</label>
								<textarea name="notes" rows="3" class="form-control bg-neutral-50 radius-12"></textarea>
							</div>
							<button type="submit" class="btn btn-primary w-100 radius-12">Convert to Lead</button>
						</form>
					<?php else: ?>
						<div class="alert alert-info">
							This enquiry has already been converted to a lead.
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/enquiries/show.blade.php ENDPATH**/ ?>