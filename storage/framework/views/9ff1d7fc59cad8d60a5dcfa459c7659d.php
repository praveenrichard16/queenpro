

<?php
	use Illuminate\Support\Str;
?>

<?php $__env->startSection('title', 'Product Reviews'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Product Reviews</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Reviews</li>
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
						<input type="text" class="form-control h-56-px bg-neutral-50 radius-12" name="search" value="<?php echo e(request('search')); ?>" placeholder="Customer name, email">
					</div>
				</div>
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Status</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="status">
						<option value="">All reviews</option>
						<option value="approved" <?php if(request('status') === 'approved'): echo 'selected'; endif; ?>>Approved</option>
						<option value="pending" <?php if(request('status') === 'pending'): echo 'selected'; endif; ?>>Pending</option>
					</select>
				</div>
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Product</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="product_id">
						<option value="">All products</option>
						<?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($product->id); ?>" <?php if(request('product_id') == $product->id): echo 'selected'; endif; ?>><?php echo e($product->name); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
				<div class="col-lg-2 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="<?php echo e(route('admin.reviews.index')); ?>" class="btn btn-outline-secondary h-56-px radius-12">
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
							<th>Product</th>
							<th>Customer</th>
							<th>Rating</th>
							<th>Review</th>
							<th>Status</th>
							<th>Date</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<tr>
								<td>
									<h6 class="text-md mb-0 fw-medium"><?php echo e($review->product->name); ?></h6>
								</td>
								<td>
									<div>
										<h6 class="text-sm mb-1 fw-medium"><?php echo e($review->customer_name); ?></h6>
										<p class="text-xs text-secondary-light mb-0"><?php echo e($review->customer_email); ?></p>
									</div>
								</td>
								<td>
									<div class="d-flex align-items-center gap-1">
										<?php for($i = 1; $i <= 5; $i++): ?>
											<iconify-icon icon="solar:star-bold" class="<?php echo e($i <= $review->rating ? 'text-warning' : 'text-secondary-light'); ?>"></iconify-icon>
										<?php endfor; ?>
										<span class="ms-2 fw-semibold"><?php echo e($review->rating); ?></span>
									</div>
								</td>
								<td>
									<div>
										<?php if($review->title): ?>
											<h6 class="text-sm mb-1 fw-medium"><?php echo e($review->title); ?></h6>
										<?php endif; ?>
										<p class="text-sm text-secondary-light mb-0"><?php echo e(Str::limit($review->comment, 100)); ?></p>
									</div>
								</td>
								<td>
									<?php if($review->is_approved): ?>
										<span class="px-20 py-6 rounded-pill fw-semibold text-sm bg-success-focus text-success-main">Approved</span>
									<?php else: ?>
										<span class="px-20 py-6 rounded-pill fw-semibold text-sm bg-warning-focus text-warning-main">Pending</span>
									<?php endif; ?>
									<?php if($review->is_featured): ?>
										<span class="px-20 py-6 rounded-pill fw-semibold text-sm bg-primary-focus text-primary-main ms-2">Featured</span>
									<?php endif; ?>
								</td>
								<td>
									<span class="text-sm text-secondary-light"><?php echo e($review->created_at->format('M d, Y')); ?></span>
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<?php if(!$review->is_approved): ?>
											<form action="<?php echo e(route('admin.reviews.approve', $review)); ?>" method="POST" class="d-inline">
												<?php echo csrf_field(); ?>
												<button type="submit" class="w-36-px h-36-px bg-success-focus text-success-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center" title="Approve">
													<iconify-icon icon="solar:check-circle-bold"></iconify-icon>
												</button>
											</form>
										<?php else: ?>
											<form action="<?php echo e(route('admin.reviews.reject', $review)); ?>" method="POST" class="d-inline">
												<?php echo csrf_field(); ?>
												<button type="submit" class="w-36-px h-36-px bg-warning-focus text-warning-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center" title="Reject">
													<iconify-icon icon="solar:close-circle-bold"></iconify-icon>
												</button>
											</form>
										<?php endif; ?>
										<form action="<?php echo e(route('admin.reviews.feature', $review)); ?>" method="POST" class="d-inline">
											<?php echo csrf_field(); ?>
											<button type="submit" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center" title="<?php echo e($review->is_featured ? 'Unfeature' : 'Feature'); ?>">
												<iconify-icon icon="solar:star-bold"></iconify-icon>
											</button>
										</form>
										<form action="<?php echo e(route('admin.reviews.destroy', $review)); ?>" method="POST" onsubmit="return confirm('Delete this review?')" class="d-inline">
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
								<td colspan="7" class="text-center py-80">
									<img src="<?php echo e(asset('wowdash/assets/images/notification/empty-message-icon1.png')); ?>" alt="No reviews" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No reviews found</h6>
									<p class="text-secondary-light mb-0">Product reviews will appear here.</p>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<?php if($reviews->hasPages()): ?>
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					<?php echo e($reviews->links()); ?>

				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/reviews/index.blade.php ENDPATH**/ ?>