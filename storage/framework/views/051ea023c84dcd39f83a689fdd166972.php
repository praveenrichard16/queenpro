<?php ($brandPrimary = '#054450'); ?>
<?php ($brandAccent = '#f97316'); ?>
<style>
    :root {
        --brand-background: #f7f8fb;
        --brand-surface: #ffffff;
        --brand-primary: <?php echo e($brandPrimary); ?>;
        --brand-accent: <?php echo e($brandAccent); ?>;
        --brand-muted: #6b7280;
        --brand-border: rgba(17, 24, 39, 0.08);
        --header-background: #02454f;
        --header-text: #f8fafc;
    }

    body {
        background-color: var(--brand-background);
        color: #0b1220;
        font-family: 'Figtree', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        line-height: 1.6;
    }

    a {
        color: var(--brand-primary);
        text-decoration: none;
        transition: color 0.2s ease;
    }

    a:hover,
    a:focus {
        color: var(--brand-accent);
    }

    .btn,
    .btn-primary,
    .shop-btn {
        --bs-btn-bg: var(--brand-primary);
        --bs-btn-border-color: var(--brand-primary);
        --bs-btn-color: #f0e0b8;
        --bs-btn-hover-bg: #043a42;
        --bs-btn-hover-border-color: #043a42;
        --bs-btn-hover-color: #f0e0b8;
        --bs-btn-focus-shadow-rgb: 5, 68, 80;
        --bs-btn-active-bg: #032e34;
        --bs-btn-active-border-color: #032e34;
        --bs-btn-active-color: #f0e0b8;
        color: #f0e0b8;
    }

    .btn-primary {
        background-color: var(--brand-primary);
        border-color: var(--brand-primary);
        color: #f0e0b8;
    }
    
    .btn-primary:hover,
    .btn-primary:focus {
        background-color: #043a42;
        border-color: #043a42;
        color: #f0e0b8;
    }
    
    .btn-primary:active {
        background-color: #032e34;
        border-color: #032e34;
        color: #f0e0b8;
    }

    .btn-ghost,
    .shop-btn.view-btn {
        background-color: rgba(17, 24, 39, 0.05);
        color: var(--brand-primary);
        border: 1px solid rgba(17, 24, 39, 0.12);
    }

    .btn-ghost:hover,
    .shop-btn.view-btn:hover {
        background-color: rgba(17, 24, 39, 0.12);
    }

    .badge.bg-accent {
        background-color: var(--brand-accent) !important;
        color: #ffffff;
        border-radius: 999px;
        font-size: 0.65rem;
        padding: 0.1rem 0.45rem;
    }

    .product-sale.bg-green {
        background-color: #054450 !important;
        color: #f0e0b8 !important;
    }

    .page-shell {
        padding-top: clamp(3rem, 5vw, 4.5rem);
        padding-bottom: clamp(3rem, 6vw, 5rem);
    }

    .page-card {
        background-color: var(--brand-surface);
        border-radius: 1.25rem;
        border: 1px solid var(--brand-border);
        box-shadow: 0 18px 40px -22px rgba(15, 23, 42, 0.16);
    }

    .lead-muted {
        color: var(--brand-muted);
        font-size: 1.05rem;
    }

    .badge-soft {
        background-color: rgba(17, 24, 39, 0.08);
        color: var(--brand-primary);
        border-radius: 999px;
        padding: 0.35rem 0.85rem;
        font-size: 0.75rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .section-heading {
        font-size: clamp(1.75rem, 3vw, 2.4rem);
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .section-subheading {
        color: var(--brand-muted);
        max-width: 42rem;
    }

    .divider-fade {
        height: 1px;
        width: 100%;
        background: linear-gradient(90deg, rgba(17, 24, 39, 0) 0%, rgba(17, 24, 39, 0.15) 50%, rgba(17, 24, 39, 0) 100%);
        margin: 3rem 0;
    }

    .chip {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border-radius: 999px;
        border: 1px solid rgba(17, 24, 39, 0.16);
        padding: 0.45rem 0.9rem;
        font-size: 0.85rem;
        color: var(--brand-muted);
        background-color: rgba(17, 24, 39, 0.04);
    }

    .text-soft {
        color: var(--brand-muted) !important;
    }

    .icon-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: 1px solid rgba(17, 24, 39, 0.12);
        background-color: var(--brand-surface);
        color: var(--brand-primary);
        transition: all 0.2s ease;
        font-size: 1.1rem;
    }

    .icon-button:hover {
        border-color: rgba(17, 24, 39, 0.24);
        background-color: #f0f2f6;
        color: var(--brand-accent);
    }

    .icon-button svg {
        display: block;
    }

    .icon-button .cart-count {
        position: absolute;
        top: 0.4rem;
        right: 0.35rem;
    }

    .scrolling-toolbar {
        /* Default colors - can be overridden by inline styles */
        background: linear-gradient(135deg, var(--brand-accent) 0%, #ff6b35 100%);
        color: #ffffff;
        padding: 0.5rem 0;
        overflow: hidden;
        position: relative;
        white-space: nowrap;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .scrolling-toolbar-content {
        display: flex;
        animation: scroll-toolbar 20s linear infinite;
        gap: 4rem;
        will-change: transform;
        backface-visibility: hidden;
        perspective: 1000px;
    }

    .scrolling-toolbar-item {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        letter-spacing: 0.02em;
        flex-shrink: 0;
        transition: opacity 0.2s ease;
    }
    
    .scrolling-toolbar-item:hover {
        opacity: 0.9;
    }
    
    .scrolling-toolbar-item a {
        color: inherit;
        text-decoration: none;
    }

    .scrolling-toolbar-icon {
        font-size: 1rem;
        display: inline-block;
        animation: bounce-icon 2s ease-in-out infinite;
    }

    @keyframes scroll-toolbar {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-50%);
        }
    }

    @keyframes bounce-icon {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-3px);
        }
    }

    .scrolling-toolbar:hover .scrolling-toolbar-content {
        animation-play-state: paused;
    }

    /* Fixed mode toolbar - no scrolling animation */
    .scrolling-toolbar-fixed .scrolling-toolbar-content {
        animation: none;
        justify-content: center;
        flex-wrap: wrap;
    }

    .scrolling-toolbar-fixed .scrolling-toolbar-item {
        margin: 0 1rem;
    }

    @media (max-width: 768px) {
        .scrolling-toolbar {
            padding: 0.4rem 0;
        }
        .scrolling-toolbar-item {
            font-size: 0.75rem;
            gap: 0.35rem;
        }
        .scrolling-toolbar-content {
            gap: 3rem;
            animation-duration: 25s;
        }
    }

    .site-header {
        background-color: var(--header-background);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        color: var(--header-text);
        padding: 0.45rem 0;
        position: relative;
        width: 100%;
        z-index: 1000;
    }

    .site-header-inner {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .site-header-mobile {
        gap: 0.5rem;
    }

    .site-header-logo-img {
        width: 140px;
        max-width: 140px;
        height: 75px;
        object-fit: contain;
        transition: transform 0.2s ease;
    }

    .site-header-logo-img--mobile {
        /* Dimensions will be set inline from settings */
        height: auto;
        object-fit: contain;
    }

    .site-header-logo:hover .site-header-logo-img {
        transform: scale(1.02);
    }

    .site-header-desktop {
        gap: 2rem;
    }

    .site-header-links {
        gap: 1.5rem;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        font-size: 1.05rem;
        font-weight: 600;
    }

    .site-header-links .nav-link {
        padding: 0;
        color: var(--header-text);
        position: relative;
    }

    .site-header-links .nav-link::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -0.4rem;
        width: 100%;
        height: 2px;
        background: var(--brand-accent);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.2s ease;
    }

    .site-header-links .nav-link:hover::after,
    .site-header-links .nav-link:focus::after {
        transform: scaleX(1);
    }

    .site-header-links .nav-link:hover,
    .site-header-links .nav-link:focus {
        color: #ffffff;
    }

    .site-header-actions .icon-button {
        width: 40px;
        height: 40px;
    }

    .site-header .icon-button {
        width: 40px;
        height: 40px;
        border-color: rgba(255, 255, 255, 0.25);
        background-color: rgba(255, 255, 255, 0.08);
        color: var(--header-text);
    }

    .site-header .icon-button:hover {
        border-color: rgba(255, 255, 255, 0.45);
        background-color: rgba(255, 255, 255, 0.18);
        color: #ffffff;
    }

    .site-header .nav-link::after {
        background: var(--brand-accent);
    }

    .home-hero-slider {
        position: relative;
        overflow: hidden;
    }

    .home-hero-slider .carousel-inner {
        border-radius: 0 0 32px 32px;
    }

    /* Slider Animation Styles */
    .home-hero-slider .carousel-item {
        transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.8s ease-in-out;
    }

    .home-hero-slider .carousel-item:not(.active) {
        opacity: 0;
        transform: scale(1.05);
    }

    .home-hero-slider .carousel-item.active {
        opacity: 1;
        transform: scale(1);
    }

    .home-hero-slider__image {
        transition: transform 1s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .home-hero-slider .carousel-item.active .home-hero-slider__image {
        animation: zoomIn 1s ease-out;
    }

    @keyframes zoomIn {
        from {
            transform: scale(1.1);
            opacity: 0.8;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .home-hero-slider__content {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s ease-out 0.3s, transform 0.8s ease-out 0.3s;
    }

    .home-hero-slider .carousel-item.active .home-hero-slider__content {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Slider clickable item */
    .slider-clickable {
        cursor: pointer;
    }
    
    /* Button size variations */
    .home-hero-slider__content .btn.btn-sm {
        padding: 0.5rem 1.5rem;
        font-size: 0.875rem;
    }
    
    .home-hero-slider__content .btn.btn-lg {
        padding: 1rem 2rem;
        font-size: 1.125rem;
    }
    
    /* Text alignment for slider content */
    .home-hero-slider__content.text-center {
        max-width: 100%;
        text-align: center;
    }
    
    .home-hero-slider__content.text-right {
        max-width: 100%;
        text-align: right;
    }
    
    .home-hero-slider__content .slider-title,
    .home-hero-slider__content .slider-description,
    .home-hero-slider__content .slider-button-wrapper {
        width: 100%;
    }

    .home-hero-slider__content h2 {
        animation: fadeInUp 0.8s ease-out 0.5s both;
    }

    .home-hero-slider__content p {
        animation: fadeInUp 0.8s ease-out 0.7s both;
    }

    .home-hero-slider__content .btn {
        animation: fadeInUp 0.8s ease-out 0.9s both;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .home-hero-slider .carousel-indicators [data-bs-target] {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.4);
    }

    .home-hero-slider .carousel-indicators .active {
        background-color: var(--brand-accent);
    }

    .home-hero-slider__image {
        min-height: 420px;
        object-fit: cover;
    }

    .home-hero-slider__overlay {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        background: linear-gradient(90deg, rgba(2, 69, 79, 0.65) 0%, rgba(2, 69, 79, 0.05) 65%);
    }

    .home-hero-slider__content {
        max-width: 560px;
        padding: 3.5rem 0;
    }

    .site-header-mobile-menu {
        background-color: var(--header-background);
        border-top: 1px solid rgba(255, 255, 255, 0.12);
        padding: 1.5rem 1.25rem 1.75rem;
        box-shadow: 0 16px 30px -20px rgba(2, 69, 79, 0.4);
    }

    .site-header-mobile-menu .nav-link {
        color: var(--header-text);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .site-header-mobile-menu .nav-link:hover {
        color: var(--brand-accent);
    }

    .footer,
    #footer {
        position: relative;
        width: 100%;
        clear: both;
    }

    .site-footer {
        background-color: #0b1220;
        color: #e2e8f0;
        margin-top: 4rem;
    }

    .site-footer-logo img {
        width: clamp(180px, 18vw, 220px);
        filter: brightness(0) invert(1);
    }

    .site-footer-title {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.18em;
        color: #94a3b8;
        margin-bottom: 1rem;
    }

    .site-footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        gap: 0.45rem;
    }

    .site-footer-links a {
        color: #e2e8f0;
        font-size: 0.95rem;
    }

    .site-footer-links a:hover {
        color: var(--brand-accent);
    }

    .site-footer-divider {
        border-color: rgba(226, 232, 240, 0.08);
    }

    @media (max-width: 992px) {
        .site-header-desktop {
            display: none !important;
        }
    }

    @media (max-width: 991px) {
        .home-hero-slider__overlay {
            background: linear-gradient(180deg, rgba(2, 69, 79, 0.75) 0%, rgba(2, 69, 79, 0.4) 60%, rgba(2, 69, 79, 0.2) 100%);
            align-items: flex-end;
        }

        .home-hero-slider__content {
            max-width: 100%;
            padding: 2.5rem 0 2rem;
            text-align: center;
        }

        .home-hero-slider__content .btn {
            width: 100%;
        }
    }

    @media (min-width: 992px) {
        .site-header-inner {
            gap: 0.5rem;
        }

        .home-hero-slider__image {
            min-height: 560px;
        }
    }

    @media (max-width: 991px) {
        .site-header {
            padding: 0.0625rem 0;
        }

        .site-header-mobile {
            gap: 0.125rem;
            height: 60px;
            min-height: 60px;
        }

        .site-header-inner {
            gap: 0.125rem;
        }

        .site-header-logo-img--mobile {
            transform: scale(0.5);
            transform-origin: center;
        }

        .site-header .icon-button {
            width: 20px;
            height: 20px;
            padding: 0.25rem;
            min-width: 20px;
            min-height: 20px;
        }

        .site-header .icon-button svg {
            width: 14px;
            height: 14px;
        }

        .site-header-mobile button.icon-button {
            width: 20px;
            height: 20px;
            padding: 0.25rem;
        }
    }

    @media (max-width: 576px) {
        .page-shell {
            padding-top: 2.5rem;
            padding-bottom: 3.5rem;
        }

        .site-header {
            padding: 0.05rem 0;
        }

        .site-header-mobile {
            gap: 0.1rem;
            height: 60px;
            min-height: 60px;
        }

        .site-header-logo-img--mobile {
            /* Dimensions will be set inline from settings */
            transform: scale(0.5);
            transform-origin: center;
            object-fit: contain;
        }

        .site-header .icon-button {
            width: 18px;
            height: 18px;
            padding: 0.2rem;
            min-width: 18px;
            min-height: 18px;
        }

        .site-header .icon-button svg {
            width: 12px;
            height: 12px;
        }

        .site-header-mobile button.icon-button {
            width: 18px;
            height: 18px;
            padding: 0.2rem;
        }
    }
</style>

<?php /**PATH D:\xampp\htdocs\ecom123\resources\views/layouts/partials/storefront-theme.blade.php ENDPATH**/ ?>