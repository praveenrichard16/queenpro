

<?php $__env->startSection('title', $post->exists ? 'Edit Blog Post' : 'Create Blog Post'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0"><?php echo e($post->exists ? 'Edit Blog Post' : 'Create Blog Post'); ?></h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.blog.posts.index')); ?>" class="hover-text-primary">Blog Posts</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light"><?php echo e($post->exists ? 'Edit' : 'Create'); ?></li>
		</ul>
	</div>

	<?php if($errors->any()): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			Please review the highlighted fields and try again.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<div class="card border-0">
		<div class="card-body p-24">
			<form method="POST" enctype="multipart/form-data" action="<?php echo e($post->exists ? route('admin.blog.posts.update', $post) : route('admin.blog.posts.store')); ?>" class="row g-4" id="blog-post-form">
				<?php echo csrf_field(); ?>
				<?php if($post->exists): ?>
					<?php echo method_field('PUT'); ?>
				<?php endif; ?>

				<div class="col-lg-8">
					<label class="form-label text-secondary-light">Title</label>
					<input type="text" name="title" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('title', $post->title)); ?>" required>
					<?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>
				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Category</label>
					<select name="blog_category_id" class="form-select bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['blog_category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
						<option value="">Select category</option>
						<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($id); ?>" <?php if(old('blog_category_id', $post->blog_category_id) == $id): echo 'selected'; endif; ?>><?php echo e($name); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
					<?php $__errorArgs = ['blog_category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>

				<div class="col-12">
					<label class="form-label text-secondary-light">Excerpt</label>
					<textarea name="excerpt" rows="3" class="form-control bg-neutral-50 radius-12 <?php $__errorArgs = ['excerpt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Short summary shown on listings"><?php echo e(old('excerpt', $post->excerpt)); ?></textarea>
					<?php $__errorArgs = ['excerpt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>

				<div class="col-12 mb-4">
					<label class="form-label text-secondary-light">Content</label>
					<div class="quill-editor bg-neutral-50 radius-12 <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border border-danger <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="blog-post-editor" style="min-height:260px;">
						<?php echo old('content', $post->content); ?>

					</div>
					<input type="hidden" name="content" id="blog-post-content" value="<?php echo e(old('content', $post->content)); ?>">
					<?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Featured Image</label>
					<input type="file" name="featured_image" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['featured_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
					<?php $__errorArgs = ['featured_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					<div class="form-text mt-1">Recommended: 1200×675px JPG/PNG (max 4MB).</div>
				</div>
				<?php if($post->featured_image_path): ?>
					<div class="col-lg-6">
						<label class="form-label text-secondary-light d-block">Current Featured Image</label>
						<div class="p-16 border radius-12 bg-neutral-50 d-inline-flex">
							<img src="<?php echo e(asset('storage/'.$post->featured_image_path)); ?>" alt="<?php echo e($post->featured_image_alt ?? $post->title); ?>" class="img-fluid rounded" style="max-height:140px;">
						</div>
					</div>
				<?php endif; ?>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Featured Image Alt Text</label>
					<input type="text" name="featured_image_alt" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['featured_image_alt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('featured_image_alt', $post->featured_image_alt)); ?>" placeholder="Describe the image for accessibility">
					<?php $__errorArgs = ['featured_image_alt'];
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
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Meta Title</label>
					<input type="text" name="meta_title" class="form-control bg-neutral-50 radius-12 <?php $__errorArgs = ['meta_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('meta_title', $post->meta_title)); ?>" placeholder="SEO meta title">
					<?php $__errorArgs = ['meta_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>
				<div class="col-12">
					<label class="form-label text-secondary-light">Meta Description</label>
					<textarea name="meta_description" rows="3" class="form-control bg-neutral-50 radius-12 <?php $__errorArgs = ['meta_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="SEO meta description"><?php echo e(old('meta_description', $post->meta_description)); ?></textarea>
					<?php $__errorArgs = ['meta_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>
				<div class="col-12">
					<label class="form-label text-secondary-light">Meta Keywords</label>
					<input type="text" name="meta_keywords" class="form-control bg-neutral-50 radius-12 <?php $__errorArgs = ['meta_keywords'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('meta_keywords', $post->meta_keywords)); ?>" placeholder="Comma-separated keywords">
					<?php $__errorArgs = ['meta_keywords'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>

				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Tags</label>
					<select name="tag_ids[]" class="form-select bg-neutral-50 radius-12 <?php $__errorArgs = ['tag_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" multiple size="6">
						<?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($id); ?>" <?php if(collect(old('tag_ids', $selectedTags))->contains($id)): echo 'selected'; endif; ?>><?php echo e($name); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
					<?php $__errorArgs = ['tag_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					<?php $__errorArgs = ['tag_ids.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					<div class="form-text mt-1">Hold Ctrl (Cmd on Mac) to select multiple tags.</div>
				</div>

				<div class="col-lg-4">
					<label class="form-label text-secondary-light">Publish At</label>
					<input type="datetime-local" name="published_at" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['published_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('published_at', optional($post->published_at)->format('Y-m-d\TH:i'))); ?>">
					<?php $__errorArgs = ['published_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
					<div class="form-text mt-1">Leave blank to publish immediately when toggled live.</div>
				</div>
				<div class="col-lg-4">
					<label class="form-label text-secondary-light d-block">Post Status</label>
					<div class="form-check form-switch mb-12">
						<input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" <?php if(old('is_published', $post->is_published)): echo 'checked'; endif; ?>>
						<label class="form-check-label" for="is_published">Published</label>
					</div>
					<div class="form-check form-switch">
						<input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1" <?php if(old('is_featured', $post->is_featured)): echo 'checked'; endif; ?>>
						<label class="form-check-label" for="is_featured">Feature on storefront</label>
					</div>
				</div>

				<div class="col-12 d-flex gap-2 mt-8">
					<button class="btn btn-primary radius-12 px-24"><?php echo e($post->exists ? 'Update Post' : 'Publish Post'); ?></button>
					<a href="<?php echo e(route('admin.blog.posts.index')); ?>" class="btn btn-outline-secondary radius-12 px-24">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
	<script src="<?php echo e(asset('wowdash/assets/js/editor.quill.js')); ?>"></script>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const editorElement = document.getElementById('blog-post-editor');
			const hiddenInput = document.getElementById('blog-post-content');

			if (!editorElement || !hiddenInput || typeof Quill === 'undefined') {
				return;
			}

			const quill = new Quill(editorElement, {
				theme: 'snow',
				placeholder: 'Write your post content here...',
				modules: {
					toolbar: [
						[{ header: [1, 2, 3, false] }],
						['bold', 'italic', 'underline', 'strike'],
						[{ color: [] }, { background: [] }],
						[{ list: 'ordered' }, { list: 'bullet' }],
						['blockquote', 'link', 'image', 'video'],
						['clean']
					]
				}
			});

			quill.on('text-change', function () {
				hiddenInput.value = quill.root.innerHTML;
			});

			const initialContent = hiddenInput.value;
			if (initialContent) {
				quill.root.innerHTML = initialContent;
			}

			document.getElementById('blog-post-form').addEventListener('submit', function () {
				hiddenInput.value = quill.root.innerHTML;
			});
		});
	</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/blog/posts/form.blade.php ENDPATH**/ ?>