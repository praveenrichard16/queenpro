

<?php $__env->startSection('title', 'SEO Tools'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">SEO Tools</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">SEO Tools</li>
		</ul>
	</div>

	<?php if(session('success')): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?php echo e(session('success')); ?>

			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<?php if($errors->any()): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			Please fix the highlighted errors and try again.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<div class="row g-4">
		<div class="col-lg-6">
			<div class="card border-0 h-100">
				<div class="card-body p-24 d-flex flex-column">
					<div class="d-flex align-items-start justify-content-between gap-3 mb-16">
						<div>
							<h6 class="fw-semibold mb-4">robots.txt Manager</h6>
							<p class="text-secondary-light mb-0">Control crawler access for your storefront and admin panel.</p>
						</div>
						<?php if($lastUpdatedRobotsAt): ?>
							<span class="badge bg-neutral-200 text-secondary-light fw-medium">
								Updated <?php echo e($lastUpdatedRobotsAt->diffForHumans()); ?>

							</span>
						<?php endif; ?>
					</div>

					<form action="<?php echo e(route('admin.settings.seo.robots.update')); ?>" method="POST" class="d-flex flex-column flex-grow-1 gap-3">
						<?php echo csrf_field(); ?>
						<div>
							<label for="robots_content" class="form-label text-secondary-light">robots.txt Content</label>
							<textarea name="robots_content" id="robots_content" rows="12" class="form-control bg-neutral-50 radius-12 <?php $__errorArgs = ['robots_content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('robots_content', $robotsContent)); ?></textarea>
							<?php $__errorArgs = ['robots_content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
							<div class="form-text mt-2">
								Your public robots.txt is served from <a href="<?php echo e($robotsUrl); ?>" target="_blank" rel="noopener"><?php echo e($robotsUrl); ?></a>.
							</div>
						</div>

						<div class="d-flex flex-wrap gap-3 mt-auto">
							<button type="submit" class="btn btn-primary radius-12 px-24">Save robots.txt</button>

							<a href="<?php echo e(route('admin.settings.seo.robots.download')); ?>" class="btn btn-outline-secondary radius-12 px-24 <?php echo e($robotsExists ? '' : 'disabled'); ?>" <?php if(!$robotsExists): ?> tabindex="-1" aria-disabled="true" <?php endif; ?>>
								Download
							</a>
							<button type="submit" class="btn btn-warning radius-12 px-24 text-white" form="robots-reset-form" data-confirm-reset>
								Restore Default
							</button>
						</div>
					</form>
					<form action="<?php echo e(route('admin.settings.seo.robots.reset')); ?>" method="POST" id="robots-reset-form" class="d-none">
						<?php echo csrf_field(); ?>
					</form>
				</div>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="card border-0 h-100">
				<div class="card-body p-24 d-flex flex-column">
					<div class="d-flex align-items-start justify-content-between gap-3 mb-16">
						<div>
							<h6 class="fw-semibold mb-4">Sitemap Generator</h6>
							<p class="text-secondary-light mb-0">Generate a fresh sitemap.xml with the latest catalog, blog, and page URLs.</p>
						</div>
						<?php if($lastGeneratedAt): ?>
							<span class="badge bg-neutral-200 text-secondary-light fw-medium">
								Generated <?php echo e($lastGeneratedAt->diffForHumans()); ?>

							</span>
						<?php endif; ?>
					</div>

					<?php if($sitemapExists): ?>
						<div class="alert alert-neutral-100 border mb-16">
							<div class="d-flex flex-wrap justify-content-between gap-2 align-items-center">
								<div>
									<strong class="d-block text-secondary">Current sitemap</strong>
									<span class="text-secondary-light"><?php echo e($sitemapUrl); ?></span>
								</div>
								<div class="text-sm text-secondary-light">
									Contains <span class="fw-semibold"><?php echo e($sitemapUrlCount); ?></span> URLs
								</div>
							</div>
						</div>
					<?php endif; ?>

					<form action="<?php echo e(route('admin.settings.seo.sitemap.generate')); ?>" method="POST" class="d-flex flex-column gap-3">
						<?php echo csrf_field(); ?>
						<?php
							$selectedSections = collect(old('sections', $sitemapSections));
						?>
						<div class="row g-3">
							<div class="col-sm-6">
								<div class="form-check border radius-12 py-12 px-16 h-100">
									<input class="form-check-input" type="checkbox" value="home" id="section-home" name="sections[]" <?php echo e($selectedSections->isEmpty() || $selectedSections->contains('home') ? 'checked' : ''); ?>>
									<label class="form-check-label fw-semibold text-secondary-light" for="section-home">
										Homepage
										<span class="d-block text-sm fw-normal mt-2">Primary landing page.</span>
									</label>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-check border radius-12 py-12 px-16 h-100">
									<input class="form-check-input" type="checkbox" value="static" id="section-static" name="sections[]" <?php echo e($selectedSections->isEmpty() || $selectedSections->contains('static') ? 'checked' : ''); ?>>
									<label class="form-check-label fw-semibold text-secondary-light" for="section-static">
										Static Pages
										<span class="d-block text-sm fw-normal mt-2">About, contact, policies.</span>
									</label>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-check border radius-12 py-12 px-16 h-100">
									<input class="form-check-input" type="checkbox" value="products" id="section-products" name="sections[]" <?php echo e($selectedSections->isEmpty() || $selectedSections->contains('products') ? 'checked' : ''); ?>>
									<label class="form-check-label fw-semibold text-secondary-light" for="section-products">
										Product Pages
										<span class="d-block text-sm fw-normal mt-2">All active products.</span>
									</label>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-check border radius-12 py-12 px-16 h-100">
									<input class="form-check-input" type="checkbox" value="categories" id="section-categories" name="sections[]" <?php echo e($selectedSections->isEmpty() || $selectedSections->contains('categories') ? 'checked' : ''); ?>>
									<label class="form-check-label fw-semibold text-secondary-light" for="section-categories">
										Product Categories
										<span class="d-block text-sm fw-normal mt-2">Published categories.</span>
									</label>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-check border radius-12 py-12 px-16 h-100">
									<input class="form-check-input" type="checkbox" value="blog_posts" id="section-blog-posts" name="sections[]" <?php echo e($selectedSections->isEmpty() || $selectedSections->contains('blog_posts') ? 'checked' : ''); ?>>
									<label class="form-check-label fw-semibold text-secondary-light" for="section-blog-posts">
										Blog Posts
										<span class="d-block text-sm fw-normal mt-2">Published articles.</span>
									</label>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-check border radius-12 py-12 px-16 h-100">
									<input class="form-check-input" type="checkbox" value="blog_categories" id="section-blog-categories" name="sections[]" <?php echo e($selectedSections->isEmpty() || $selectedSections->contains('blog_categories') ? 'checked' : ''); ?>>
									<label class="form-check-label fw-semibold text-secondary-light" for="section-blog-categories">
										Blog Categories
										<span class="d-block text-sm fw-normal mt-2">Topic hubs.</span>
									</label>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-check border radius-12 py-12 px-16 h-100">
									<input class="form-check-input" type="checkbox" value="tags" id="section-tags" name="sections[]" <?php echo e($selectedSections->isEmpty() || $selectedSections->contains('tags') ? 'checked' : ''); ?>>
									<label class="form-check-label fw-semibold text-secondary-light" for="section-tags">
										Product Tags
										<span class="d-block text-sm fw-normal mt-2">Curated tag landing pages.</span>
									</label>
								</div>
							</div>
						</div>

						<div class="d-flex flex-wrap gap-3 mt-auto">
							<button type="submit" class="btn btn-primary radius-12 px-24">Generate Sitemap</button>
							<?php if($sitemapExists): ?>
								<a href="<?php echo e(route('admin.settings.seo.sitemap.download')); ?>" class="btn btn-outline-secondary radius-12 px-24">
									Download
								</a>
							<?php endif; ?>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const resetButtons = document.querySelectorAll('[data-confirm-reset]');

		resetButtons.forEach((button) => {
			button.addEventListener('click', function (event) {
				if (!confirm('Restore the default robots.txt template? This will overwrite your current rules.')) {
					event.preventDefault();
					event.stopPropagation();
				}
			});
		});
	});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/settings/seo.blade.php ENDPATH**/ ?>