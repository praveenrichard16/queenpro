<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
	<title><?php echo $__env->yieldContent('title', config('app.name')); ?></title>
	<link rel="icon" type="image/png" href="<?php echo e(asset('wowdash/assets/images/favicon.png')); ?>">
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
	<?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
	<?php echo $__env->yieldContent('content'); ?>

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
<?php /**PATH D:\xampp\htdocs\ecom123\resources\views/layouts/auth.blade.php ENDPATH**/ ?>