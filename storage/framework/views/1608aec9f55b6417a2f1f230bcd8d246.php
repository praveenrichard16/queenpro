<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <?php
        $siteName = $appSettings['site_name'] ?? config('app.name', 'Laravel');
        $siteTagline = $appSettings['site_tagline'] ?? null;
        $favicon = !empty($appSettings['site_favicon'])
            ? asset('storage/'.$appSettings['site_favicon'])
            : asset('shopus/assets/images/homepage-one/icon.png');
    ?>
    <title><?php echo $__env->yieldContent('title', $siteTagline ?? 'Shop'); ?> &mdash; <?php echo e($siteName); ?></title>
    <link rel="icon" href="<?php echo e($favicon); ?>">

    <?php
        $googleAnalyticsId = $appSettings['google_analytics_id'] ?? null;
        $googleTagManagerId = $appSettings['google_tag_manager_id'] ?? null;
        $googleSearchConsole = $appSettings['google_search_console_verification'] ?? null;
    ?>

    <?php if($googleTagManagerId): ?>
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','<?php echo e($googleTagManagerId); ?>');
        </script>
    <?php endif; ?>

    <?php if($googleAnalyticsId): ?>
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e($googleAnalyticsId); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php echo e($googleAnalyticsId); ?>');
        </script>
    <?php endif; ?>

    <?php if($googleSearchConsole): ?>
        <?php if(str_contains($googleSearchConsole, '<meta')): ?>
            <?php echo $googleSearchConsole; ?>

        <?php else: ?>
            <meta name="google-site-verification" content="<?php echo e($googleSearchConsole); ?>">
        <?php endif; ?>
    <?php endif; ?>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="<?php echo e(asset('shopus/css/swiper10-bundle.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('shopus/css/bootstrap-5.3.2.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('shopus/css/nouislider.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('shopus/css/aos-3.0.0.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('shopus/css/style.css')); ?>">
    
    <!-- Watch Style CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('anvogue/css/output-tailwind.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('anvogue/css/output-scss.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('anvogue/css/swiper-bundle.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('anvogue/css/style.css')); ?>">

    <?php echo $__env->make('layouts.partials.storefront-theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if (! empty(trim($__env->yieldContent('meta')))): ?>
        <?php echo $__env->yieldContent('meta'); ?>
    <?php elseif($siteTagline): ?>
        <meta name="description" content="<?php echo e($siteTagline); ?>">
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="font-sans antialiased <?php echo $__env->yieldContent('body_class'); ?>">
    <?php if($googleTagManagerId): ?>
        <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo e($googleTagManagerId); ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
    <?php endif; ?>
    <div id="app">
        <?php if(session('success') || session('error')): ?>
            <div class="position-fixed top-0 start-50 translate-middle-x mt-4 z-3 w-100" style="max-width: 420px;">
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show shadow" role="alert">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show shadow" role="alert">
                        <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php echo $__env->make('layouts.partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="bg-white style-watch">
            <main class="site-main">
                <?php echo $__env->yieldContent('content'); ?>
            </main>

            <?php echo $__env->make('layouts.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>

    <script src="<?php echo e(asset('shopus/assets/js/jquery_3.7.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('shopus/assets/js/bootstrap_5.3.2.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('shopus/assets/js/nouislider.min.js')); ?>"></script>
    <script src="<?php echo e(asset('shopus/assets/js/aos-3.0.0.js')); ?>"></script>
    <script src="<?php echo e(asset('shopus/assets/js/swiper10-bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('shopus/assets/js/shopus.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.AOS) {
                AOS.init();
            }
        });
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\xampp\htdocs\ecom123\resources\views/layouts/app.blade.php ENDPATH**/ ?>