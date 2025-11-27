

<?php $__env->startSection('title', 'Home Page Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Home Page Settings</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Home Page</li>
		</ul>
	</div>

	<?php if(session('success')): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?php echo e(session('success')); ?>

			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<?php if(session('warning')): ?>
		<div class="alert alert-warning alert-dismissible fade show" role="alert">
			<?php echo e(session('warning')); ?>

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
					<div class="nav flex-column nav-pills gap-2" id="home-tabs" role="tablist" aria-orientation="vertical">
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(session('active_tab', 'hero') === 'hero' ? 'active' : ''); ?>" id="home-tab-hero" data-bs-toggle="pill" data-bs-target="#home-pane-hero" type="button" role="tab" aria-controls="home-pane-hero" aria-selected="<?php echo e(session('active_tab', 'hero') === 'hero' ? 'true' : 'false'); ?>">
							<span>Home Hero Slider</span>
							<iconify-icon icon="solar:slider-vertical-linear" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(session('active_tab') === 'second' ? 'active' : ''); ?>" id="home-tab-second" data-bs-toggle="pill" data-bs-target="#home-pane-second" type="button" role="tab" aria-controls="home-pane-second" aria-selected="<?php echo e(session('active_tab') === 'second' ? 'true' : 'false'); ?>">
							<span>Second Section Slider</span>
							<iconify-icon icon="solar:image-bold-duotone" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(session('active_tab') === 'product' ? 'active' : ''); ?>" id="home-tab-product" data-bs-toggle="pill" data-bs-target="#home-pane-product" type="button" role="tab" aria-controls="home-pane-product" aria-selected="<?php echo e(session('active_tab') === 'product' ? 'true' : 'false'); ?>">
							<span>Product Slider</span>
							<iconify-icon icon="solar:bag-heart-linear" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(session('active_tab') === 'third' ? 'active' : ''); ?>" id="home-tab-third" data-bs-toggle="pill" data-bs-target="#home-pane-third" type="button" role="tab" aria-controls="home-pane-third" aria-selected="<?php echo e(session('active_tab') === 'third' ? 'true' : 'false'); ?>">
							<span>3rd Section Slider</span>
							<iconify-icon icon="solar:image-bold-duotone" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(session('active_tab') === 'product2' ? 'active' : ''); ?>" id="home-tab-product2" data-bs-toggle="pill" data-bs-target="#home-pane-product2" type="button" role="tab" aria-controls="home-pane-product2" aria-selected="<?php echo e(session('active_tab') === 'product2' ? 'true' : 'false'); ?>">
							<span>Product Slider 2</span>
							<iconify-icon icon="solar:bag-4-linear" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(session('active_tab') === 'reviews' ? 'active' : ''); ?>" id="home-tab-reviews" data-bs-toggle="pill" data-bs-target="#home-pane-reviews" type="button" role="tab" aria-controls="home-pane-reviews" aria-selected="<?php echo e(session('active_tab') === 'reviews' ? 'true' : 'false'); ?>">
							<span>Reviews</span>
							<iconify-icon icon="solar:star-smile-linear" class="text-lg"></iconify-icon>
						</button>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-9">
			<div class="tab-content" id="home-tabs-content">
				<!-- Home Hero Slider Tab -->
				<div class="tab-pane fade <?php echo e(session('active_tab', 'hero') === 'hero' ? 'show active' : ''); ?>" id="home-pane-hero" role="tabpanel" aria-labelledby="home-tab-hero">
					<div class="card border-0 mb-24">
						<div class="card-body p-24">
							<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-16">
								<div>
									<h6 class="fw-semibold mb-4">Home Hero Slider</h6>
									<p class="text-secondary-light mb-0">Manage up to three hero slides for the storefront homepage.</p>
								</div>
								<div class="d-flex gap-2">
									<a href="<?php echo e(route('admin.cms.home.seo.settings')); ?>" class="btn btn-outline-secondary radius-12">
										<iconify-icon icon="solar:seo-linear" class="me-1"></iconify-icon>
										SEO Settings
									</a>
									<a href="<?php echo e(route('admin.cms.home.sliders.create')); ?>?active_tab=hero"
									   class="btn btn-primary radius-12 <?php echo e($canCreate ? '' : 'disabled'); ?>"
									   <?php echo e($canCreate ? '' : 'aria-disabled=true'); ?>>
										<iconify-icon icon="solar:add-circle-linear" class="me-1"></iconify-icon>
										New Slide
									</a>
								</div>
							</div>

							<div class="table-responsive">
								<table class="table align-middle mb-0">
									<thead class="bg-light">
									<tr>
										<th style="width: 80px;">Order</th>
										<th>Preview</th>
										<th>Title</th>
										<th>Status</th>
										<th>Updated</th>
										<th class="text-end" style="width: 160px;">Actions</th>
									</tr>
									</thead>
									<tbody>
									<?php $__empty_1 = true; $__currentLoopData = $sliders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
										<tr>
											<td>
												<span class="badge bg-secondary"><?php echo e($slider->sort_order); ?></span>
											</td>
											<td>
												<div class="d-flex align-items-center gap-3">
													<img src="<?php echo e(asset('storage/' . $slider->desktop_image_path)); ?>"
														 alt="<?php echo e($slider->alt_text ?? $slider->title ?? 'Slide preview'); ?>"
														 class="rounded border"
														 style="width:120px;height:68px;object-fit:cover;"
														 onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'120\' height=\'68\'%3E%3Crect fill=\'%23ddd\' width=\'120\' height=\'68\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\' font-size=\'12\'%3EImage not found%3C/text%3E%3C/svg%3E';">
													<div class="text-muted small">
														<div>
															<iconify-icon icon="solar:monitor-line-duotone" class="me-1"></iconify-icon>
															1600×900
														</div>
														<div>
															<iconify-icon icon="solar:phone-line-duotone" class="me-1"></iconify-icon>
															800×1300
														</div>
													</div>
												</div>
											</td>
											<td>
												<div class="fw-semibold"><?php echo e($slider->title ?? 'Untitled slide'); ?></div>
												<?php if($slider->button_text && $slider->button_link): ?>
													<div class="text-muted small"><?php echo e($slider->button_text); ?> → <?php echo e($slider->button_link); ?></div>
												<?php endif; ?>
											</td>
											<td>
												<?php if($slider->is_active): ?>
													<span class="badge bg-success-subtle text-success">Active</span>
												<?php else: ?>
													<span class="badge bg-secondary-subtle text-secondary">Hidden</span>
												<?php endif; ?>
											</td>
											<td class="text-muted">
												<?php echo e($slider->updated_at->format('d M Y, H:i')); ?>

											</td>
											<td class="text-end">
												<div class="d-inline-flex gap-2">
													<a href="<?php echo e(route('admin.cms.home.sliders.edit', $slider)); ?>?active_tab=hero" class="btn btn-sm btn-outline-secondary">
														Edit
													</a>
													<form action="<?php echo e(route('admin.cms.home.sliders.destroy', $slider)); ?>" method="POST" onsubmit="return confirm('Delete this slide?');" class="d-inline">
														<?php echo csrf_field(); ?>
														<?php echo method_field('DELETE'); ?>
														<input type="hidden" name="active_tab" value="hero">
														<button type="submit" class="btn btn-sm btn-outline-danger">
															Delete
														</button>
													</form>
												</div>
											</td>
										</tr>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
										<tr>
											<td colspan="6" class="text-center py-5 text-muted">
												No slides created yet. Click "New Slide" to add your first hero image.
											</td>
										</tr>
									<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				<!-- Second Section Slider Tab -->
				<div class="tab-pane fade <?php echo e(session('active_tab') === 'second' ? 'show active' : ''); ?>" id="home-pane-second" role="tabpanel" aria-labelledby="home-tab-second">
					<div class="card border-0 mb-24">
						<div class="card-body p-24">
							<div class="mb-16">
								<h6 class="fw-semibold mb-4">2nd Section Slider</h6>
								<p class="text-secondary-light mb-0">Upload the secondary promotional banner that appears after featured products on the storefront.</p>
							</div>

							<form method="POST"
								  action="<?php echo e(route('admin.cms.home.second-slider.update')); ?>"
								  enctype="multipart/form-data"
								  class="row g-4">
								<?php echo csrf_field(); ?>
								<input type="hidden" name="active_tab" value="second">

								<div class="col-12">
									<div class="form-check form-switch">
										<input type="checkbox"
											   class="form-check-input"
											   name="is_active"
											   id="second-slider-active"
											   value="1"
											   <?php echo e(old('is_active', $secondSlider['is_active']) ? 'checked' : ''); ?>>
										<label class="form-check-label fw-medium" for="second-slider-active">
											Display 2nd section slider on the homepage
										</label>
									</div>
								</div>

								<div class="col-md-6">
									<label class="form-label text-secondary-light">Desktop image (min 1200×500px)</label>
									<input type="file"
										   name="desktop_image"
										   class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['desktop_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
									<?php $__errorArgs = ['desktop_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									<div class="form-text mt-1">Upload JPG/PNG/WebP up to 4MB. This image is shown on laptop & desktop screens.</div>
									<?php if($secondSlider['desktop_image_path']): ?>
										<div class="mt-3">
											<div class="text-secondary-light mb-2">Current desktop preview</div>
											<img src="<?php echo e(asset('storage/' . $secondSlider['desktop_image_path'])); ?>"
												 alt="Current desktop visual"
												 class="img-fluid rounded-3 border"
												 style="max-height:240px;object-fit:cover;">
										</div>
									<?php endif; ?>
								</div>

								<div class="col-md-6">
									<label class="form-label text-secondary-light">Mobile image (min 750×1000px)</label>
									<input type="file"
										   name="mobile_image"
										   class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['mobile_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
									<?php $__errorArgs = ['mobile_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									<div class="form-text mt-1">Upload JPG/PNG/WebP up to 4MB that fits portrait screens.</div>
									<?php if($secondSlider['mobile_image_path']): ?>
										<div class="mt-3">
											<div class="text-secondary-light mb-2">Current mobile preview</div>
											<img src="<?php echo e(asset('storage/' . $secondSlider['mobile_image_path'])); ?>"
												 alt="Current mobile visual"
												 class="img-fluid rounded-3 border"
												 style="max-height:240px;object-fit:cover;">
										</div>
									<?php endif; ?>
								</div>

								<div class="col-md-6">
									<label class="form-label text-secondary-light">Alt text</label>
									<input type="text"
										   name="alt_text"
										   class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['alt_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
										   value="<?php echo e(old('alt_text', $secondSlider['alt_text'])); ?>"
										   placeholder="Describe the visual for accessibility">
									<?php $__errorArgs = ['alt_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>

								<div class="col-md-6">
									<label class="form-label text-secondary-light">Optional link</label>
									<input type="url"
										   name="button_link"
										   class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['button_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
										   value="<?php echo e(old('button_link', $secondSlider['button_link'])); ?>"
										   placeholder="https://example.com/collection">
									<?php $__errorArgs = ['button_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									<div class="form-text mt-1">Provide a URL to make the slider clickable.</div>
								</div>

								<div class="col-12 d-flex gap-3 mt-3">
									<button type="submit" class="btn btn-primary radius-12 px-24">
										<iconify-icon icon="solar:floppy-disk-line-duotone" class="me-1"></iconify-icon>
										Save slider
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<!-- Product Slider Tab -->
				<div class="tab-pane fade <?php echo e(session('active_tab') === 'product' ? 'show active' : ''); ?>" id="home-pane-product" role="tabpanel" aria-labelledby="home-tab-product">
					<div class="card border-0 mb-24">
						<div class="card-body p-24">
							<div class="mb-16">
								<h6 class="fw-semibold mb-4">Homepage Product Slides</h6>
								<p class="text-secondary-light mb-0">Select the exact products to highlight after the 2nd section slider. Order is preserved.</p>
							</div>

							<form method="POST" action="<?php echo e(route('admin.cms.home.product-slides.update')); ?>" class="row g-4">
								<?php echo csrf_field(); ?>
								<input type="hidden" name="active_tab" value="product">

								<div class="col-lg-5">
									<label class="form-label text-secondary-light">Add products to the carousel</label>
									<select id="productSelect" class="form-select bg-neutral-50 radius-12 h-56-px">
										<option value="">Search or choose a product</option>
										<?php $__currentLoopData = $productOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($product->id); ?>"
													data-active="<?php echo e($product->is_active ? '1' : '0'); ?>">
												<?php echo e($product->name); ?> <?php if (! ($product->is_active)): ?> — Inactive <?php endif; ?>
											</option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</select>
									<div class="d-flex gap-2 mt-3">
										<button type="button" id="addProductBtn" class="btn btn-primary radius-12 flex-grow-1">
											<iconify-icon icon="solar:add-circle-linear" class="me-1"></iconify-icon>
											Add to list
										</button>
										<button type="button" id="clearProductsBtn" class="btn btn-outline-secondary radius-12">
											Clear all
										</button>
									</div>
									<div class="form-text mt-2" id="productSlidesLimitMessage">
										You can feature up to <?php echo e($maxSlides); ?> products. Scroll order = display order on the storefront.
									</div>
									<?php $__errorArgs = ['product_slides'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									<?php $__errorArgs = ['product_slides.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>

								<div class="col-lg-7">
									<label class="form-label text-secondary-light d-flex justify-content-between">
										<span>Selected products</span>
										<span class="text-muted small">Use ↑ / ↓ controls to reorder</span>
									</label>
									<div id="selectedProductsEmpty" class="border rounded-3 p-4 text-center text-secondary-light <?php echo e($selectedProducts->isNotEmpty() ? 'd-none' : ''); ?>" style="border-style: dashed;">
										No products selected yet.
									</div>

									<ul class="list-group gap-3" id="selectedProductsList">
										<?php $__currentLoopData = $selectedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<li class="list-group-item d-flex align-items-center justify-content-between radius-12 border flex-wrap gap-3 product-slide-item" data-id="<?php echo e($product->id); ?>">
												<div class="d-flex align-items-center gap-3">
													<span class="badge bg-neutral-100 text-secondary-light border order-index"></span>
													<div>
														<div class="fw-semibold"><?php echo e($product->name); ?></div>
														<div class="text-secondary-light small">
															<?php if (! ($product->is_active)): ?>
																<span class="text-danger">Inactive</span>
															<?php endif; ?>
														</div>
													</div>
												</div>
												<div class="d-flex align-items-center gap-2 ms-auto">
													<button type="button" class="btn btn-sm btn-outline-secondary move-product-btn" data-direction="-1">
														<iconify-icon icon="solar:alt-arrow-up-linear"></iconify-icon>
													</button>
													<button type="button" class="btn btn-sm btn-outline-secondary move-product-btn" data-direction="1">
														<iconify-icon icon="solar:alt-arrow-down-linear"></iconify-icon>
													</button>
													<button type="button" class="btn btn-sm btn-outline-danger remove-product-btn">
														<iconify-icon icon="solar:trash-bin-trash-line-duotone"></iconify-icon>
													</button>
												</div>
												<input type="hidden" name="product_slides[]" value="<?php echo e($product->id); ?>">
											</li>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</ul>
								</div>

								<div class="col-12 d-flex gap-3 mt-3">
									<button type="submit" class="btn btn-primary radius-12 px-24">
										<iconify-icon icon="solar:floppy-disk-line-duotone" class="me-1"></iconify-icon>
										Save product slides
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<!-- 3rd Section Slider Tab -->
				<div class="tab-pane fade <?php echo e(session('active_tab') === 'third' ? 'show active' : ''); ?>" id="home-pane-third" role="tabpanel" aria-labelledby="home-tab-third">
					<div class="card border-0 mb-24">
						<div class="card-body p-24">
							<div class="mb-16">
								<h6 class="fw-semibold mb-4">3rd Section Slider</h6>
								<p class="text-secondary-light mb-0">Upload the third promotional banner that appears after the product slider on the storefront.</p>
							</div>

							<form method="POST"
								  action="<?php echo e(route('admin.cms.home.third-slider.update')); ?>"
								  enctype="multipart/form-data"
								  class="row g-4">
								<?php echo csrf_field(); ?>
								<input type="hidden" name="active_tab" value="third">

								<div class="col-12">
									<div class="form-check form-switch">
										<input type="checkbox"
											   class="form-check-input"
											   name="is_active"
											   id="third-slider-active"
											   value="1"
											   <?php echo e(old('is_active', $thirdSlider['is_active']) ? 'checked' : ''); ?>>
										<label class="form-check-label fw-medium" for="third-slider-active">
											Display 3rd section slider on the homepage
										</label>
									</div>
								</div>

								<div class="col-md-6">
									<label class="form-label text-secondary-light">Desktop image (min 1200×500px)</label>
									<input type="file"
										   name="desktop_image"
										   class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['desktop_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
									<?php $__errorArgs = ['desktop_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									<div class="form-text mt-1">Upload JPG/PNG/WebP up to 4MB. This image is shown on laptop & desktop screens.</div>
									<?php if($thirdSlider['desktop_image_path']): ?>
										<div class="mt-3">
											<div class="text-secondary-light mb-2">Current desktop preview</div>
											<img src="<?php echo e(asset('storage/' . $thirdSlider['desktop_image_path'])); ?>"
												 alt="Current desktop visual"
												 class="img-fluid rounded-3 border"
												 style="max-height:240px;object-fit:cover;">
										</div>
									<?php endif; ?>
								</div>

								<div class="col-md-6">
									<label class="form-label text-secondary-light">Mobile image (min 750×1000px)</label>
									<input type="file"
										   name="mobile_image"
										   class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['mobile_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
									<?php $__errorArgs = ['mobile_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									<div class="form-text mt-1">Upload JPG/PNG/WebP up to 4MB that fits portrait screens.</div>
									<?php if($thirdSlider['mobile_image_path']): ?>
										<div class="mt-3">
											<div class="text-secondary-light mb-2">Current mobile preview</div>
											<img src="<?php echo e(asset('storage/' . $thirdSlider['mobile_image_path'])); ?>"
												 alt="Current mobile visual"
												 class="img-fluid rounded-3 border"
												 style="max-height:240px;object-fit:cover;">
										</div>
									<?php endif; ?>
								</div>

								<div class="col-md-6">
									<label class="form-label text-secondary-light">Alt text</label>
									<input type="text"
										   name="alt_text"
										   class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['alt_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
										   value="<?php echo e(old('alt_text', $thirdSlider['alt_text'])); ?>"
										   placeholder="Describe the visual for accessibility">
									<?php $__errorArgs = ['alt_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>

								<div class="col-md-6">
									<label class="form-label text-secondary-light">Optional link</label>
									<input type="url"
										   name="button_link"
										   class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['button_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
										   value="<?php echo e(old('button_link', $thirdSlider['button_link'])); ?>"
										   placeholder="https://example.com/collection">
									<?php $__errorArgs = ['button_link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									<div class="form-text mt-1">Provide a URL to make the slider clickable.</div>
								</div>

								<div class="col-12 d-flex gap-3 mt-3">
									<button type="submit" class="btn btn-primary radius-12 px-24">
										<iconify-icon icon="solar:floppy-disk-line-duotone" class="me-1"></iconify-icon>
										Save slider
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<!-- Product Slider 2 Tab -->
				<div class="tab-pane fade <?php echo e(session('active_tab') === 'product2' ? 'show active' : ''); ?>" id="home-pane-product2" role="tabpanel" aria-labelledby="home-tab-product2">
					<div class="card border-0 mb-24">
						<div class="card-body p-24">
							<div class="mb-16">
								<h6 class="fw-semibold mb-4">Product Slider 2</h6>
								<p class="text-secondary-light mb-0">Select products to display in a fixed grid layout (4 columns desktop, 2 columns mobile) after the 3rd section slider.</p>
							</div>

							<form method="POST" action="<?php echo e(route('admin.cms.home.product-slider-2.update')); ?>" class="row g-4">
								<?php echo csrf_field(); ?>
								<input type="hidden" name="active_tab" value="product2">

								<div class="col-lg-5">
									<label class="form-label text-secondary-light">Add products to the grid</label>
									<select id="productSlider2Select" class="form-select bg-neutral-50 radius-12 h-56-px">
										<option value="">Search or choose a product</option>
										<?php $__currentLoopData = $productOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($product->id); ?>"
													data-active="<?php echo e($product->is_active ? '1' : '0'); ?>">
												<?php echo e($product->name); ?> <?php if (! ($product->is_active)): ?> — Inactive <?php endif; ?>
											</option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</select>
									<div class="d-flex gap-2 mt-3">
										<button type="button" id="addProductSlider2Btn" class="btn btn-primary radius-12 flex-grow-1">
											<iconify-icon icon="solar:add-circle-linear" class="me-1"></iconify-icon>
											Add to list
										</button>
										<button type="button" id="clearProductSlider2Btn" class="btn btn-outline-secondary radius-12">
											Clear all
										</button>
									</div>
									<div class="form-text mt-2" id="productSlider2LimitMessage">
										You can feature up to <?php echo e($maxProductSlider2); ?> products. Order is preserved.
									</div>
									<?php $__errorArgs = ['product_slider2'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									<?php $__errorArgs = ['product_slider2.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>

								<div class="col-lg-7">
									<label class="form-label text-secondary-light d-flex justify-content-between">
										<span>Selected products</span>
										<span class="text-muted small">Use ↑ / ↓ controls to reorder</span>
									</label>
									<div id="selectedProductSlider2Empty" class="border rounded-3 p-4 text-center text-secondary-light <?php echo e($productSlider2Selected->isNotEmpty() ? 'd-none' : ''); ?>" style="border-style: dashed;">
										No products selected yet.
									</div>

									<ul class="list-group gap-3" id="selectedProductSlider2List">
										<?php $__currentLoopData = $productSlider2Selected; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<li class="list-group-item d-flex align-items-center justify-content-between radius-12 border flex-wrap gap-3 product-slider2-item" data-id="<?php echo e($product->id); ?>">
												<div class="d-flex align-items-center gap-3">
													<span class="badge bg-neutral-100 text-secondary-light border order-index"></span>
													<div>
														<div class="fw-semibold"><?php echo e($product->name); ?></div>
														<div class="text-secondary-light small">
															<?php if (! ($product->is_active)): ?>
																<span class="text-danger">Inactive</span>
															<?php endif; ?>
														</div>
													</div>
												</div>
												<div class="d-flex align-items-center gap-2 ms-auto">
													<button type="button" class="btn btn-sm btn-outline-secondary move-product-slider2-btn" data-direction="-1">
														<iconify-icon icon="solar:alt-arrow-up-linear"></iconify-icon>
													</button>
													<button type="button" class="btn btn-sm btn-outline-secondary move-product-slider2-btn" data-direction="1">
														<iconify-icon icon="solar:alt-arrow-down-linear"></iconify-icon>
													</button>
													<button type="button" class="btn btn-sm btn-outline-danger remove-product-slider2-btn">
														<iconify-icon icon="solar:trash-bin-trash-line-duotone"></iconify-icon>
													</button>
												</div>
												<input type="hidden" name="product_slider2[]" value="<?php echo e($product->id); ?>">
											</li>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</ul>
								</div>

								<div class="col-12 d-flex gap-3 mt-3">
									<button type="submit" class="btn btn-primary radius-12 px-24">
										<iconify-icon icon="solar:floppy-disk-line-duotone" class="me-1"></iconify-icon>
										Save product slider 2
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<!-- Reviews Tab -->
				<div class="tab-pane fade <?php echo e(session('active_tab') === 'reviews' ? 'show active' : ''); ?>" id="home-pane-reviews" role="tabpanel" aria-labelledby="home-tab-reviews">
					<div class="card border-0 mb-24">
						<div class="card-body p-24">
							<div class="mb-16">
								<h6 class="fw-semibold mb-4">Home Reviews</h6>
								<p class="text-secondary-light mb-0">Select reviews to display on the homepage with a custom title and description. Reviews will be shown as scrolling cards.</p>
							</div>

							<form method="POST" action="<?php echo e(route('admin.cms.home.reviews.update')); ?>" class="row g-4">
								<?php echo csrf_field(); ?>
								<input type="hidden" name="active_tab" value="reviews">

								<div class="col-12">
									<label class="form-label text-secondary-light">Section Title</label>
									<input type="text"
										   name="title"
										   class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
										   value="<?php echo e(old('title', $homeReviewsTitle)); ?>"
										   placeholder="What Our Customers Say">
									<?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>

								<div class="col-12">
									<label class="form-label text-secondary-light">Section Description</label>
									<textarea name="description"
											  rows="3"
											  class="form-control bg-neutral-50 radius-12 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
											  placeholder="Read what our satisfied customers have to say about their shopping experience."><?php echo e(old('description', $homeReviewsDescription)); ?></textarea>
									<?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>

								<div class="col-lg-5">
									<label class="form-label text-secondary-light">Add reviews to display</label>
									<select id="reviewSelect" class="form-select bg-neutral-50 radius-12 h-56-px">
										<option value="">Search or choose a review</option>
										<?php $__currentLoopData = $reviewOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($review->id); ?>"
													data-customer="<?php echo e($review->customer_name); ?>"
													data-rating="<?php echo e($review->rating); ?>"
													data-product="<?php echo e($review->product->name ?? 'N/A'); ?>"
													data-comment="<?php echo e(Str::limit($review->comment ?? '', 50)); ?>">
												<?php echo e($review->customer_name); ?> - <?php echo e($review->product->name ?? 'N/A'); ?> (<?php echo e($review->rating); ?>★)
											</option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</select>
									<div class="d-flex gap-2 mt-3">
										<button type="button" id="addReviewBtn" class="btn btn-primary radius-12 flex-grow-1">
											<iconify-icon icon="solar:add-circle-linear" class="me-1"></iconify-icon>
											Add to list
										</button>
										<button type="button" id="clearReviewsBtn" class="btn btn-outline-secondary radius-12">
											Clear all
										</button>
									</div>
									<div class="form-text mt-2" id="reviewsLimitMessage">
										You can feature up to <?php echo e($maxHomeReviews); ?> reviews. Order is preserved.
									</div>
									<?php $__errorArgs = ['home_reviews'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
									<?php $__errorArgs = ['home_reviews.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>

								<div class="col-lg-7">
									<label class="form-label text-secondary-light d-flex justify-content-between">
										<span>Selected reviews</span>
										<span class="text-muted small">Use ↑ / ↓ controls to reorder</span>
									</label>
									<div id="selectedReviewsEmpty" class="border rounded-3 p-4 text-center text-secondary-light <?php echo e($homeReviewsSelected->isNotEmpty() ? 'd-none' : ''); ?>" style="border-style: dashed;">
										No reviews selected yet.
									</div>

									<ul class="list-group gap-3" id="selectedReviewsList">
										<?php $__currentLoopData = $homeReviewsSelected; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<li class="list-group-item d-flex align-items-center justify-content-between radius-12 border flex-wrap gap-3 review-item" data-id="<?php echo e($review->id); ?>">
												<div class="d-flex align-items-center gap-3 flex-grow-1">
													<span class="badge bg-neutral-100 text-secondary-light border order-index"></span>
													<div class="flex-grow-1">
														<div class="fw-semibold"><?php echo e($review->customer_name); ?></div>
														<div class="text-secondary-light small">
															<?php for($i = 1; $i <= 5; $i++): ?>
																<iconify-icon icon="solar:star-bold" class="<?php echo e($i <= $review->rating ? 'text-warning' : 'text-secondary'); ?>"></iconify-icon>
															<?php endfor; ?>
															<?php if($review->product): ?>
																<span class="ms-2">- <?php echo e($review->product->name); ?></span>
															<?php endif; ?>
														</div>
														<?php if($review->title): ?>
															<div class="text-secondary-light small mt-1"><?php echo e(Str::limit($review->title, 60)); ?></div>
														<?php endif; ?>
													</div>
												</div>
												<div class="d-flex align-items-center gap-2">
													<button type="button" class="btn btn-sm btn-outline-secondary move-review-btn" data-direction="-1">
														<iconify-icon icon="solar:alt-arrow-up-linear"></iconify-icon>
													</button>
													<button type="button" class="btn btn-sm btn-outline-secondary move-review-btn" data-direction="1">
														<iconify-icon icon="solar:alt-arrow-down-linear"></iconify-icon>
													</button>
													<button type="button" class="btn btn-sm btn-outline-danger remove-review-btn">
														<iconify-icon icon="solar:trash-bin-trash-line-duotone"></iconify-icon>
													</button>
												</div>
												<input type="hidden" name="home_reviews[]" value="<?php echo e($review->id); ?>">
											</li>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</ul>
								</div>

								<div class="col-12 d-flex gap-3 mt-3">
									<button type="submit" class="btn btn-primary radius-12 px-24">
										<iconify-icon icon="solar:floppy-disk-line-duotone" class="me-1"></iconify-icon>
										Save reviews
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
	#home-tabs .btn {
		width: 100%;
		justify-content: space-between;
	}

	#home-tabs .btn.active {
		background-color: #024550 !important;
		color: #ffffff !important;
		border-color: #024550 !important;
	}

	#home-tabs .btn:not(.active):hover {
		background-color: rgba(2, 69, 80, 0.08);
	}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const maxSlides = <?php echo e($maxSlides); ?>;
		const selectedList = document.getElementById('selectedProductsList');
		const selectedEmpty = document.getElementById('selectedProductsEmpty');
		const productSelect = document.getElementById('productSelect');
		const addBtn = document.getElementById('addProductBtn');
		const clearBtn = document.getElementById('clearProductsBtn');
		const selectedIds = new Set(<?php echo json_encode($selectedProducts->pluck('id'), 15, 512) ?>);

		const updateEmptyState = () => {
			if (selectedEmpty) {
				selectedEmpty.classList.toggle('d-none', selectedList && selectedList.children.length > 0);
			}
			updateOrderBadges();
		};

		const updateOrderBadges = () => {
			if (!selectedList) return;
			[...selectedList.children].forEach((item, index) => {
				const badge = item.querySelector('.order-index');
				if (badge) {
					badge.textContent = index + 1;
				}
			});
		};

		const createListItem = (product) => {
			const li = document.createElement('li');
			li.className = 'list-group-item d-flex align-items-center justify-content-between radius-12 border flex-wrap gap-3 product-slide-item';
			li.dataset.id = product.id;
			li.innerHTML = `
				<div class="d-flex align-items-center gap-3">
					<span class="badge bg-neutral-100 text-secondary-light border order-index"></span>
					<div>
						<div class="fw-semibold">${product.name}</div>
						<div class="text-secondary-light small">
							${product.is_active ? '' : '<span class="text-danger">Inactive</span>'}
						</div>
					</div>
				</div>
				<div class="d-flex align-items-center gap-2 ms-auto">
					<button type="button" class="btn btn-sm btn-outline-secondary move-product-btn" data-direction="-1">
						<iconify-icon icon="solar:alt-arrow-up-linear"></iconify-icon>
					</button>
					<button type="button" class="btn btn-sm btn-outline-secondary move-product-btn" data-direction="1">
						<iconify-icon icon="solar:alt-arrow-down-linear"></iconify-icon>
					</button>
					<button type="button" class="btn btn-sm btn-outline-danger remove-product-btn">
						<iconify-icon icon="solar:trash-bin-trash-line-duotone"></iconify-icon>
					</button>
				</div>
				<input type="hidden" name="product_slides[]" value="${product.id}">
			`;
			return li;
		};

		if (addBtn && productSelect && selectedList) {
			addBtn.addEventListener('click', () => {
				const productId = parseInt(productSelect.value, 10);
				if (!productId || selectedIds.has(productId)) {
					return;
				}

				if (selectedIds.size >= maxSlides) {
					alert(`You can only feature up to ${maxSlides} products.`);
					return;
				}

				const option = productSelect.options[productSelect.selectedIndex];
				const product = {
					id: productId,
					name: option.textContent.trim(),
					is_active: option.dataset.active === '1',
				};

				selectedIds.add(productId);
				selectedList.appendChild(createListItem(product));
				productSelect.value = '';
				updateEmptyState();
			});
		}

		if (clearBtn && selectedList) {
			clearBtn.addEventListener('click', () => {
				if (!selectedList.children.length) {
					return;
				}
				if (confirm('Remove all selected products?')) {
					selectedIds.clear();
					selectedList.innerHTML = '';
					updateEmptyState();
				}
			});
		}

		if (selectedList) {
			selectedList.addEventListener('click', (event) => {
				const button = event.target.closest('button');
				if (!button) {
					return;
				}

				const item = button.closest('.product-slide-item');
				if (!item) {
					return;
				}

				if (button.classList.contains('remove-product-btn')) {
					const id = parseInt(item.dataset.id, 10);
					selectedIds.delete(id);
					item.remove();
					updateEmptyState();
					return;
				}

				if (button.classList.contains('move-product-btn')) {
					const direction = parseInt(button.dataset.direction, 10);
					if (direction === -1 && item.previousElementSibling) {
						selectedList.insertBefore(item, item.previousElementSibling);
					} else if (direction === 1 && item.nextElementSibling) {
						selectedList.insertBefore(item.nextElementSibling, item);
					}
					updateOrderBadges();
				}
			});
		}

		updateEmptyState();
	});

	// Product Slider 2 functionality
	const maxProductSlider2 = <?php echo e($maxProductSlider2); ?>;
	const selectedProductSlider2List = document.getElementById('selectedProductSlider2List');
	const selectedProductSlider2Empty = document.getElementById('selectedProductSlider2Empty');
	const productSlider2Select = document.getElementById('productSlider2Select');
	const addProductSlider2Btn = document.getElementById('addProductSlider2Btn');
	const clearProductSlider2Btn = document.getElementById('clearProductSlider2Btn');
	const selectedProductSlider2Ids = new Set(<?php echo json_encode($productSlider2Selected->pluck('id'), 15, 512) ?>);

	const updateProductSlider2EmptyState = () => {
		if (selectedProductSlider2Empty) {
			selectedProductSlider2Empty.classList.toggle('d-none', selectedProductSlider2List && selectedProductSlider2List.children.length > 0);
		}
		updateProductSlider2OrderBadges();
	};

	const updateProductSlider2OrderBadges = () => {
		if (!selectedProductSlider2List) return;
		[...selectedProductSlider2List.children].forEach((item, index) => {
			const badge = item.querySelector('.order-index');
			if (badge) {
				badge.textContent = index + 1;
			}
		});
	};

	const createProductSlider2ListItem = (product) => {
		const li = document.createElement('li');
		li.className = 'list-group-item d-flex align-items-center justify-content-between radius-12 border flex-wrap gap-3 product-slider2-item';
		li.dataset.id = product.id;
		li.innerHTML = `
			<div class="d-flex align-items-center gap-3">
				<span class="badge bg-neutral-100 text-secondary-light border order-index"></span>
				<div>
					<div class="fw-semibold">${product.name}</div>
					<div class="text-secondary-light small">
						${product.is_active ? '' : '<span class="text-danger">Inactive</span>'}
					</div>
				</div>
			</div>
			<div class="d-flex align-items-center gap-2 ms-auto">
				<button type="button" class="btn btn-sm btn-outline-secondary move-product-slider2-btn" data-direction="-1">
					<iconify-icon icon="solar:alt-arrow-up-linear"></iconify-icon>
				</button>
				<button type="button" class="btn btn-sm btn-outline-secondary move-product-slider2-btn" data-direction="1">
					<iconify-icon icon="solar:alt-arrow-down-linear"></iconify-icon>
				</button>
				<button type="button" class="btn btn-sm btn-outline-danger remove-product-slider2-btn">
					<iconify-icon icon="solar:trash-bin-trash-line-duotone"></iconify-icon>
				</button>
			</div>
			<input type="hidden" name="product_slider2[]" value="${product.id}">
		`;
		return li;
	};

	if (addProductSlider2Btn && productSlider2Select && selectedProductSlider2List) {
		addProductSlider2Btn.addEventListener('click', () => {
			const productId = parseInt(productSlider2Select.value, 10);
			if (!productId || selectedProductSlider2Ids.has(productId)) {
				return;
			}

			if (selectedProductSlider2Ids.size >= maxProductSlider2) {
				alert(`You can only feature up to ${maxProductSlider2} products.`);
				return;
			}

			const option = productSlider2Select.options[productSlider2Select.selectedIndex];
			const product = {
				id: productId,
				name: option.textContent.trim(),
				is_active: option.dataset.active === '1',
			};

			selectedProductSlider2Ids.add(productId);
			selectedProductSlider2List.appendChild(createProductSlider2ListItem(product));
			productSlider2Select.value = '';
			updateProductSlider2EmptyState();
		});
	}

	if (clearProductSlider2Btn && selectedProductSlider2List) {
		clearProductSlider2Btn.addEventListener('click', () => {
			if (!selectedProductSlider2List.children.length) {
				return;
			}
			if (confirm('Remove all selected products?')) {
				selectedProductSlider2Ids.clear();
				selectedProductSlider2List.innerHTML = '';
				updateProductSlider2EmptyState();
			}
		});
	}

	if (selectedProductSlider2List) {
		selectedProductSlider2List.addEventListener('click', (event) => {
			const button = event.target.closest('button');
			if (!button) {
				return;
			}

			const item = button.closest('.product-slider2-item');
			if (!item) {
				return;
			}

			if (button.classList.contains('remove-product-slider2-btn')) {
				const id = parseInt(item.dataset.id, 10);
				selectedProductSlider2Ids.delete(id);
				item.remove();
				updateProductSlider2EmptyState();
				return;
			}

			if (button.classList.contains('move-product-slider2-btn')) {
				const direction = parseInt(button.dataset.direction, 10);
				if (direction === -1 && item.previousElementSibling) {
					selectedProductSlider2List.insertBefore(item, item.previousElementSibling);
				} else if (direction === 1 && item.nextElementSibling) {
					selectedProductSlider2List.insertBefore(item.nextElementSibling, item);
				}
				updateProductSlider2OrderBadges();
			}
		});
	}

	updateProductSlider2EmptyState();

	// Reviews functionality
	const maxHomeReviews = <?php echo e($maxHomeReviews); ?>;
	const selectedReviewsList = document.getElementById('selectedReviewsList');
	const selectedReviewsEmpty = document.getElementById('selectedReviewsEmpty');
	const reviewSelect = document.getElementById('reviewSelect');
	const addReviewBtn = document.getElementById('addReviewBtn');
	const clearReviewsBtn = document.getElementById('clearReviewsBtn');
	const selectedReviewIds = new Set(<?php echo json_encode($homeReviewsSelected->pluck('id'), 15, 512) ?>);

	const updateReviewsEmptyState = () => {
		if (selectedReviewsEmpty) {
			selectedReviewsEmpty.classList.toggle('d-none', selectedReviewsList && selectedReviewsList.children.length > 0);
		}
		updateReviewsOrderBadges();
	};

	const updateReviewsOrderBadges = () => {
		if (!selectedReviewsList) return;
		[...selectedReviewsList.children].forEach((item, index) => {
			const badge = item.querySelector('.order-index');
			if (badge) {
				badge.textContent = index + 1;
			}
		});
	};

	const createReviewListItem = (review) => {
		const li = document.createElement('li');
		li.className = 'list-group-item d-flex align-items-center justify-content-between radius-12 border flex-wrap gap-3 review-item';
		li.dataset.id = review.id;
		
		let starsHtml = '';
		for (let i = 1; i <= 5; i++) {
			const starClass = i <= review.rating ? 'text-warning' : 'text-secondary';
			starsHtml += `<iconify-icon icon="solar:star-bold" class="${starClass}"></iconify-icon>`;
		}
		
		const productName = review.product ? ` - ${review.product}` : '';
		const titleHtml = review.title ? `<div class="text-secondary-light small mt-1">${review.title}</div>` : '';
		
		li.innerHTML = `
			<div class="d-flex align-items-center gap-3 flex-grow-1">
				<span class="badge bg-neutral-100 text-secondary-light border order-index"></span>
				<div class="flex-grow-1">
					<div class="fw-semibold">${review.customer}</div>
					<div class="text-secondary-light small">
						${starsHtml}
						${productName ? `<span class="ms-2">${productName}</span>` : ''}
					</div>
					${titleHtml}
				</div>
			</div>
			<div class="d-flex align-items-center gap-2">
				<button type="button" class="btn btn-sm btn-outline-secondary move-review-btn" data-direction="-1">
					<iconify-icon icon="solar:alt-arrow-up-linear"></iconify-icon>
				</button>
				<button type="button" class="btn btn-sm btn-outline-secondary move-review-btn" data-direction="1">
					<iconify-icon icon="solar:alt-arrow-down-linear"></iconify-icon>
				</button>
				<button type="button" class="btn btn-sm btn-outline-danger remove-review-btn">
					<iconify-icon icon="solar:trash-bin-trash-line-duotone"></iconify-icon>
				</button>
			</div>
			<input type="hidden" name="home_reviews[]" value="${review.id}">
		`;
		return li;
	};

	if (addReviewBtn && reviewSelect && selectedReviewsList) {
		addReviewBtn.addEventListener('click', () => {
			const reviewId = parseInt(reviewSelect.value, 10);
			if (!reviewId || selectedReviewIds.has(reviewId)) {
				return;
			}

			if (selectedReviewIds.size >= maxHomeReviews) {
				alert(`You can only feature up to ${maxHomeReviews} reviews.`);
				return;
			}

			const option = reviewSelect.options[reviewSelect.selectedIndex];
			const review = {
				id: reviewId,
				customer: option.dataset.customer || 'Customer',
				rating: parseInt(option.dataset.rating || '5', 10),
				product: option.dataset.product || null,
				title: option.dataset.comment || null,
			};

			selectedReviewIds.add(reviewId);
			selectedReviewsList.appendChild(createReviewListItem(review));
			reviewSelect.value = '';
			updateReviewsEmptyState();
		});
	}

	if (clearReviewsBtn && selectedReviewsList) {
		clearReviewsBtn.addEventListener('click', () => {
			if (!selectedReviewsList.children.length) {
				return;
			}
			if (confirm('Remove all selected reviews?')) {
				selectedReviewIds.clear();
				selectedReviewsList.innerHTML = '';
				updateReviewsEmptyState();
			}
		});
	}

	if (selectedReviewsList) {
		selectedReviewsList.addEventListener('click', (event) => {
			const button = event.target.closest('button');
			if (!button) {
				return;
			}

			const item = button.closest('.review-item');
			if (!item) {
				return;
			}

			if (button.classList.contains('remove-review-btn')) {
				const id = parseInt(item.dataset.id, 10);
				selectedReviewIds.delete(id);
				item.remove();
				updateReviewsEmptyState();
				return;
			}

			if (button.classList.contains('move-review-btn')) {
				const direction = parseInt(button.dataset.direction, 10);
				if (direction === -1 && item.previousElementSibling) {
					selectedReviewsList.insertBefore(item, item.previousElementSibling);
				} else if (direction === 1 && item.nextElementSibling) {
					selectedReviewsList.insertBefore(item.nextElementSibling, item);
				}
				updateReviewsOrderBadges();
			}
		});
	}

	updateReviewsEmptyState();
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/cms/home/index.blade.php ENDPATH**/ ?>