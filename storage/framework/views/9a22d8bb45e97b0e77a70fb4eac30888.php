

<?php $__env->startSection('title', 'Blog Posts'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Blog Posts</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Blog Posts</li>
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
				<div class="col-xl-4 col-lg-6">
					<label class="form-label text-secondary-light">Search</label>
					<div class="icon-field">
						<span class="icon top-50 translate-middle-y">
							<iconify-icon icon="mage:search"></iconify-icon>
						</span>
						<input type="text" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Post title or excerpt" name="search" value="<?php echo e(request('search')); ?>">
					</div>
				</div>
				<div class="col-xl-3 col-lg-6">
					<label class="form-label text-secondary-light">Category</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="category">
						<option value="">All categories</option>
						<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($id); ?>" <?php if(request('category') == $id): echo 'selected'; endif; ?>><?php echo e($name); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
				</div>
				<div class="col-xl-3 col-lg-6">
					<label class="form-label text-secondary-light">Status</label>
					<select class="form-select h-56-px bg-neutral-50 radius-12" name="status">
						<option value="">Any status</option>
						<option value="published" <?php if(request('status') === 'published'): echo 'selected'; endif; ?>>Published</option>
						<option value="draft" <?php if(request('status') === 'draft'): echo 'selected'; endif; ?>>Draft</option>
					</select>
				</div>
				<div class="col-xl-2 col-lg-6 d-flex gap-2">
					<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Apply</button>
					<a href="<?php echo e(route('admin.blog.posts.index')); ?>" class="btn btn-outline-secondary h-56-px radius-12">
						<iconify-icon icon="mi:refresh"></iconify-icon>
					</a>
				</div>
			</form>

			<div class="d-flex flex-wrap gap-2 mt-24 justify-content-between">
				<div class="d-flex flex-wrap gap-3 align-items-center">
						<span class="badge bg-neutral-100 text-secondary-light px-16 py-8 radius-12 fw-medium">
							Total Posts: <?php echo e($posts->total()); ?>

						</span>
				</div>
				<a href="<?php echo e(route('admin.blog.posts.create')); ?>" class="btn btn-warning radius-12 text-white d-inline-flex align-items-center gap-2">
					<iconify-icon icon="solar:add-circle-linear"></iconify-icon>
					Write New Post
				</a>
			</div>
		</div>
	</div>

	<div class="card basic-data-table border-0">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table bordered-table mb-0 align-middle">
					<thead>
						<tr>
							<th scope="col">Post</th>
							<th scope="col">Category</th>
							<th scope="col">Author</th>
							<th scope="col">Status</th>
							<th scope="col" class="text-end">Updated</th>
							<th scope="col" class="text-end">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<tr>
								<td>
									<h6 class="text-md fw-semibold mb-4"><?php echo e($post->title); ?></h6>
									<p class="text-sm text-secondary-light mb-0"><?php echo e(\Illuminate\Support\Str::limit($post->excerpt, 120) ?: '—'); ?></p>
								</td>
								<td><span class="text-sm text-secondary-light fw-semibold"><?php echo e($post->category->name ?? 'Uncategorised'); ?></span></td>
								<td><span class="text-sm text-secondary-light"><?php echo e($post->author?->name ?? 'System'); ?></span></td>
								<td>
									<div class="d-flex flex-column gap-2">
										<span class="px-20 py-6 rounded-pill fw-semibold text-sm <?php echo e($post->is_published ? 'bg-success-focus text-success-main' : 'bg-neutral-200 text-neutral-600'); ?>">
											<?php echo e($post->is_published ? 'Published' : 'Draft'); ?>

										</span>
										<?php if($post->is_featured): ?>
											<span class="badge bg-warning text-white radius-12 align-self-start px-12 py-6">Featured</span>
										<?php endif; ?>
									</div>
								</td>
								<td class="text-end text-sm text-secondary-light">
									<?php if($post->published_at): ?>
										Published <?php echo e($post->published_at->diffForHumans()); ?>

									<?php else: ?>
										Updated <?php echo e($post->updated_at->diffForHumans()); ?>

									<?php endif; ?>
								</td>
								<td class="text-end">
									<div class="d-inline-flex gap-2">
										<a href="<?php echo e(route('admin.blog.posts.edit', $post)); ?>" class="w-36-px h-36-px bg-primary-focus text-primary-main rounded-circle d-inline-flex align-items-center justify-content-center" title="Edit">
											<iconify-icon icon="lucide:edit"></iconify-icon>
										</a>
										<form action="<?php echo e(route('admin.blog.posts.destroy', $post)); ?>" method="POST" onsubmit="return confirm('Move this post to trash?')">
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
								<td colspan="6" class="text-center py-80">
									<img src="<?php echo e(asset('wowdash/assets/images/notification/empty-message-icon1.png')); ?>" alt="No data" class="mb-16" style="max-width:120px;">
									<h6 class="fw-semibold mb-8">No posts yet</h6>
									<p class="text-secondary-light mb-0">Start publishing to engage shoppers with trend stories and buying guides.</p>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<?php if($posts->hasPages()): ?>
				<div class="card-footer border-0 bg-transparent py-16 px-24">
					<?php echo e($posts->withQueryString()->links()); ?>

				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/blog/posts/index.blade.php ENDPATH**/ ?>