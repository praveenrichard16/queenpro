<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
	<title><?php echo $__env->yieldContent('title', 'My Account'); ?> &mdash; <?php echo e($appSettings['site_name'] ?? config('app.name')); ?></title>
	<?php
		$favicon = !empty($appSettings['site_favicon'])
			? asset('storage/'.$appSettings['site_favicon'])
			: asset('wowdash/assets/images/favicon.png');
		$siteLogoLight = !empty($appSettings['site_logo'])
			? asset('storage/'.$appSettings['site_logo'])
			: asset('wowdash/assets/images/logo.png');
		$siteLogoDark = !empty($appSettings['site_logo_dark'])
			? asset('storage/'.$appSettings['site_logo_dark'])
			: $siteLogoLight;
		if (!empty($appSettings['site_logo_dark'])) {
			$siteLogoLight = $siteLogoDark;
		}
		$siteLogoIcon = $siteLogoDark;
		$customer = auth()->user();
		$avatar = $customer?->avatar_url ?? asset('wowdash/assets/images/avatar/avatar-1.png');
	?>
	<link rel="icon" type="image/png" href="<?php echo e($favicon); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('wowdash/assets/css/remixicon.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('wowdash/assets/css/lib/bootstrap.min.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('wowdash/assets/css/lib/apexcharts.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('wowdash/assets/css/lib/dataTables.min.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('wowdash/assets/css/lib/editor-katex.min.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('wowdash/assets/css/lib/editor.atom-one-dark.min.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('wowdash/assets/css/lib/editor.quill.snow.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('wowdash/assets/css/lib/flatpickr.min.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('wowdash/assets/css/lib/full-calendar.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('wowdash/assets/css/lib/jquery-jvectormap-2.0.5.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('wowdash/assets/css/lib/magnific-popup.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('wowdash/assets/css/lib/slick.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('wowdash/assets/css/lib/prism.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('wowdash/assets/css/lib/file-upload.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('wowdash/assets/css/lib/audioplayer.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(asset('wowdash/assets/css/style.css')); ?>">
	<style>
		.sidebar-logo img {
			width: 240px;
			height: 140px;
			object-fit: contain;
		}
		.sidebar-menu > li.active > a,
		.sidebar-menu > li > a:hover {
			background-color: #0b7a7d !important;
			color: #ffffff !important;
		}
		.sidebar-menu > li > a:hover .menu-icon,
		.sidebar-menu > li.active > a .menu-icon {
			color: #ffffff !important;
		}
		.btn-primary {
			background-color: #0b7a7d !important;
			border-color: #0b7a7d !important;
		}
		.btn-primary:hover,
		.btn-primary:focus {
			background-color: #0a686a !important;
			border-color: #0a686a !important;
		}
	</style>
	<?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
	<div class="preloader" id="preloader">
		<div class="loader-book">
			<div class="loader-book-page"></div>
			<div class="loader-book-page"></div>
			<div class="loader-book-page"></div>
		</div>
	</div>

	<aside class="sidebar">
		<button type="button" class="sidebar-close-btn">
			<iconify-icon icon="radix-icons:cross-2"></iconify-icon>
		</button>
		<div class="mb-24">
			<a href="<?php echo e(route('customer.dashboard')); ?>" class="sidebar-logo">
				<img src="<?php echo e($siteLogoLight); ?>" alt="logo" class="light-logo">
				<img src="<?php echo e($siteLogoDark); ?>" alt="logo" class="dark-logo">
				<img src="<?php echo e($siteLogoIcon); ?>" alt="logo" class="logo-icon">
			</a>
		</div>
		<div class="sidebar-menu-area">
			<?php
				$accountMenuActive = request()->routeIs('customer.dashboard');
				$orderMenuActive = request()->routeIs('customer.orders.*');
				$addressMenuActive = request()->routeIs('customer.addresses.*');
				$ticketsMenuActive = request()->routeIs('customer.support.*');
				$profileMenuActive = request()->routeIs('customer.profile.*');
				$notificationMenuActive = request()->routeIs('notifications.*');
			?>
			<ul class="sidebar-menu" id="customer-sidebar-menu">
				<li class="<?php echo e($accountMenuActive ? 'active' : ''); ?>">
					<a href="<?php echo e(route('customer.dashboard')); ?>">
						<iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
						<span>Overview</span>
					</a>
				</li>
				<li class="<?php echo e($orderMenuActive ? 'active' : ''); ?>">
					<a href="<?php echo e(route('customer.orders.index')); ?>">
						<iconify-icon icon="solar:bag-check-outline" class="menu-icon"></iconify-icon>
						<span>Orders &amp; Tracking</span>
					</a>
				</li>
				<li class="<?php echo e($addressMenuActive ? 'active' : ''); ?>">
					<a href="<?php echo e(route('customer.addresses.index')); ?>">
						<iconify-icon icon="solar:map-point-wave-outline" class="menu-icon"></iconify-icon>
						<span>Addresses</span>
					</a>
				</li>
				<li class="<?php echo e($ticketsMenuActive ? 'active' : ''); ?>">
					<a href="<?php echo e(route('customer.support.tickets.index')); ?>">
						<iconify-icon icon="solar:lifebuoy-outline" class="menu-icon"></iconify-icon>
						<span>Support Tickets</span>
					</a>
				</li>
				<li class="<?php echo e($notificationMenuActive ? 'active' : ''); ?>">
					<a href="<?php echo e(route('notifications.index')); ?>">
						<iconify-icon icon="solar:bell-outline" class="menu-icon"></iconify-icon>
						<span>Notifications</span>
						<?php
							$unreadCount = auth()->user()->unreadNotifications()->count();
						?>
						<?php if($unreadCount > 0): ?>
							<span class="badge bg-danger ms-2"><?php echo e($unreadCount > 99 ? '99+' : $unreadCount); ?></span>
						<?php endif; ?>
					</a>
				</li>
				<li class="<?php echo e($profileMenuActive ? 'active' : ''); ?>">
					<a href="<?php echo e(route('customer.profile.edit')); ?>">
						<iconify-icon icon="solar:user-circle-outline" class="menu-icon"></iconify-icon>
						<span>Profile &amp; Settings</span>
					</a>
				</li>
			</ul>
		</div>
		<div class="sidebar-profile">
			<div class="sidebar-profile-top">
				<div class="profile-thumb">
					<img src="<?php echo e($avatar); ?>" alt="profile">
				</div>
				<h6 class="name mb-0"><?php echo e($customer?->name ?? 'Account'); ?></h6>
				<span class="designation">Customer</span>
			</div>
			<a href="<?php echo e(route('logout')); ?>" class="sidebar-logout" onclick="event.preventDefault(); document.getElementById('customer-logout-form').submit();">
				<iconify-icon icon="solar:logout-2-outline"></iconify-icon>
				<span>Logout</span>
			</a>
			<form id="customer-logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
				<?php echo csrf_field(); ?>
			</form>
		</div>
	</aside>

	<main class="dashboard-main">
		<div class="navbar-header">
			<div class="row align-items-center justify-content-between g-0">
				<div class="col-auto">
					<div class="d-flex flex-wrap align-items-center gap-3">
						<button type="button" class="sidebar-toggle">
							<iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
							<iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
						</button>
						<button type="button" class="sidebar-mobile-toggle">
							<iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
						</button>
						<form class="navbar-search d-none d-md-flex" action="<?php echo e(route('customer.orders.index')); ?>">
							<input type="text" name="search" placeholder="Search orders or tickets">
							<iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
						</form>
					</div>
				</div>
				<div class="col-auto">
					<div class="d-flex flex-wrap align-items-center gap-3">
						<button class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center" type="button" data-theme-toggle></button>
						<div class="dropdown">
							<button class="btn btn-link text-decoration-none p-0 dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
								<?php if($customer): ?>
									<?php if (isset($component)) { $__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.avatar','data' => ['user' => $customer,'size' => 'md','class' => 'w-40-px h-40-px']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['user' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($customer),'size' => 'md','class' => 'w-40-px h-40-px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b)): ?>
