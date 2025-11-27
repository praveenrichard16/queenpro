

<?php $__env->startSection('title', 'Add Quotation'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Add Quotation</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.quotations.index')); ?>" class="hover-text-primary">Quotations</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Add</li>
		</ul>
	</div>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" action="<?php echo e(route('admin.quotations.store')); ?>" class="row g-4" id="quotationForm">
				<?php echo csrf_field(); ?>

				<div class="col-12">
					<h6 class="fw-semibold mb-16">Quotation Information</h6>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Lead <span class="text-danger">*</span></label>
					<select name="lead_id" class="form-select bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['lead_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
						<option value="">Select Lead</option>
						<?php $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($lead->id); ?>" <?php if(old('lead_id') == $lead->id): echo 'selected'; endif; ?>><?php echo e($lead->name); ?> (<?php echo e($lead->email ?? 'No email'); ?>)</option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
					<?php $__errorArgs = ['lead_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Quote Number</label>
					<input type="text" name="quote_number" value="<?php echo e(old('quote_number')); ?>" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['quote_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Auto-generated if left empty">
					<?php $__errorArgs = ['quote_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					<div class="form-text mt-1">Leave empty to auto-generate</div>
				</div>

				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Status <span class="text-danger">*</span></label>
					<select name="status" class="form-select bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
						<option value="draft" <?php if(old('status', 'draft') === 'draft'): echo 'selected'; endif; ?>>Draft</option>
						<option value="sent" <?php if(old('status') === 'sent'): echo 'selected'; endif; ?>>Sent</option>
						<option value="accepted" <?php if(old('status') === 'accepted'): echo 'selected'; endif; ?>>Accepted</option>
						<option value="rejected" <?php if(old('status') === 'rejected'): echo 'selected'; endif; ?>>Rejected</option>
						<option value="expired" <?php if(old('status') === 'expired'): echo 'selected'; endif; ?>>Expired</option>
					</select>
					<?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>

				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Currency <span class="text-danger">*</span></label>
					<input type="text" name="currency" value="<?php echo e(old('currency', currency_code())); ?>" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required maxlength="3">
					<?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>

				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Valid Until</label>
					<input type="date" name="valid_until" value="<?php echo e(old('valid_until')); ?>" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['valid_until'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" min="<?php echo e(date('Y-m-d', strtotime('+1 day'))); ?>">
					<?php $__errorArgs = ['valid_until'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>

				<div class="col-12">
					<hr class="my-4">
					<h6 class="fw-semibold mb-16">Quotation Items</h6>
				</div>

				<div class="col-12" id="itemsContainer">
					<div class="item-row mb-3 p-3 border rounded">
						<div class="row g-3">
							<div class="col-lg-5">
								<label class="form-label text-secondary-light">Product <span class="text-danger">*</span></label>
								<select name="items[0][product_id]" class="form-select bg-neutral-50 radius-12 h-56-px" required>
									<option value="">Select Product</option>
									<?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<option value="<?php echo e($product->id); ?>" data-price="<?php echo e($product->effective_price); ?>"><?php echo e($product->name); ?> - <?php echo e(currency($product->effective_price)); ?></option>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</select>
							</div>
							<div class="col-lg-2">
								<label class="form-label text-secondary-light">Quantity <span class="text-danger">*</span></label>
								<input type="number" name="items[0][quantity]" value="1" min="1" class="form-control bg-neutral-50 radius-12 h-56-px item-quantity" required>
							</div>
							<div class="col-lg-2">
								<label class="form-label text-secondary-light">Price <span class="text-danger">*</span></label>
								<input type="number" name="items[0][price]" step="0.01" min="0" class="form-control bg-neutral-50 radius-12 h-56-px item-price" required>
							</div>
							<div class="col-lg-2">
								<label class="form-label text-secondary-light">Tax Amount</label>
								<input type="number" name="items[0][tax_amount]" step="0.01" min="0" class="form-control bg-neutral-50 radius-12 h-56-px item-tax">
							</div>
							<div class="col-lg-1 d-flex align-items-end">
								<button type="button" class="btn btn-danger btn-sm remove-item" style="display:none;">
									<iconify-icon icon="solar:trash-bin-outline"></iconify-icon>
								</button>
							</div>
						</div>
					</div>
				</div>

				<div class="col-12">
					<button type="button" class="btn btn-outline-primary" id="addItem">Add Item</button>
				</div>

				<div class="col-12">
					<hr class="my-4">
					<h6 class="fw-semibold mb-16">Notes</h6>
				</div>

				<div class="col-12">
					<textarea name="notes" rows="4" class="form-control bg-neutral-50 radius-12 <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('notes')); ?></textarea>
					<?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>

				<div class="col-12">
					<div class="d-flex gap-2">
						<button type="submit" class="btn btn-primary radius-12 h-56-px">Create Quotation</button>
						<a href="<?php echo e(route('admin.quotations.index')); ?>" class="btn btn-outline-secondary radius-12 h-56-px">Cancel</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
	let itemIndex = 1;
	
	document.getElementById('addItem').addEventListener('click', function() {
		const container = document.getElementById('itemsContainer');
		const newItem = container.firstElementChild.cloneNode(true);
		
		// Update indices
		newItem.querySelectorAll('select, input').forEach(input => {
			if (input.name) {
				input.name = input.name.replace(/\[0\]/, '[' + itemIndex + ']');
				input.value = '';
			}
		});
		
		newItem.querySelector('.remove-item').style.display = 'block';
		container.appendChild(newItem);
		itemIndex++;
	});
	
	document.addEventListener('click', function(e) {
		if (e.target.closest('.remove-item')) {
			if (document.querySelectorAll('.item-row').length > 1) {
				e.target.closest('.item-row').remove();
			}
		}
	});
	
	// Auto-fill price when product is selected
	document.addEventListener('change', function(e) {
		if (e.target.matches('select[name*="[product_id]"]')) {
			const option = e.target.options[e.target.selectedIndex];
			const price = option.dataset.price;
			const priceInput = e.target.closest('.item-row').querySelector('.item-price');
			if (price && priceInput) {
				priceInput.value = price;
			}
		}
	});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/quotations/create.blade.php ENDPATH**/ ?>