<?php
    $cartCount = $headerCartCount ?? 0;
    $headerCategories = $headerCategories ?? collect();
    $siteLogo = !empty($appSettings['site_logo'] ?? null)
        ? asset('storage/' . $appSettings['site_logo'])
        : asset('shopus/assets/images/logos/logo.webp');
    $mobileLogo = !empty($appSettings['site_logo_mobile'] ?? null)
        ? asset('storage/' . $appSettings['site_logo_mobile'])
        : $siteLogo;
    $mobileLogoWidth = $appSettings['mobile_logo_width'] ?? '175';
    $mobileLogoHeight = $appSettings['mobile_logo_height'] ?? '100';
    $offersUrl = route('products.index', ['offers' => '1']);
    
    // Fetch toolbar items from database
    $toolbarItems = collect();
    if (\Illuminate\Support\Facades\Schema::hasTable('header_toolbar_items')) {
        try {
            $toolbarItems = \App\Models\HeaderToolbarItem::active()
                ->orderBy('sort_order')
                ->orderBy('created_at')
                ->get();
        } catch (\Exception $e) {
            // Table might not exist yet, use empty collection
            $toolbarItems = collect();
        }
    }
    
    // Get global toolbar settings
    $toolbarHeight = \App\Models\Setting::getValue('toolbar_height', '');
    $toolbarBgColor = \App\Models\Setting::getValue('toolbar_background_color', 'gradient');
    $toolbarMode = \App\Models\Setting::getValue('toolbar_mode', 'scrolling');
    $toolbarFontSize = \App\Models\Setting::getValue('toolbar_font_size', '');
    $toolbarTextColor = $toolbarItems->isNotEmpty() ? ($toolbarItems->first()->text_color ?? null) : null;
    
    // Calculate height value
    $heightValue = '';
    $paddingStyle = '';
    // Handle toolbar height - check for both empty string and null
    if ($toolbarHeight !== null && $toolbarHeight !== '' && trim($toolbarHeight) !== '') {
        $toolbarHeight = trim($toolbarHeight);
        if ($toolbarHeight === 'small') {
            $heightValue = '0.4rem';
            $paddingStyle = 'padding: 0.4rem 0 !important;';
        } elseif ($toolbarHeight === 'medium') {
            $heightValue = '0.5rem';
            $paddingStyle = 'padding: 0.5rem 0 !important;';
        } elseif ($toolbarHeight === 'large') {
            $heightValue = '0.7rem';
            $paddingStyle = 'padding: 0.7rem 0 !important;';
        } else {
            // Custom value - ensure it has proper format
            $heightValue = trim($toolbarHeight);
            // If no unit is specified, assume px
            if (preg_match('/^\d+$/', $heightValue)) {
                $heightValue .= 'px';
            }
            // Ensure the value is not empty before creating padding style
            if (!empty($heightValue) && $heightValue !== '') {
                $paddingStyle = 'padding: ' . $heightValue . ' 0 !important;';
            }
        }
    }
    
    // Calculate global font size value
    $globalFontSizeValue = '';
    if ($toolbarFontSize) {
        if ($toolbarFontSize === 'small') $globalFontSizeValue = '0.75rem';
        elseif ($toolbarFontSize === 'medium') $globalFontSizeValue = '0.875rem';
        elseif ($toolbarFontSize === 'large') $globalFontSizeValue = '1rem';
        else $globalFontSizeValue = $toolbarFontSize;
    }
    
    // Determine toolbar class based on mode
    $toolbarClass = $toolbarMode === 'fixed' ? 'scrolling-toolbar-fixed' : '';
    
    // Calculate font size value helper (item-specific font size overrides global)
    $getFontSizeValue = function($fontSize) use ($globalFontSizeValue) {
        // If item has its own font size, use it; otherwise use global
        if ($fontSize) {
            if ($fontSize === 'small') return '0.75rem';
            if ($fontSize === 'medium') return '0.875rem';
            if ($fontSize === 'large') return '1rem';
            return $fontSize;
        }
        return $globalFontSizeValue;
    };
?>

