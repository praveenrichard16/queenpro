<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteName = $appSettings['site_name'] ?? config('app.name', 'Laravel');
        $siteTagline = $appSettings['site_tagline'] ?? null;
        $favicon = !empty($appSettings['site_favicon'])
            ? asset('storage/'.$appSettings['site_favicon'])
            : asset('shopus/assets/images/homepage-one/icon.png');
    @endphp
    <title>@yield('title', $siteTagline ?? 'Shop') &mdash; {{ $siteName }}</title>
    <link rel="icon" href="{{ $favicon }}">

    @php
        $googleAnalyticsId = $appSettings['google_analytics_id'] ?? null;
        $googleTagManagerId = $appSettings['google_tag_manager_id'] ?? null;
        $googleSearchConsole = $appSettings['google_search_console_verification'] ?? null;
    @endphp

    @if($googleTagManagerId)
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','{{ $googleTagManagerId }}');
        </script>
    @endif

    @if($googleAnalyticsId)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $googleAnalyticsId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $googleAnalyticsId }}');
        </script>
    @endif

    @if($googleSearchConsole)
        @if(str_contains($googleSearchConsole, '<meta'))
            {!! $googleSearchConsole !!}
        @else
            <meta name="google-site-verification" content="{{ $googleSearchConsole }}">
        @endif
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('shopus/css/swiper10-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('shopus/css/bootstrap-5.3.2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('shopus/css/nouislider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('shopus/css/aos-3.0.0.css') }}">
    <link rel="stylesheet" href="{{ asset('shopus/css/style.css') }}">
    
    <!-- Watch Style CSS -->
    <link rel="stylesheet" href="{{ asset('anvogue/css/output-tailwind.css') }}">
    <link rel="stylesheet" href="{{ asset('anvogue/css/output-scss.css') }}">
    <link rel="stylesheet" href="{{ asset('anvogue/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('anvogue/css/style.css') }}">

    @include('layouts.partials.storefront-theme')

    @hasSection('meta')
        @yield('meta')
    @elseif($siteTagline)
        <meta name="description" content="{{ $siteTagline }}">
    @endif

    @stack('styles')
</head>
<body class="font-sans antialiased @yield('body_class')">
    @if($googleTagManagerId)
        <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id={{ $googleTagManagerId }}" height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
    @endif
    <div id="app">
        @if(session('success') || session('error'))
            <div class="position-fixed top-0 start-50 translate-middle-x mt-4 z-3 w-100" style="max-width: 420px;">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show shadow" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
        @endif

        @include('layouts.partials.header')

        <div class="bg-white style-watch">
            <main class="site-main">
                @yield('content')
            </main>

            @include('layouts.partials.footer')
        </div>
    </div>

    <script src="{{ asset('shopus/assets/js/jquery_3.7.1.min.js') }}"></script>
    <script src="{{ asset('shopus/assets/js/bootstrap_5.3.2.bundle.min.js') }}"></script>
    <script src="{{ asset('shopus/assets/js/nouislider.min.js') }}"></script>
    <script src="{{ asset('shopus/assets/js/aos-3.0.0.js') }}"></script>
    <script src="{{ asset('shopus/assets/js/swiper10-bundle.min.js') }}"></script>
    <script src="{{ asset('shopus/assets/js/shopus.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.AOS) {
                AOS.init();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
