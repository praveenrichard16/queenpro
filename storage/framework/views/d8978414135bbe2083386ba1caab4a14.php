

<?php $__env->startSection('title', 'Affiliate Commissions'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Affiliate Commissions</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Commissions</li>
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
					<label class="form-label text-secondary-light">Status</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="status">
						<option value="">All statuses</option>
						<option value="pending" <?php if(request('status') === 'pending'): echo 'selected'; endif; ?>>Pending</option>
						<option value="approved" <?php if(request('status') === 'approved'): echo 'selected'; endif; ?>>Approved</option>
						<option value="paid" <?php if(request('status') === 'paid'): echo 'selected'; endif; ?>>Paid</option>
						<option value="cancelled" <?php if(request('status') === 'cancelled'): echo 'selected'; endif; ?>>Cancelled</option>
					</select>
				</div>
				<div class="col-lg-3 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="<?php echo e(route('admin.affiliates.commissions.index')); ?>" class="btn btn-outline-secondary h-56-px radius-12">
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
							<th>Affiliate</th>
							<th>Order</th>
							<th class="text-end">Amount</th>
							<th>Status</th>
							<th>Date</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $commissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<tr>
								<td>
									<h6 class="text-md mb-2 fw-medium"><?php echo e($commission->affiliate->user->name); ?></h6>
									<p class="text-sm text-secondary-light mb-0"><?php echo e($commission->affiliate->affiliate_code); ?></p>
								</td>
								<td>
									<a href="<?php echo e(route('admin.orders.show', $commission->order)); ?>" class="text-primary">
										<?php echo e($commission->order->order_number); ?>

									</a>
									<p class="text-sm text-secondary-light mb-0"><?php echo e(\App\Services\CurrencyService::format($commission->order->total_amount)); ?></p>
								</td>
								<td class="text-end fw-semibold"><?php echo e(\App\Services\CurrencyService::format($commission->amount)); ?></td>
								<td>
									<span class="px-20 py-6 rounded-pill fw-semibold text-sm 
										<?php echo e($commission->status === 'paid' ? 'bg-success-focus text-success-main' : 
										   ($commission->status === 'approved' ? 'bg-info-focus text-info-main' : 
										   ($commission->status === 'pending' ? 'bg-warning-focus text-warning-main' : 'bg-danger-focus text-danger-main'))); ?>">
										<?php echo e(ucfirst($commission->status)); ?>

									</span>
								</td>
								<td><?php echo e($commission->created_at->format('M d, Y')); ?></td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<?php if($commission->status === 'pending'): ?>
											<form action="<?php echo e(route('admin.affiliates.commissions.approve', $commission)); ?>" method="POST" class="d-inline">
												<?php echo csrf_field(); ?>
												<button type="submit" class="btn btn-sm btn-success radius-12">Approve</button>
											</form>
											<form action="<?php echo e(route('admin.affiliates.commissions.cancel', $commission)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Cancel this commission?')">
												<?php echo csrf_field(); ?>
												<button type="submit" class="btn btn-sm btn-danger radius-12">Cancel</button>
											</form>
										<?php endif; ?>
									</div>
								</td>
							</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
							<tr>
								<td colspan="6" class="text-center py-80">
									<img src="<?php echo e(asset('wowdash/assets/images/notification/empty-message-icon1.png')); ?>" alt="No commissions" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No commissions found</h6>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<?php if($commissions->hasPages()): ?>
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					<?php echo e($commissions->links()); ?>

				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/affiliates/commissions/index.blade.php ENDPATH**/ ?>