<?php $attributes = $__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b; ?>
<?php unset($__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b)): ?>
<?php $component = $__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b; ?>
<?php unset($__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b); ?>
<?php endif; ?>
								<?php else: ?>
									<img src="<?php echo e(asset('wowdash/assets/images/avatar/avatar-1.png')); ?>" alt="avatar" class="w-40-px h-40-px rounded-circle object-fit-cover">
								<?php endif; ?>
								<span class="text-sm fw-semibold text-secondary-light d-none d-md-inline"><?php echo e($customer?->name); ?></span>
							</button>
							<div class="dropdown-menu dropdown-menu-end profile-dropdown shadow border-0 radius-12 p-0 overflow-hidden">
								<div class="p-24 bg-neutral-100">
									<div class="d-flex align-items-center gap-3">
										<?php if($customer): ?>
											<?php if (isset($component)) { $__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.avatar','data' => ['user' => $customer,'size' => 'lg','class' => 'w-56-px h-56-px']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['user' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($customer),'size' => 'lg','class' => 'w-56-px h-56-px']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b)): ?>
<?php $attributes = $__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b; ?>
<?php unset($__attributesOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b)): ?>
<?php $component = $__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b; ?>
<?php unset($__componentOriginal8ca5b43b8fff8bb34ab2ba4eb4bdd67b); ?>
<?php endif; ?>
										<?php else: ?>
											<img src="<?php echo e(asset('wowdash/assets/images/avatar/avatar-1.png')); ?>" alt="avatar" class="w-56-px h-56-px rounded-circle object-fit-cover">
										<?php endif; ?>
										<div>
											<h6 class="mb-0 text-md fw-semibold"><?php echo e($customer?->name); ?></h6>
											<span class="text-sm text-secondary-light"><?php echo e($customer?->email); ?></span>
										</div>
									</div>
								</div>
								<div class="list-group list-group-flush">
									<a href="<?php echo e(route('customer.profile.edit')); ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-2">
										<iconify-icon icon="solar:user-circle-outline" class="text-lg"></iconify-icon>
										Account Settings
									</a>
									<a href="<?php echo e(route('customer.support.tickets.index')); ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-2">
										<iconify-icon icon="solar:lifebuoy-outline" class="text-lg"></iconify-icon>
										Support Tickets
									</a>
								</div>
								<div class="border-top">
									<a class="dropdown-item text-danger py-12 d-flex align-items-center gap-2" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('customer-logout-form').submit();">
										<iconify-icon icon="solar:logout-2-outline" class="text-lg"></iconify-icon>
										Logout
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php if(session('success')): ?>
			<div class="container-fluid px-24 mt-24">
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<?php echo e(session('success')); ?>

					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			</div>
		<?php endif; ?>
		<?php if(session('error')): ?>
			<div class="container-fluid px-24 mt-24">
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<?php echo e(session('error')); ?>

					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
			</div>
		<?php endif; ?>

		<?php echo $__env->yieldContent('content'); ?>

		<footer class="footer mt-32">
			<div class="container-fluid">
				<div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
					<p class="mb-0">&copy; <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. All rights reserved.</p>
					<div class="d-flex gap-3">
						<a href="<?php echo e(route('privacy')); ?>" class="text-secondary-light">Privacy Policy</a>
						<a href="<?php echo e(route('terms')); ?>" class="text-secondary-light">Terms</a>
						<a href="<?php echo e(route('contact')); ?>" class="text-secondary-light">Help</a>
					</div>
				</div>
			</div>
		</footer>
	</main>

	<script src="<?php echo e(asset('wowdash/assets/js/lib/jquery-3.7.1.min.js')); ?>"></script>
	<script src="<?php echo e(asset('wowdash/assets/js/lib/bootstrap.bundle.min.js')); ?>"></script>
	<script src="<?php echo e(asset('wowdash/assets/js/lib/apexcharts.min.js')); ?>"></script>
	<script src="<?php echo e(asset('wowdash/assets/js/lib/dataTables.min.js')); ?>"></script>
	<script src="<?php echo e(asset('wowdash/assets/js/lib/iconify-icon.min.js')); ?>"></script>
	<script src="<?php echo e(asset('wowdash/assets/js/lib/jquery-ui.min.js')); ?>"></script>
	<script src="<?php echo e(asset('wowdash/assets/js/lib/jquery-jvectormap-2.0.5.min.js')); ?>"></script>
	<script src="<?php echo e(asset('wowdash/assets/js/lib/jquery-jvectormap-world-mill-en.js')); ?>"></script>
	<script src="<?php echo e(asset('wowdash/assets/js/lib/magnifc-popup.min.js')); ?>"></script>
	<script src="<?php echo e(asset('wowdash/assets/js/lib/slick.min.js')); ?>"></script>
	<script src="<?php echo e(asset('wowdash/assets/js/lib/prism.js')); ?>"></script>
	<script src="<?php echo e(asset('wowdash/assets/js/lib/file-upload.js')); ?>"></script>
	<script src="<?php echo e(asset('wowdash/assets/js/lib/audioplayer.js')); ?>"></script>
	<script src="<?php echo e(asset('wowdash/assets/js/app.js')); ?>"></script>
	<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>

<?php /**PATH D:\xampp\htdocs\ecom123\resources\views/layouts/customer.blade.php ENDPATH**/ ?>