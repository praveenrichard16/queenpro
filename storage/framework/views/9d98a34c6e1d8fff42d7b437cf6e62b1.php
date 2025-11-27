

<?php $__env->startSection('title', 'Create Account'); ?>

<?php $__env->startSection('content'); ?>
<section class="auth-section py-80">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-6">
				<div class="card border-0 shadow-lg radius-24">
					<div class="card-body p-40">
						<div class="text-center mb-32">
							<h2 class="heading mb-12">Create your account</h2>
							<p class="text-muted">Join <?php echo e($appSettings['site_name'] ?? config('app.name')); ?> for fast checkout and order tracking.</p>
						</div>
						<form method="POST" action="<?php echo e(route('register.perform')); ?>" class="d-grid gap-3">
							<?php echo csrf_field(); ?>
							<div>
								<label class="form-label text-secondary-light">Full name</label>
								<input type="text" name="name" class="form-control radius-16 bg-neutral-50 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('name')); ?>" required autofocus>
								<?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
							</div>
							<div>
								<label class="form-label text-secondary-light">Email address</label>
								<input type="email" name="email" class="form-control radius-16 bg-neutral-50 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('email')); ?>" required>
								<?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
							</div>
							<div class="row g-3">
								<div class="col-md-6">
									<label class="form-label text-secondary-light">Password</label>
									<input type="password" name="password" class="form-control radius-16 bg-neutral-50 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
									<?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>
								<div class="col-md-6">
									<label class="form-label text-secondary-light">Confirm password</label>
									<input type="password" name="password_confirmation" class="form-control radius-16 bg-neutral-50" required>
								</div>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="checkbox" value="1" id="marketing_opt_in" name="marketing_opt_in" <?php echo e(old('marketing_opt_in', true) ? 'checked' : ''); ?>>
								<label class="form-check-label text-secondary-light" for="marketing_opt_in">
									Send me product updates and exclusive offers.
								</label>
							</div>
							<button type="submit" class="shop-btn radius-16 py-3">Create account</button>
						</form>
						<div class="text-center mt-24 text-secondary-light">
							Already have an account?
							<a href="<?php echo e(route('login')); ?>" class="text-primary fw-semibold">Sign in</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/auth/register.blade.php ENDPATH**/ ?>