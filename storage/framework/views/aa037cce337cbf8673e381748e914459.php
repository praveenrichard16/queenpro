

<?php $__env->startSection('title', 'Brands'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Brands</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Brands</li>
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
				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Search</label>
					<div class="icon-field">
						<span class="icon top-50 translate-middle-y"><iconify-icon icon="mage:search"></iconify-icon></span>
						<input type="text" class="form-control h-56-px bg-neutral-50 radius-12" name="search" value="<?php echo e(request('search')); ?>" placeholder="Brand name">
					</div>
				</div>
				<div class="col-lg-3">
					<label class="form-label text-secondary-light">Status</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="status">
						<option value="">All statuses</option>
						<option value="1" <?php if(request('status') === '1'): echo 'selected'; endif; ?>>Active</option>
						<option value="0" <?php if(request('status') === '0'): echo 'selected'; endif; ?>>Inactive</option>
					</select>
				</div>
				<div class="col-lg-3 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
					<a href="<?php echo e(route('admin.brands.index')); ?>" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
					</a>
				</div>
				<div class="col-lg-2">
					<a href="<?php echo e(route('admin.brands.create')); ?>" class="btn btn-warning radius-12 text-white w-100 h-56-px d-inline-flex align-items-center justify-content-center gap-2">
						<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
						Add Brand
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
							<th>Brand</th>
							<th>Logo</th>
							<th>Status</th>
							<th class="text-end">Products</th>
							<th class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<tr>
								<td>
									<h6 class="text-md mb-2 fw-medium"><?php echo e($brand->name); ?></h6>
									<p class="text-sm text-secondary-light mb-0"><?php echo e($brand->slug); ?></p>
								</td>
								<td>
									<?php if($brand->logo_path): ?>
										<img src="<?php echo e($brand->logo_path); ?>" alt="<?php echo e($brand->name); ?>" style="max-width: 60px; max-height: 60px; object-fit: contain;">
									<?php else: ?>
										<span class="text-secondary-light">—</span>
									<?php endif; ?>
								</td>
								<td>
									<span class="px-20 py-6 rounded-pill fw-semibold text-sm <?php echo e($brand->is_active ? 'bg-success-focus text-success-main' : 'bg-neutral-200 text-neutral-600'); ?>">
										<?php echo e($brand->is_active ? 'Active' : 'Inactive'); ?>

									</span>
								</td>
								<td class="text-end"><?php echo e($brand->products_count); ?></td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="<?php echo e(route('admin.brands.edit', $brand)); ?>" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center">
											<iconify-icon icon="lucide:edit"></iconify-icon>
										</a>
										<form action="<?php echo e(route('admin.brands.destroy', $brand)); ?>" method="POST" onsubmit="return confirm('Delete this brand?')" class="d-inline">
											<?php echo csrf_field(); ?>
											<?php echo method_field('DELETE'); ?>
											<button type="submit" class="w-36-px h-36-px bg-danger-focus text-danger-main rounded-circle border-0 d-inline-flex align-items-center justify-content-center">
												<iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
											</button>
										</form>
									</div>
								</td>
							</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
							<tr>
								<td colspan="5" class="text-center py-80">
									<img src="<?php echo e(asset('wowdash/assets/images/notification/empty-message-icon1.png')); ?>" alt="No brands" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No brands found</h6>
									<p class="text-secondary-light mb-0">Create your first brand to organize products.</p>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<?php if($brands->hasPages()): ?>
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					<?php echo e($brands->links()); ?>

				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/brands/index.blade.php ENDPATH**/ ?>