<?php if($toolbarItems->isNotEmpty()): ?>
<!-- Scrolling Toolbar -->
<div class="scrolling-toolbar <?php echo e($toolbarClass); ?>" style="background: <?php echo e($toolbarBgColor && $toolbarBgColor !== 'gradient' ? $toolbarBgColor : 'linear-gradient(135deg, var(--brand-accent) 0%, #ff6b35 100%)'); ?>; color: <?php echo e($toolbarTextColor ?: '#ffffff'); ?>;<?php if($paddingStyle): ?> <?php echo e($paddingStyle); ?><?php endif; ?> <?php if($globalFontSizeValue): ?> font-size: <?php echo e($globalFontSizeValue); ?> !important;<?php endif; ?>">
    <div class="scrolling-toolbar-content">
        <?php $__currentLoopData = $toolbarItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $itemFontSize = $getFontSizeValue($item->font_size);
                $itemStyle = 'color: ' . ($item->text_color ?: ($toolbarTextColor ?: '#ffffff')) . ';';
                // Only apply font size if item has its own font size (overrides global)
                if ($item->font_size && $itemFontSize) {
                    $itemStyle .= ' font-size: ' . $itemFontSize . ';';
                }
            ?>
            <?php if($item->link): ?>
                <a href="<?php echo e($item->link); ?>" class="scrolling-toolbar-item" style="<?php echo e($itemStyle); ?> text-decoration: none;">
                    <?php if($item->icon): ?>
                        <span class="scrolling-toolbar-icon"><?php echo e($item->icon); ?></span>
                    <?php endif; ?>
                    <span><?php echo e($item->text); ?></span>
                </a>
            <?php else: ?>
                <div class="scrolling-toolbar-item" style="<?php echo e($itemStyle); ?>">
                    <?php if($item->icon): ?>
                        <span class="scrolling-toolbar-icon"><?php echo e($item->icon); ?></span>
                    <?php endif; ?>
                    <span><?php echo e($item->text); ?></span>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php if($toolbarMode === 'scrolling'): ?>
        <!-- Duplicate items for seamless loop (only in scrolling mode) -->
        <?php $__currentLoopData = $toolbarItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $itemFontSize = $getFontSizeValue($item->font_size);
                $itemStyle = 'color: ' . ($item->text_color ?: ($toolbarTextColor ?: '#ffffff')) . ';';
                // Only apply font size if item has its own font size (overrides global)
                if ($item->font_size && $itemFontSize) {
                    $itemStyle .= ' font-size: ' . $itemFontSize . ';';
                }
            ?>
            <?php if($item->link): ?>
                <a href="<?php echo e($item->link); ?>" class="scrolling-toolbar-item" style="<?php echo e($itemStyle); ?> text-decoration: none;">
                    <?php if($item->icon): ?>
                        <span class="scrolling-toolbar-icon"><?php echo e($item->icon); ?></span>
                    <?php endif; ?>
                    <span><?php echo e($item->text); ?></span>
                </a>
            <?php else: ?>
                <div class="scrolling-toolbar-item" style="<?php echo e($itemStyle); ?>">
                    <?php if($item->icon): ?>
                        <span class="scrolling-toolbar-icon"><?php echo e($item->icon); ?></span>
                    <?php endif; ?>
                    <span><?php echo e($item->text); ?></span>
                </div>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<header class="site-header">
    <div class="container px-4 px-lg-5 site-header-inner">
        <!-- Mobile -->
        <div class="site-header-mobile d-flex d-lg-none align-items-center justify-content-between">
        <button class="icon-button" type="button" data-bs-toggle="collapse" data-bs-target="#site-mobile-nav" aria-controls="site-mobile-nav" aria-expanded="false" aria-label="Toggle navigation">
            <svg width="20" height="14" viewBox="0 0 20 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="20" height="2" rx="1" fill="currentColor"/>
                <rect y="6" width="20" height="2" rx="1" fill="currentColor"/>
                <rect y="12" width="14" height="2" rx="1" fill="currentColor"/>
            </svg>
        </button>

        <a href="<?php echo e(route('home')); ?>" class="site-header-logo">
            <img src="<?php echo e($mobileLogo); ?>" alt="<?php echo e(config('app.name')); ?>" class="site-header-logo-img site-header-logo-img--mobile" style="width: <?php echo e($mobileLogoWidth); ?>px; max-width: <?php echo e($mobileLogoWidth); ?>px; height: auto; max-height: <?php echo e($mobileLogoHeight); ?>px;">
        </a>

        <div class="d-flex align-items-center gap-2">
            <a href="<?php echo e(route('wishlist.index')); ?>" class="icon-button" aria-label="Wishlist">
                <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 19s-8-4.438-8-10a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 5.562-8 10-8 10Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
            <a href="<?php echo e(route('cart.index')); ?>" class="icon-button position-relative" aria-label="Cart">
                <svg width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 6h16l-1 12.5a2 2 0 0 1-2 1.5H5a2 2 0 0 1-2-1.5L2 6Z" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M6 6V4a4 4 0 0 1 8 0v2" stroke="currentColor" stroke-width="1.5"/>
                </svg>
                <?php if($cartCount > 0): ?>
                    <span class="badge bg-accent cart-count"><?php echo e($cartCount); ?></span>
                <?php endif; ?>
            </a>
        </div>
    </div>

    <div class="collapse d-lg-none" id="site-mobile-nav">
        <div class="site-header-mobile-menu">
            <nav class="nav flex-column gap-2">
                <a class="nav-link" href="<?php echo e(route('home')); ?>">Home</a>
                <a class="nav-link" href="<?php echo e(route('products.index')); ?>">Products</a>
                <a class="nav-link" href="<?php echo e(route('about')); ?>">About Us</a>
                <a class="nav-link" href="<?php echo e($offersUrl); ?>">Offers</a>
                <a class="nav-link" href="<?php echo e(route('blog.index')); ?>">Blog</a>
                <a class="nav-link" href="<?php echo e(route('social-hub')); ?>">Social Hub</a>
            </nav>
            <hr class="my-3">
            <nav class="nav flex-column gap-2">
                <?php $__empty_1 = true; $__currentLoopData = $headerCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a class="nav-link small" href="<?php echo e(route('products.category', $category->slug)); ?>"><?php echo e($category->name); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <span class="text-soft small">Categories coming soon</span>
                <?php endif; ?>
            </nav>
            <div class="mt-3 d-flex gap-2">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('customer.dashboard')); ?>" class="btn btn-primary w-100">Dashboard</a>
                    <form action="<?php echo e(route('logout')); ?>" method="POST" class="w-100">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-ghost w-100">Logout</button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-primary w-100">Sign in</a>
                    <?php if(Route::has('register')): ?>
                        <a href="<?php echo e(route('register')); ?>" class="btn btn-ghost w-100">Create account</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Desktop -->
    <div class="site-header-desktop d-none d-lg-flex align-items-center justify-content-between">
        <nav class="nav site-header-links">
            <a class="nav-link" href="<?php echo e(route('home')); ?>">Home</a>
            <a class="nav-link" href="<?php echo e(route('products.index')); ?>">Products</a>
            <a class="nav-link" href="<?php echo e(route('about')); ?>">About Us</a>
        </nav>

        <a href="<?php echo e(route('home')); ?>" class="site-header-logo">
            <img src="<?php echo e($siteLogo); ?>" alt="<?php echo e(config('app.name')); ?>" class="site-header-logo-img" width="140" height="75">
        </a>

        <div class="d-flex align-items-center gap-3">
            <nav class="nav site-header-links">
                <a class="nav-link" href="<?php echo e($offersUrl); ?>">Offers</a>
                <a class="nav-link" href="<?php echo e(route('blog.index')); ?>">Blog</a>
                <a class="nav-link" href="<?php echo e(route('social-hub')); ?>">Social Hub</a>
            </nav>
            <div class="site-header-actions d-flex align-items-center gap-2">
                <a href="<?php echo e(route('compare.index')); ?>" class="icon-button" aria-label="Compare">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6 3v14M3 6h6M14 3v14M11 11h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <a href="<?php echo e(route('wishlist.index')); ?>" class="icon-button" aria-label="Wishlist">
                    <svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11 19s-8-4.438-8-10a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 5.562-8 10-8 10Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <a href="<?php echo e(route('cart.index')); ?>" class="icon-button position-relative" aria-label="Cart">
                    <svg width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 6h16l-1 12.5a2 2 0 0 1-2 1.5H5a2 2 0 0 1-2-1.5L2 6Z" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M6 6V4a4 4 0 0 1 8 0v2" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    <?php if($cartCount > 0): ?>
                        <span class="badge bg-accent cart-count"><?php echo e($cartCount); ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?php echo e(auth()->check() ? route('customer.dashboard') : route('login')); ?>" class="icon-button" aria-label="Account">
                    <svg width="20" height="22" viewBox="0 0 20 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="10" cy="7" r="4" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M3 19c0-3.314 3.134-6 7-6s7 2.686 7 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    </div>
</header>

<?php /**PATH D:\xampp\htdocs\ecom123\resources\views/layouts/partials/header.blade.php ENDPATH**/ ?>