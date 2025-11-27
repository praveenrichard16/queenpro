

<?php $__env->startSection('title', 'WhatsApp Catalog Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<div>
			<h6 class="fw-semibold mb-0">WhatsApp Catalog Management</h6>
			<p class="text-secondary-light mb-0">Sync products to WhatsApp Business Catalog and manage catalog links.</p>
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
				<a href="<?php echo e(route('admin.integrations.index', ['tab' => 'whatsapp'])); ?>" class="hover-text-primary">Integrations</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">WhatsApp Catalog</li>
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

	<?php if(session('catalog_link')): ?>
		<div class="alert alert-info alert-dismissible fade show" role="alert">
			<strong>Catalog Link:</strong> 
			<a href="<?php echo e(session('catalog_link')); ?>" target="_blank" class="text-white text-decoration-underline"><?php echo e(session('catalog_link')); ?></a>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<div class="card border-0 shadow-none bg-base mb-24">
		<div class="card-body p-24">
			<div class="row g-3 align-items-end">
				<div class="col-md-4">
					<label class="form-label text-secondary-light">Search Products</label>
					<form method="GET" class="d-flex gap-2">
						<input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control bg-neutral-50 radius-12 h-56-px" placeholder="Search by name or SKU">
						<button type="submit" class="btn btn-primary h-56-px radius-12">Search</button>
					</form>
				</div>
				<div class="col-md-3">
					<label class="form-label text-secondary-light">Sync Status</label>
					<form method="GET" onchange="this.submit()">
						<input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
						<select name="sync_status" class="form-select bg-neutral-50 radius-12 h-56-px">
							<option value="">All Products</option>
							<option value="synced" <?php if(request('sync_status') === 'synced'): echo 'selected'; endif; ?>>Synced</option>
							<option value="not_synced" <?php if(request('sync_status') === 'not_synced'): echo 'selected'; endif; ?>>Not Synced</option>
						</select>
					</form>
				</div>
				<div class="col-md-5 d-flex gap-2 justify-content-end">
					<form action="<?php echo e(route('admin.whatsapp-catalog.get-link')); ?>" method="POST" class="d-inline">
						<?php echo csrf_field(); ?>
						<button type="submit" class="btn btn-info text-white h-56-px radius-12">
							<iconify-icon icon="solar:link-outline"></iconify-icon>
							Get Catalog Link
						</button>
					</form>
					<button type="button" class="btn btn-warning text-white h-56-px radius-12" data-bs-toggle="modal" data-bs-target="#bulkSyncModal">
						<iconify-icon icon="solar:refresh-outline"></iconify-icon>
						Bulk Sync
					</button>
				</div>
			</div>
		</div>
	</div>

	<div class="card basic-data-table border-0">
		<div class="card-body p-0">
			<form id="bulkSyncForm" action="<?php echo e(route('admin.whatsapp-catalog.sync-multiple')); ?>" method="POST">
				<?php echo csrf_field(); ?>
				<div class="table-responsive">
					<table class="table bordered-table mb-0 align-middle">
						<thead>
							<tr>
								<th width="50">
									<input type="checkbox" id="selectAll" class="form-check-input">
								</th>
								<th>Product</th>
								<th>Price</th>
								<th>Stock</th>
								<th>Sync Status</th>
								<th>Last Synced</th>
								<th class="text-end">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
								<tr>
									<td>
										<input type="checkbox" name="product_ids[]" value="<?php echo e($product->id); ?>" class="form-check-input product-checkbox">
									</td>
									<td>
										<div class="d-flex align-items-center gap-3">
											<?php if($product->image): ?>
												<img src="<?php echo e(asset('storage/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>" class="w-48-px h-48-px object-fit-cover radius-8">
											<?php endif; ?>
											<div>
												<h6 class="text-md mb-0 fw-medium"><?php echo e($product->name); ?></h6>
												<?php if($product->sku): ?>
													<span class="text-sm text-secondary-light">SKU: <?php echo e($product->sku); ?></span>
												<?php endif; ?>
											</div>
										</div>
									</td>
									<td>
										<span class="fw-semibold"><?php echo e(currency($product->effective_price)); ?></span>
									</td>
									<td>
										<span class="badge bg-<?php echo e($product->stock_quantity > 0 ? 'success' : 'danger'); ?>">
											<?php echo e($product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock'); ?>

										</span>
									</td>
									<td>
										<?php if($product->is_synced_to_whatsapp): ?>
											<span class="badge bg-success">
												<iconify-icon icon="solar:check-circle-bold"></iconify-icon>
												Synced
											</span>
										<?php else: ?>
											<span class="badge bg-secondary">Not Synced</span>
										<?php endif; ?>
										<?php if($product->whatsapp_sync_error): ?>
											<div class="text-danger text-sm mt-1" title="<?php echo e($product->whatsapp_sync_error); ?>">
												<iconify-icon icon="solar:danger-triangle-bold"></iconify-icon>
												Error
											</div>
										<?php endif; ?>
									</td>
									<td>
										<?php if($product->whatsapp_synced_at): ?>
											<span class="text-sm text-secondary-light"><?php echo e($product->whatsapp_synced_at->format('M d, Y H:i')); ?></span>
										<?php else: ?>
											<span class="text-sm text-secondary-light">—</span>
										<?php endif; ?>
									</td>
									<td class="text-end">
										<div class="d-inline-flex gap-2">
											<?php if(!$product->is_synced_to_whatsapp): ?>
												<form action="<?php echo e(route('admin.whatsapp-catalog.sync', $product)); ?>" method="POST" class="d-inline">
													<?php echo csrf_field(); ?>
													<button type="submit" class="btn btn-primary btn-sm radius-8">
														<iconify-icon icon="solar:upload-outline"></iconify-icon>
														Sync
													</button>
												</form>
											<?php else: ?>
												<form action="<?php echo e(route('admin.whatsapp-catalog.sync', $product)); ?>" method="POST" class="d-inline">
													<?php echo csrf_field(); ?>
													<button type="submit" class="btn btn-outline-primary btn-sm radius-8">
														<iconify-icon icon="solar:refresh-outline"></iconify-icon>
														Re-sync
													</button>
												</form>
											<?php endif; ?>
										</div>
									</td>
								</tr>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
								<tr>
									<td colspan="7" class="text-center py-80">
										<img src="<?php echo e(asset('wowdash/assets/images/notification/empty-message-icon1.png')); ?>" alt="No products" class="mb-16" style="max-width:120px;">
										<h6 class="fw-semibold mb-8">No products found</h6>
										<p class="text-secondary-light mb-0">Try adjusting your filters or add products first.</p>
									</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>

				<?php if($products->hasPages()): ?>
					<div class="card-footer border-0 bg-transparent py-16 px-24">
						<?php echo e($products->links()); ?>

					</div>
				<?php endif; ?>
			</form>
		</div>
	</div>
</div>

<!-- Bulk Sync Modal -->
<div class="modal fade" id="bulkSyncModal" tabindex="-1" aria-labelledby="bulkSyncModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="bulkSyncModalLabel">Bulk Sync Products</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p>Sync selected products to WhatsApp catalog. This may take a few moments.</p>
				<p class="text-sm text-secondary-light mb-0">Selected products: <span id="selectedCount">0</span></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="document.getElementById('bulkSyncForm').submit();">Sync Selected</button>
			</div>
		</div>
	</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
	document.getElementById('selectAll')?.addEventListener('change', function() {
		const checkboxes = document.querySelectorAll('.product-checkbox');
		checkboxes.forEach(checkbox => {
			checkbox.checked = this.checked;
		});
		updateSelectedCount();
	});

	document.querySelectorAll('.product-checkbox').forEach(checkbox => {
		checkbox.addEventListener('change', updateSelectedCount);
	});

	function updateSelectedCount() {
		const selected = document.querySelectorAll('.product-checkbox:checked').length;
		document.getElementById('selectedCount').textContent = selected;
	}

	document.getElementById('bulkSyncForm')?.addEventListener('submit', function(e) {
		const selected = document.querySelectorAll('.product-checkbox:checked').length;
		if (selected === 0) {
			e.preventDefault();
			alert('Please select at least one product to sync.');
			return false;
		}
	});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/integrations/whatsapp-catalog.blade.php ENDPATH**/ ?>