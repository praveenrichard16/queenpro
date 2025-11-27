<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>@yield('title', 'WowDash Admin')</title>
		@php
			$favicon = !empty($appSettings['site_favicon'])
				? asset('storage/'.$appSettings['site_favicon'])
				: asset('wowdash/assets/images/favicon.png');
			$siteLogoLight = !empty($appSettings['site_logo'])
				? asset('storage/'.$appSettings['site_logo'])
				: asset('wowdash/assets/images/logo.png');
			$siteLogoDark = !empty($appSettings['site_logo_dark'])
				? asset('storage/'.$appSettings['site_logo_dark'])
				: $siteLogoLight;

			// Always display the dark logo throughout the admin dashboard when provided.
			if (!empty($appSettings['site_logo_dark'])) {
				$siteLogoLight = $siteLogoDark;
			}

			$siteLogoIcon = $siteLogoDark;
			$adminUser = auth()->user();
		@endphp
		<link rel="icon" type="image/png" href="{{ $favicon }}">
		<link rel="stylesheet" href="{{ asset('wowdash/assets/css/remixicon.css') }}">
		<link rel="stylesheet" href="{{ asset('wowdash/assets/css/lib/bootstrap.min.css') }}">
		<link rel="stylesheet" href="{{ asset('wowdash/assets/css/lib/apexcharts.css') }}">
		<link rel="stylesheet" href="{{ asset('wowdash/assets/css/lib/dataTables.min.css') }}">
		<link rel="stylesheet" href="{{ asset('wowdash/assets/css/lib/editor-katex.min.css') }}">
		<link rel="stylesheet" href="{{ asset('wowdash/assets/css/lib/editor.atom-one-dark.min.css') }}">
		<link rel="stylesheet" href="{{ asset('wowdash/assets/css/lib/editor.quill.snow.css') }}">
		<link rel="stylesheet" href="{{ asset('wowdash/assets/css/lib/flatpickr.min.css') }}">
		<link rel="stylesheet" href="{{ asset('wowdash/assets/css/lib/full-calendar.css') }}">
		<link rel="stylesheet" href="{{ asset('wowdash/assets/css/lib/jquery-jvectormap-2.0.5.css') }}">
		<link rel="stylesheet" href="{{ asset('wowdash/assets/css/lib/magnific-popup.css') }}">
		<link rel="stylesheet" href="{{ asset('wowdash/assets/css/lib/slick.css') }}">
		<link rel="stylesheet" href="{{ asset('wowdash/assets/css/lib/prism.css') }}">
		<link rel="stylesheet" href="{{ asset('wowdash/assets/css/lib/file-upload.css') }}">
		<link rel="stylesheet" href="{{ asset('wowdash/assets/css/lib/audioplayer.css') }}">
		<link rel="stylesheet" href="{{ asset('wowdash/assets/css/style.css') }}">
	<style>
			.sidebar-menu > li.active > a,
			.sidebar-menu > li > a:hover {
				background-color: #024550 !important;
				color: #ffffff !important;
			}
			.sidebar-menu > li > a:hover .menu-icon,
			.sidebar-menu > li.active > a .menu-icon {
				color: #ffffff !important;
			}
			.btn-primary,
			.btn-warning,
			.btn-success,
			.btn-danger,
			.btn-info,
			.btn-outline-primary,
			.btn-outline-secondary,
			.btn-outline-success,
			.btn-outline-danger,
			.btn-outline-warning,
			.btn-outline-info,
			.btn.btn-link.text-decoration-none,
			.btn,
			button[type="submit"],
			input[type="submit"] {
				background-color: #024550 !important;
				border-color: #024550 !important;
				color: #ffffff !important;
			}

			.btn:hover,
			.btn:focus,
			.btn:active {
				background-color: #03606a !important;
				border-color: #03606a !important;
				color: #ffffff !important;
			}

			.btn-outline-secondary,
			.btn-outline-secondary:hover,
			.btn-outline-secondary:focus {
				background-color: transparent !important;
				color: #024550 !important;
				border-color: #024550 !important;
			}

			.btn-outline-secondary:hover,
			.btn-outline-secondary:focus {
				background-color: rgba(2, 69, 80, 0.08) !important;
			}
			.navbar-header .dropdown-toggle::after {
				display: none !important;
			}
			.navbar-header .dropdown-toggle iconify-icon {
				display: inline-flex;
				align-items: center;
				justify-content: center;
				margin-left: 8px;
				color: #6e6d79;
			}
			.sidebar-menu .submenu-toggle {
				display: flex;
				align-items: center;
				justify-content: space-between;
				gap: 0.75rem;
			}
			.sidebar-menu .submenu-arrow {
				transition: transform 0.2s ease;
				color: #6e6d79;
			}
			.sidebar-menu .submenu-arrow.rotate-180 {
				transform: rotate(180deg);
			}
			.sidebar-menu .sidebar-submenu {
				list-style: none;
				padding-left: 2.75rem;
				margin: 0.75rem 0 0;
				display: grid;
				gap: 0.35rem;
			}
			.sidebar-menu .sidebar-submenu li a {
				display: flex;
				align-items: center;
				gap: 0.6rem;
				padding: 0.5rem 0.75rem;
				border-radius: 0.75rem;
				color: #6e6d79;
				font-size: 0.95rem;
				transition: background-color 0.2s ease, color 0.2s ease;
			}
			.sidebar-menu .sidebar-submenu li a:hover,
			.sidebar-menu .sidebar-submenu li a.active {
				color: #024550 !important;
				background-color: rgba(2, 69, 80, 0.08);
			}
	</style>
		@stack('styles')
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
				<a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
					<img src="{{ $siteLogoLight }}" alt="logo" class="light-logo" style="width:240px;height:140px;object-fit:contain;">
					<img src="{{ $siteLogoDark }}" alt="logo" class="dark-logo" style="width:240px;height:140px;object-fit:contain;">
					<img src="{{ $siteLogoIcon }}" alt="logo" class="logo-icon">
				</a>
			</div>
			<div class="sidebar-menu-area">
				<ul class="sidebar-menu" id="sidebar-menu">
					@php
						$blogMenuActive = request()->routeIs('admin.blog.posts.*') || request()->routeIs('admin.blog.categories.*') || request()->routeIs('admin.blog.tags.*');
						$catalogMenuActive = request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.tags.*') || request()->routeIs('admin.brands.*') || request()->routeIs('admin.attributes.*');
						$homeMenuActive = request()->routeIs('admin.cms.home.index')
							|| request()->routeIs('admin.cms.home.sliders.*')
							|| request()->routeIs('admin.cms.home.second-slider.*')
							|| request()->routeIs('admin.cms.home.product-slides.*')
							|| request()->routeIs('admin.cms.home.seo.*');
						$cmsMenuActive = $homeMenuActive
							|| request()->routeIs('admin.cms.header.toolbar.*')
							|| request()->routeIs('admin.cms.legal-pages.*')
							|| request()->routeIs('admin.cms.about-us.*')
							|| request()->routeIs('admin.cms.breadcrumb.*');
					$supportMenuActive = request()->routeIs('admin.support.*');
					$settingsMenuActive = request()->routeIs('admin.settings.*') || request()->routeIs('admin.integrations.*') || request()->routeIs('admin.tax-classes.*') || request()->routeIs('admin.shipping-methods.*') || request()->routeIs('admin.documentation.*');
					$affiliateMenuActive = request()->routeIs('admin.affiliates.*');
					$notificationMenuActive = request()->routeIs('notifications.*');
					$customerMenuActive = request()->routeIs('admin.customers.*');
					$marketingMenuActive = request()->routeIs('admin.marketing.*');
					$offerMenuActive = request()->routeIs('admin.offers.*');
					$invoiceMenuActive = request()->routeIs('admin.invoices.*');
					$leadMenuActive = request()->routeIs('admin.leads.*') || request()->routeIs('admin.lead-sources.*') || request()->routeIs('admin.lead-stages.*');
					$quotationMenuActive = request()->routeIs('admin.quotations.*');
					$enquiryMenuActive = request()->routeIs('admin.enquiries.*');
					$usersMenuActive = request()->routeIs('admin.users.*');
					$documentationMenuActive = request()->routeIs('admin.documentation.*');
					@endphp
					<li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
						<a href="{{ route('admin.dashboard') }}">
							<iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
							<span>Dashboard</span>
						</a>
					</li>
					<li class="{{ $catalogMenuActive ? 'active' : '' }}">
						<a href="javascript:void(0)"
						   class="submenu-toggle d-flex align-items-center justify-content-between gap-3"
						   data-submenu="catalog"
						   data-bs-toggle="collapse"
						   data-bs-target="#sidebarCatalogMenu"
						   aria-expanded="{{ $catalogMenuActive ? 'true' : 'false' }}"
						   aria-controls="sidebarCatalogMenu">
							<span class="d-inline-flex align-items-center gap-3">
								<iconify-icon icon="mdi:store-outline" class="menu-icon"></iconify-icon>
								<span>Catalog</span>
							</span>
							<iconify-icon icon="solar:alt-arrow-down-outline" class="submenu-arrow {{ $catalogMenuActive ? 'rotate-180' : '' }}"></iconify-icon>
						</a>
						<ul class="sidebar-submenu collapse {{ $catalogMenuActive ? 'show' : '' }}" id="sidebarCatalogMenu">
							<li>
								<a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:bag-smile-outline" class="text-primary-main"></iconify-icon>
									Products
								</a>
							</li>
							<li>
								<a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:menu-dots-vertical-linear" class="text-info-main"></iconify-icon>
									Product Categories
								</a>
							</li>
							<li>
								<a href="{{ route('admin.tags.index') }}" class="{{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:hashtag-linear" class="text-warning"></iconify-icon>
									Product Tags
								</a>
							</li>
							<li>
								<a href="{{ route('admin.brands.index') }}" class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:medal-ribbons-star-linear" class="text-success"></iconify-icon>
									Brands
								</a>
							</li>
							<li>
								<a href="{{ route('admin.attributes.index') }}" class="{{ request()->routeIs('admin.attributes.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:settings-linear" class="text-purple"></iconify-icon>
									Attributes
								</a>
							</li>
						</ul>
					</li>
					<li class="{{ $cmsMenuActive ? 'active' : '' }}">
						<a href="javascript:void(0)"
						   class="submenu-toggle d-flex align-items-center justify-content-between gap-3"
						   data-submenu="cms"
						   data-bs-toggle="collapse"
						   data-bs-target="#sidebarCmsMenu"
						   aria-expanded="{{ $cmsMenuActive ? 'true' : 'false' }}"
						   aria-controls="sidebarCmsMenu">
							<span class="d-inline-flex align-items-center gap-3">
								<iconify-icon icon="solar:document-add-linear" class="menu-icon"></iconify-icon>
								<span>CMS</span>
							</span>
							<iconify-icon icon="solar:alt-arrow-down-outline" class="submenu-arrow {{ $cmsMenuActive ? 'rotate-180' : '' }}"></iconify-icon>
						</a>
						<ul class="sidebar-submenu collapse {{ $cmsMenuActive ? 'show' : '' }}" id="sidebarCmsMenu">
							<li>
								<a href="{{ route('admin.cms.home.index') }}" class="{{ $homeMenuActive ? 'active' : '' }}">
									<iconify-icon icon="solar:home-smile-angle-outline" class="text-primary-main"></iconify-icon>
									Home
								</a>
							</li>
							<li>
								<a href="{{ route('admin.cms.header.toolbar.index') }}" class="{{ request()->routeIs('admin.cms.header.toolbar.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:menu-dots-square-outline" class="text-info-main"></iconify-icon>
									Header
								</a>
							</li>
							<li>
								<a href="{{ route('admin.cms.legal-pages.index') }}" class="{{ request()->routeIs('admin.cms.legal-pages.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:document-text-linear" class="text-warning"></iconify-icon>
									Legal Pages
								</a>
							</li>
							<li>
								<a href="{{ route('admin.cms.about-us.edit') }}" class="{{ request()->routeIs('admin.cms.about-us.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:info-circle-linear" class="text-purple"></iconify-icon>
									About Us
								</a>
							</li>
							<li>
								<a href="{{ route('admin.cms.breadcrumb.settings') }}" class="{{ request()->routeIs('admin.cms.breadcrumb.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:layers-linear" class="text-success"></iconify-icon>
									Breadcrumb
								</a>
							</li>
						</ul>
					</li>
					<li class="{{ $usersMenuActive ? 'active' : '' }}">
						<a href="javascript:void(0)"
						   class="submenu-toggle d-flex align-items-center justify-content-between gap-3"
						   data-submenu="users"
						   data-bs-toggle="collapse"
						   data-bs-target="#sidebarUsersMenu"
						   aria-expanded="{{ $usersMenuActive ? 'true' : 'false' }}"
						   aria-controls="sidebarUsersMenu">
							<span class="d-inline-flex align-items-center gap-3">
								<iconify-icon icon="solar:user-id-linear" class="menu-icon"></iconify-icon>
								<span>Users</span>
							</span>
							<iconify-icon icon="solar:alt-arrow-down-outline" class="submenu-arrow {{ $usersMenuActive ? 'rotate-180' : '' }}"></iconify-icon>
						</a>
						<ul class="sidebar-submenu collapse {{ $usersMenuActive ? 'show' : '' }}" id="sidebarUsersMenu">
							<li>
								<a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.index') || request()->routeIs('admin.users.create') || request()->routeIs('admin.users.edit') || request()->routeIs('admin.users.*-activity') || request()->routeIs('admin.users.customer-details') ? 'active' : '' }}">
									<iconify-icon icon="solar:users-group-two-rounded-outline" class="text-primary-main"></iconify-icon>
									Manage Users
								</a>
							</li>
							<li>
								<a href="{{ route('admin.users.assign-modules') }}" class="{{ request()->routeIs('admin.users.assign-modules') || request()->routeIs('admin.users.update-modules') ? 'active' : '' }}">
									<iconify-icon icon="solar:settings-linear" class="text-info-main"></iconify-icon>
									Assign Modules to Staff
								</a>
							</li>
						</ul>
					</li>
					<li class="{{ $blogMenuActive ? 'active' : '' }}">
						<a href="javascript:void(0)"
						   class="submenu-toggle"
						   data-submenu="blog"
						   data-bs-toggle="collapse"
						   data-bs-target="#sidebarBlogMenu"
						   aria-expanded="{{ $blogMenuActive ? 'true' : 'false' }}"
						   aria-controls="sidebarBlogMenu">
							<span class="d-inline-flex align-items-center gap-3">
								<iconify-icon icon="solar:document-text-outline" class="menu-icon"></iconify-icon>
								<span>Blogs</span>
							</span>
							<iconify-icon icon="solar:alt-arrow-down-outline" class="submenu-arrow {{ $blogMenuActive ? 'rotate-180' : '' }}"></iconify-icon>
						</a>
						<ul class="sidebar-submenu collapse {{ $blogMenuActive ? 'show' : '' }}" id="sidebarBlogMenu">
							<li>
								<a href="{{ route('admin.blog.posts.index') }}" class="{{ request()->routeIs('admin.blog.posts.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:document-add-outline" class="text-primary-main"></iconify-icon>
									All Posts
								</a>
							</li>
							<li>
								<a href="{{ route('admin.blog.categories.index') }}" class="{{ request()->routeIs('admin.blog.categories.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:folders-outline" class="text-info-main"></iconify-icon>
									Categories
								</a>
							</li>
							<li>
								<a href="{{ route('admin.blog.tags.index') }}" class="{{ request()->routeIs('admin.blog.tags.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:hashtag-circle-outline" class="text-warning"></iconify-icon>
									Tags
								</a>
							</li>
						</ul>
					</li>
					<li class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
						<a href="{{ route('admin.orders.index') }}">
							<iconify-icon icon="mdi:clipboard-text-outline" class="menu-icon"></iconify-icon>
							<span>Orders</span>
						</a>
					</li>
					<li class="{{ $affiliateMenuActive ? 'active' : '' }}">
						<a href="javascript:void(0)"
						   class="submenu-toggle d-flex align-items-center justify-content-between gap-3"
						   data-submenu="affiliates"
						   data-bs-toggle="collapse"
						   data-bs-target="#sidebarAffiliatesMenu"
						   aria-expanded="{{ $affiliateMenuActive ? 'true' : 'false' }}"
						   aria-controls="sidebarAffiliatesMenu">
							<span class="d-inline-flex align-items-center gap-3">
								<iconify-icon icon="solar:hand-stars-outline" class="menu-icon"></iconify-icon>
								<span>Affiliates</span>
							</span>
							<iconify-icon icon="solar:alt-arrow-down-outline" class="submenu-arrow {{ $affiliateMenuActive ? 'rotate-180' : '' }}"></iconify-icon>
						</a>
						<ul class="sidebar-submenu collapse {{ $affiliateMenuActive ? 'show' : '' }}" id="sidebarAffiliatesMenu">
							<li>
								<a href="{{ route('admin.affiliates.index') }}" class="{{ request()->routeIs('admin.affiliates.index') || request()->routeIs('admin.affiliates.create') || request()->routeIs('admin.affiliates.edit') ? 'active' : '' }}">
									<iconify-icon icon="solar:user-id-linear" class="text-primary-main"></iconify-icon>
									Manage Affiliates
								</a>
							</li>
							<li>
								<a href="{{ route('admin.affiliates.commissions.index') }}" class="{{ request()->routeIs('admin.affiliates.commissions.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:dollar-minimalistic-linear" class="text-info-main"></iconify-icon>
									Commissions
								</a>
							</li>
							<li>
								<a href="{{ route('admin.affiliates.payouts.index') }}" class="{{ request()->routeIs('admin.affiliates.payouts.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:wallet-money-outline" class="text-success"></iconify-icon>
									Payouts
								</a>
							</li>
						</ul>
					</li>
					<li class="{{ $supportMenuActive ? 'active' : '' }}">
						<a href="javascript:void(0)"
						   class="submenu-toggle d-flex align-items-center justify-content-between gap-3"
						   data-submenu="support"
						   data-bs-toggle="collapse"
						   data-bs-target="#sidebarSupportMenu"
						   aria-expanded="{{ $supportMenuActive ? 'true' : 'false' }}"
						   aria-controls="sidebarSupportMenu">
							<span class="d-inline-flex align-items-center gap-3">
								<iconify-icon icon="solar:chat-dots-outline" class="menu-icon"></iconify-icon>
								<span>Support</span>
							</span>
							<iconify-icon icon="solar:alt-arrow-down-outline" class="submenu-arrow {{ $supportMenuActive ? 'rotate-180' : '' }}"></iconify-icon>
						</a>
						<ul class="sidebar-submenu collapse {{ $supportMenuActive ? 'show' : '' }}" id="sidebarSupportMenu">
							<li>
								<a href="{{ route('admin.support.tickets.index') }}" class="{{ request()->routeIs('admin.support.tickets.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:chat-dots-outline" class="text-primary-main"></iconify-icon>
									Tickets
								</a>
							</li>
							<li>
								<a href="{{ route('admin.support.categories.index') }}" class="{{ request()->routeIs('admin.support.categories.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:folder-outline" class="text-info-main"></iconify-icon>
									Categories
								</a>
							</li>
							<li>
								<a href="{{ route('admin.support.settings.edit') }}" class="{{ request()->routeIs('admin.support.settings.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:settings-linear" class="text-warning"></iconify-icon>
									Settings
								</a>
							</li>
						</ul>
					</li>
					<li class="{{ $customerMenuActive ? 'active' : '' }}">
						<a href="{{ route('admin.customers.index') }}">
							<iconify-icon icon="solar:users-group-two-rounded-outline" class="menu-icon"></iconify-icon>
							<span>Customers</span>
						</a>
					</li>
					<li class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
						<a href="{{ route('admin.reviews.index') }}">
							<iconify-icon icon="solar:star-outline" class="menu-icon"></iconify-icon>
							<span>Reviews</span>
						</a>
					</li>
					<li class="{{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
						<a href="{{ route('admin.comments.index') }}">
							<iconify-icon icon="solar:chat-round-outline" class="menu-icon"></iconify-icon>
							<span>Comments</span>
						</a>
					</li>
					<li class="{{ $marketingMenuActive ? 'active' : '' }}">
						<a href="javascript:void(0)"
						   class="submenu-toggle d-flex align-items-center justify-content-between gap-3"
						   data-submenu="marketing"
						   data-bs-toggle="collapse"
						   data-bs-target="#sidebarMarketingMenu"
						   aria-expanded="{{ $marketingMenuActive ? 'true' : 'false' }}"
						   aria-controls="sidebarMarketingMenu">
							<span class="d-inline-flex align-items-center gap-3">
								<iconify-icon icon="solar:megaphone-outline" class="menu-icon"></iconify-icon>
								<span>Marketing</span>
							</span>
							<iconify-icon icon="solar:alt-arrow-down-outline" class="submenu-arrow {{ $marketingMenuActive ? 'rotate-180' : '' }}"></iconify-icon>
						</a>
						<ul class="sidebar-submenu collapse {{ $marketingMenuActive ? 'show' : '' }}" id="sidebarMarketingMenu">
							<li>
								<a href="{{ route('admin.marketing.templates.index') }}" class="{{ request()->routeIs('admin.marketing.templates.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:document-text-outline" class="text-primary-main"></iconify-icon>
									Templates
								</a>
							</li>
							<li>
								<a href="{{ route('admin.marketing.campaigns.index') }}" class="{{ request()->routeIs('admin.marketing.campaigns.*') && !request()->routeIs('admin.marketing.drip-campaigns.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:chart-outline" class="text-info-main"></iconify-icon>
									Campaigns
								</a>
							</li>
							<li>
								<a href="{{ route('admin.marketing.drip-campaigns.index') }}" class="{{ request()->routeIs('admin.marketing.drip-campaigns.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:water-drop-outline" class="text-success"></iconify-icon>
									Drip Campaigns
								</a>
							</li>
						</ul>
					</li>
					<li class="sidebar-menu-title mt-3">Sales</li>
					<li class="{{ $leadMenuActive ? 'active' : '' }}">
						<a href="{{ route('admin.leads.index') }}">
							<iconify-icon icon="solar:user-check-linear" class="menu-icon"></iconify-icon>
							<span>Lead Management</span>
						</a>
					</li>
					<li class="{{ request()->routeIs('admin.lead-sources.*') ? 'active' : '' }}">
						<a href="{{ route('admin.lead-sources.index') }}">
							<iconify-icon icon="solar:link-circle-outline" class="menu-icon"></iconify-icon>
							<span>Lead Source</span>
						</a>
					</li>
					<li class="{{ request()->routeIs('admin.lead-stages.*') ? 'active' : '' }}">
						<a href="{{ route('admin.lead-stages.index') }}">
							<iconify-icon icon="solar:layers-outline" class="menu-icon"></iconify-icon>
							<span>Lead Stage</span>
						</a>
					</li>
					<li class="{{ $quotationMenuActive ? 'active' : '' }}">
						<a href="javascript:void(0)"
						   class="submenu-toggle d-flex align-items-center justify-content-between gap-3"
						   data-submenu="quotations"
						   data-bs-toggle="collapse"
						   data-bs-target="#sidebarQuotationsMenu"
						   aria-expanded="{{ $quotationMenuActive ? 'true' : 'false' }}"
						   aria-controls="sidebarQuotationsMenu">
							<span class="d-inline-flex align-items-center gap-3">
								<iconify-icon icon="solar:document-text-linear" class="menu-icon"></iconify-icon>
								<span>Quotations</span>
							</span>
							<iconify-icon icon="solar:alt-arrow-down-outline" class="submenu-arrow {{ $quotationMenuActive ? 'rotate-180' : '' }}"></iconify-icon>
						</a>
						<ul class="sidebar-submenu collapse {{ $quotationMenuActive ? 'show' : '' }}" id="sidebarQuotationsMenu">
							<li>
								<a href="{{ route('admin.quotations.create') }}" class="{{ request()->routeIs('admin.quotations.create') ? 'active' : '' }}">
									<iconify-icon icon="solar:add-circle-linear" class="text-primary-main"></iconify-icon>
									Add Quotation
								</a>
							</li>
							<li>
								<a href="{{ route('admin.quotations.index') }}" class="{{ request()->routeIs('admin.quotations.index') || request()->routeIs('admin.quotations.edit') || request()->routeIs('admin.quotations.show') ? 'active' : '' }}">
									<iconify-icon icon="solar:list-check-outline" class="text-info-main"></iconify-icon>
									Manage Quotations
								</a>
							</li>
						</ul>
					</li>
					<li class="{{ $enquiryMenuActive ? 'active' : '' }}">
						<a href="javascript:void(0)"
						   class="submenu-toggle d-flex align-items-center justify-content-between gap-3"
						   data-submenu="enquiries"
						   data-bs-toggle="collapse"
						   data-bs-target="#sidebarEnquiriesMenu"
						   aria-expanded="{{ $enquiryMenuActive ? 'true' : 'false' }}"
						   aria-controls="sidebarEnquiriesMenu">
							<span class="d-inline-flex align-items-center gap-3">
								<iconify-icon icon="solar:question-circle-outline" class="menu-icon"></iconify-icon>
								<span>Enquiries</span>
							</span>
							<iconify-icon icon="solar:alt-arrow-down-outline" class="submenu-arrow {{ $enquiryMenuActive ? 'rotate-180' : '' }}"></iconify-icon>
						</a>
						<ul class="sidebar-submenu collapse {{ $enquiryMenuActive ? 'show' : '' }}" id="sidebarEnquiriesMenu">
							<li>
								<a href="{{ route('admin.enquiries.index') }}" class="{{ request()->routeIs('admin.enquiries.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:list-check-outline" class="text-info-main"></iconify-icon>
									Manage Enquiries
								</a>
							</li>
						</ul>
					</li>
					<li class="{{ request()->routeIs('admin.marketing.campaigns.*') ? 'active' : '' }}">
						<a href="{{ route('admin.marketing.campaigns.index') }}">
							<iconify-icon icon="solar:megaphone-outline" class="menu-icon"></iconify-icon>
							<span>Drip Campaigns</span>
						</a>
					</li>
					<li class="{{ $offerMenuActive ? 'active' : '' }}">
						<a href="javascript:void(0)"
						   class="submenu-toggle d-flex align-items-center justify-content-between gap-3"
						   data-submenu="offers"
						   data-bs-toggle="collapse"
						   data-bs-target="#sidebarOfferMenu"
						   aria-expanded="{{ $offerMenuActive ? 'true' : 'false' }}"
						   aria-controls="sidebarOfferMenu">
							<span class="d-inline-flex align-items-center gap-3">
								<iconify-icon icon="solar:gift-outline" class="menu-icon"></iconify-icon>
								<span>Offers</span>
							</span>
							<iconify-icon icon="solar:alt-arrow-down-outline" class="submenu-arrow {{ $offerMenuActive ? 'rotate-180' : '' }}"></iconify-icon>
						</a>
						<ul class="sidebar-submenu collapse {{ $offerMenuActive ? 'show' : '' }}" id="sidebarOfferMenu">
							<li>
								<a href="{{ route('admin.offers.create') }}" class="{{ request()->routeIs('admin.offers.create') ? 'active' : '' }}">
									<iconify-icon icon="solar:add-circle-linear" class="text-primary-main"></iconify-icon>
									Create Offer
								</a>
							</li>
							<li>
								<a href="{{ route('admin.offers.index') }}" class="{{ request()->routeIs('admin.offers.index') || request()->routeIs('admin.offers.edit') ? 'active' : '' }}">
									<iconify-icon icon="solar:list-check-outline" class="text-info-main"></iconify-icon>
									Manage Offers
								</a>
							</li>
						</ul>
					</li>
					<li class="{{ $invoiceMenuActive ? 'active' : '' }}">
						<a href="javascript:void(0)"
						   class="submenu-toggle d-flex align-items-center justify-content-between gap-3"
						   data-submenu="invoices"
						   data-bs-toggle="collapse"
						   data-bs-target="#sidebarInvoiceMenu"
						   aria-expanded="{{ $invoiceMenuActive ? 'true' : 'false' }}"
						   aria-controls="sidebarInvoiceMenu">
							<span class="d-inline-flex align-items-center gap-3">
								<iconify-icon icon="solar:receipt-outline" class="menu-icon"></iconify-icon>
								<span>Invoices</span>
							</span>
							<iconify-icon icon="solar:alt-arrow-down-outline" class="submenu-arrow {{ $invoiceMenuActive ? 'rotate-180' : '' }}"></iconify-icon>
						</a>
						<ul class="sidebar-submenu collapse {{ $invoiceMenuActive ? 'show' : '' }}" id="sidebarInvoiceMenu">
							<li>
								<a href="{{ route('admin.invoices.templates.index') }}" class="{{ request()->routeIs('admin.invoices.templates.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:document-text-outline" class="text-primary-main"></iconify-icon>
									Invoice Template
								</a>
							</li>
							<li>
								<a href="{{ route('admin.invoices.invoices.index') }}" class="{{ request()->routeIs('admin.invoices.invoices.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:receipt-check-outline" class="text-info-main"></iconify-icon>
									Manage Invoices
								</a>
							</li>
						</ul>
					</li>
					<li class="{{ $notificationMenuActive ? 'active' : '' }}">
						<a href="{{ route('notifications.index') }}">
							<iconify-icon icon="solar:bell-outline" class="menu-icon"></iconify-icon>
							<span>Notifications</span>
							@php
								$unreadCount = auth()->user()->unreadNotifications()->count();
							@endphp
							@if($unreadCount > 0)
								<span class="badge bg-danger ms-2">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
							@endif
						</a>
					</li>
					<li class="{{ $settingsMenuActive ? 'active' : '' }}">
						<a href="javascript:void(0)"
						   class="submenu-toggle d-flex align-items-center justify-content-between gap-3"
						   data-submenu="settings"
						   data-bs-toggle="collapse"
						   data-bs-target="#sidebarSettingsMenu"
						   aria-expanded="{{ $settingsMenuActive ? 'true' : 'false' }}"
						   aria-controls="sidebarSettingsMenu">
							<span class="d-inline-flex align-items-center gap-3">
								<iconify-icon icon="solar:settings-linear" class="menu-icon"></iconify-icon>
								<span>Settings</span>
							</span>
							<iconify-icon icon="solar:alt-arrow-down-outline" class="submenu-arrow {{ $settingsMenuActive ? 'rotate-180' : '' }}"></iconify-icon>
						</a>
						<ul class="sidebar-submenu collapse {{ $settingsMenuActive ? 'show' : '' }}" id="sidebarSettingsMenu">
							<li>
								<a href="{{ route('admin.settings.general') }}" class="{{ request()->routeIs('admin.settings.general') ? 'active' : '' }}">
									<iconify-icon icon="solar:widget-4-outline" class="text-primary-main"></iconify-icon>
									General
								</a>
							</li>
							<li>
								<a href="{{ route('admin.settings.seo') }}" class="{{ request()->routeIs('admin.settings.seo*') ? 'active' : '' }}">
									<iconify-icon icon="solar:shield-check-outline" class="text-info-main"></iconify-icon>
									SEO Tools
								</a>
							</li>
							<li>
								<a href="{{ route('admin.integrations.index') }}" class="{{ request()->routeIs('admin.integrations.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:usb-outline" class="text-warning"></iconify-icon>
									Integrations
								</a>
							</li>
							<li>
								<a href="{{ route('admin.tax-classes.index') }}" class="{{ request()->routeIs('admin.tax-classes.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:receipt-outline" class="text-danger"></iconify-icon>
									Tax Classes
								</a>
							</li>
							<li>
								<a href="{{ route('admin.shipping-methods.index') }}" class="{{ request()->routeIs('admin.shipping-methods.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:delivery-outline" class="text-success"></iconify-icon>
									Shipping Methods
								</a>
							</li>
							<li>
								<a href="{{ route('admin.settings.locations.index') }}" class="{{ request()->routeIs('admin.settings.locations.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:map-point-outline" class="text-primary"></iconify-icon>
									Location Manager
								</a>
							</li>
							<li>
								<a href="{{ route('admin.documentation.index') }}" class="{{ request()->routeIs('admin.documentation.*') ? 'active' : '' }}">
									<iconify-icon icon="solar:document-text-outline" class="text-info"></iconify-icon>
									Documentation
								</a>
							</li>
						</ul>
					</li>
					<li class="sidebar-menu-title mt-3">API Access</li>
					<li class="{{ request()->routeIs('admin.api.documentation') || request()->routeIs('admin.api.docs') ? 'active' : '' }}">
						<a href="{{ route('admin.api.documentation') }}">
							<iconify-icon icon="solar:document-linear" class="menu-icon"></iconify-icon>
							<span>Documentation</span>
						</a>
					</li>
					<li class="{{ request()->routeIs('admin.api-tokens.*') ? 'active' : '' }}">
						<a href="{{ route('admin.api-tokens.index') }}">
							<iconify-icon icon="solar:key-outline" class="menu-icon"></iconify-icon>
							<span>Manage Tokens</span>
						</a>
					</li>
					<li class="{{ request()->routeIs('admin.api.webhooks.*') && !request()->routeIs('admin.api.webhook-logs.*') ? 'active' : '' }}">
						<a href="{{ route('admin.api.webhooks.index') }}">
							<iconify-icon icon="solar:webhook-outline" class="menu-icon"></iconify-icon>
							<span>Webhooks</span>
						</a>
					</li>
					<li class="{{ request()->routeIs('admin.api.webhook-logs.*') ? 'active' : '' }}">
						<a href="{{ route('admin.api.webhook-logs.index') }}">
							<iconify-icon icon="solar:file-text-outline" class="menu-icon"></iconify-icon>
							<span>Webhook Logs</span>
						</a>
					</li>
					<li class="{{ request()->routeIs('admin.api.test-console') ? 'active' : '' }}">
						<a href="{{ route('admin.api.test-console') }}">
							<iconify-icon icon="solar:code-square-outline" class="menu-icon"></iconify-icon>
							<span>Test API</span>
						</a>
					</li>
					<li class="{{ request()->routeIs('admin.api.usage-statistics') ? 'active' : '' }}">
						<a href="{{ route('admin.api.usage-statistics') }}">
							<iconify-icon icon="solar:chart-2-outline" class="menu-icon"></iconify-icon>
							<span>Usage Statistics</span>
						</a>
					</li>
				</ul>
			</div>
			<div class="sidebar-profile">
				<div class="sidebar-profile-top">
					<div class="profile-thumb">
						@if($adminUser)
							<x-avatar :user="$adminUser" size="md" class="w-100 h-100" />
						@else
							<img src="{{ asset('wowdash/assets/images/avatar/avatar-1.png') }}" alt="profile">
						@endif
					</div>
					<h6 class="name mb-0">{{ $adminUser?->name ?? 'Admin' }}</h6>
					<span class="designation">Administrator</span>
				</div>
				<a href="{{ route('logout') }}" class="sidebar-logout" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
					<iconify-icon icon="solar:logout-2-outline"></iconify-icon>
					<span>Logout</span>
				</a>
				<form id="admin-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
					@csrf
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
							<form class="navbar-search d-none d-md-flex">
								<input type="text" name="search" placeholder="Search">
								<iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
							</form>
						</div>
					</div>
					<div class="col-auto">
						<div class="d-flex flex-wrap align-items-center gap-3">
							<button class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center" type="button" data-theme-toggle></button>
						<div class="dropdown">
							<button class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center position-relative" type="button" data-bs-toggle="dropdown" id="notificationDropdown">
								<iconify-icon icon="solar:bell-outline" class="text-primary-light text-xl"></iconify-icon>
								<span class="notification-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none" id="notificationBadge" style="font-size: 0.65rem;">0</span>
							</button>
							<div class="dropdown-menu to-top dropdown-menu-lg p-0" id="notificationMenu">
								<div class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
									<div>
										<h6 class="text-lg text-primary-light fw-semibold mb-0">Notifications</h6>
									</div>
									<span class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center" id="notificationCount">0</span>
								</div>
								<div class="max-h-400-px overflow-y-auto scroll-sm pe-4" id="notificationList">
									<div class="text-center py-32 text-secondary-light">
										<iconify-icon icon="solar:bell-off-outline" class="text-3xl mb-2"></iconify-icon>
										<p class="mb-0">No notifications</p>
									</div>
								</div>
								<div class="d-flex border-top">
									<a href="{{ route('notifications.index') }}" class="flex-grow-1 text-center py-12 text-primary-600 fw-semibold">View all</a>
									<a href="#" class="flex-grow-1 text-center py-12 text-primary-600 fw-semibold border-start" id="markAllReadBtn">Mark all read</a>
								</div>
							</div>
						</div>
						<div class="dropdown">
							<button class="btn btn-link text-decoration-none p-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" style="box-shadow:none;background:none;border:0;">
								@if($adminUser)
									<x-avatar :user="$adminUser" size="md" class="w-40-px h-40-px" />
								@else
									<img src="{{ asset('wowdash/assets/images/avatar/avatar-1.png') }}" alt="avatar" class="w-40-px h-40-px rounded-circle object-fit-cover">
								@endif
							</button>
							<div class="dropdown-menu dropdown-menu-end profile-dropdown shadow border-0 radius-12 p-0 overflow-hidden">
								<div class="p-24 bg-neutral-100">
									<div class="d-flex align-items-center gap-3">
										@if($adminUser)
											<x-avatar :user="$adminUser" size="lg" class="w-56-px h-56-px" />
										@else
											<img src="{{ asset('wowdash/assets/images/avatar/avatar-1.png') }}" alt="avatar" class="w-56-px h-56-px rounded-circle object-fit-cover">
										@endif
										<div>
											<h6 class="mb-0 text-md fw-semibold">{{ $adminUser?->name ?? 'Admin' }}</h6>
											<span class="text-sm text-secondary-light">{{ $adminUser?->email ?? 'admin@example.com' }}</span>
										</div>
									</div>
								</div>
								<div class="list-group list-group-flush">
									<a href="{{ route('admin.profile') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2">
										<iconify-icon icon="solar:user-circle-outline" class="text-lg"></iconify-icon>
										View Profile
									</a>
									<a href="{{ route('admin.settings.general') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2">
										<iconify-icon icon="solar:settings-outline" class="text-lg"></iconify-icon>
										Settings
									</a>
								</div>
								<div class="border-top">
									<a class="dropdown-item text-danger py-12 d-flex align-items-center gap-2" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
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

				@yield('content')

			<footer class="footer mt-32">
				<div class="container-fluid">
					<div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
						<p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
						<div class="d-flex gap-3">
							<a href="#" class="text-secondary-light">Privacy Policy</a>
							<a href="#" class="text-secondary-light">Terms</a>
							<a href="#" class="text-secondary-light">Help</a>
						</div>
		</div>
	</div>
			</footer>
		</main>

		<script src="{{ asset('wowdash/assets/js/lib/jquery-3.7.1.min.js') }}"></script>
		<script src="{{ asset('wowdash/assets/js/lib/bootstrap.bundle.min.js') }}"></script>
		<script src="{{ asset('wowdash/assets/js/lib/apexcharts.min.js') }}"></script>
		<script src="{{ asset('wowdash/assets/js/lib/dataTables.min.js') }}"></script>
		<script src="{{ asset('wowdash/assets/js/lib/iconify-icon.min.js') }}"></script>
		<script src="{{ asset('wowdash/assets/js/lib/jquery-ui.min.js') }}"></script>
		<script src="{{ asset('wowdash/assets/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
		<script src="{{ asset('wowdash/assets/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
		<script src="{{ asset('wowdash/assets/js/lib/magnifc-popup.min.js') }}"></script>
		<script src="{{ asset('wowdash/assets/js/lib/slick.min.js') }}"></script>
		<script src="{{ asset('wowdash/assets/js/lib/prism.js') }}"></script>
		<script src="{{ asset('wowdash/assets/js/lib/file-upload.js') }}"></script>
		<script src="{{ asset('wowdash/assets/js/lib/audioplayer.js') }}"></script>
		<script src="{{ asset('wowdash/assets/js/app.js') }}"></script>
		<script>
			document.addEventListener('DOMContentLoaded', function () {
				if (typeof bootstrap === 'undefined') {
					return;
				}

				const setupSubmenu = (toggle) => {
					const menuId = toggle.getAttribute('data-bs-target')?.replace('#', '');
					if (!menuId) {
						return null;
					}
					const menu = document.getElementById(menuId);
					if (!menu) {
						return null;
					}

					const arrow = toggle.querySelector('.submenu-arrow');
					const collapseInstance = bootstrap.Collapse.getOrCreateInstance(menu, { toggle: false });

					const setExpanded = (expanded) => {
						toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
						if (expanded) {
							arrow?.classList.add('rotate-180');
							toggle.classList.add('active');
							menu.style.height = '';
							menu.style.display = '';
							menu.classList.add('show');
						} else {
							arrow?.classList.remove('rotate-180');
							const stayActive = toggle.dataset.submenu === 'blog'
								? @json($blogMenuActive)
								: (toggle.dataset.submenu === 'catalog' ? @json($catalogMenuActive) : false);
							if (!stayActive) {
								toggle.classList.remove('active');
							}
							menu.classList.remove('show', 'collapsing');
							menu.style.height = '';
							menu.style.display = 'none';
						}
					};

					if (menu.classList.contains('show')) {
						setExpanded(true);
					} else {
						setExpanded(false);
					}

					const forceCollapse = () => {
						try {
							collapseInstance.hide();
						} catch (e) {
							/* ignore */
						}
						setExpanded(false);
					};

					menu.addEventListener('shown.bs.collapse', () => setExpanded(true));
					menu.addEventListener('hidden.bs.collapse', () => setExpanded(false));

					toggle.addEventListener('click', (event) => {
						event.preventDefault();
						const isExpanded = menu.classList.contains('show') || menu.style.display === '';
						if (isExpanded) {
							forceCollapse();
						} else {
							setExpanded(true);
							try {
								collapseInstance.show();
							} catch (e) {
								menu.classList.add('show');
								menu.style.display = '';
							}
						}
					});

					return { toggle, menu, forceCollapse };
				};

				const submenuConfigs = Array.from(document.querySelectorAll('.submenu-toggle'))
					.map(setupSubmenu)
					.filter(Boolean);

				document.querySelectorAll('#sidebar-menu > li > a').forEach((link) => {
					link.addEventListener('click', function () {
						submenuConfigs.forEach(({ forceCollapse }) => forceCollapse());
					});
				});
			});
		</script>
		<script>
			// Notification System
			(function() {
				const notificationBadge = document.getElementById('notificationBadge');
				const notificationCount = document.getElementById('notificationCount');
				const notificationList = document.getElementById('notificationList');
				const markAllReadBtn = document.getElementById('markAllReadBtn');

				function loadNotifications() {
					fetch('{{ route("notifications.recent") }}', {
						headers: {
							'X-Requested-With': 'XMLHttpRequest',
							'Accept': 'application/json'
						}
					})
					.then(response => response.json())
					.then(data => {
						updateBadge(data.unread_count);
						updateNotificationList(data.notifications);
					})
					.catch(error => console.error('Error loading notifications:', error));
				}

				function updateBadge(count) {
					if (count > 0) {
						notificationBadge.textContent = count > 99 ? '99+' : count;
						notificationBadge.classList.remove('d-none');
					} else {
						notificationBadge.classList.add('d-none');
					}
					notificationCount.textContent = count;
				}

				function updateNotificationList(notifications) {
					if (notifications.length === 0) {
						notificationList.innerHTML = `
							<div class="text-center py-32 text-secondary-light">
								<iconify-icon icon="solar:bell-off-outline" class="text-3xl mb-2"></iconify-icon>
								<p class="mb-0">No notifications</p>
							</div>
						`;
						return;
					}

					notificationList.innerHTML = notifications.map(notif => {
						const isRead = notif.read_at ? '' : 'bg-primary-50';
						const iconColorClass = `text-${notif.icon_color}-main`;
						const iconBgClass = `bg-${notif.icon_color}-focus`;
						
						return `
							<div class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between ${isRead} notification-item" data-id="${notif.id}">
								<a href="${notif.url}" class="d-flex align-items-center gap-3 text-decoration-none flex-grow-1" onclick="markNotificationAsRead('${notif.id}')">
									<span class="w-40-px h-40-px rounded-circle flex-shrink-0 ${iconBgClass} d-flex justify-content-center align-items-center">
										<iconify-icon icon="${notif.icon}" class="${iconColorClass} text-xl"></iconify-icon>
									</span>
									<div>
										<h6 class="text-md fw-semibold mb-4">${notif.title}</h6>
										<p class="mb-0 text-sm text-secondary-light">${notif.message}</p>
										<p class="mb-0 text-xs text-secondary-light mt-1">${notif.created_at}</p>
									</div>
								</a>
							</div>
						`;
					}).join('');
				}

				function markNotificationAsRead(id) {
					fetch(`/notifications/${id}/read`, {
						method: 'POST',
						headers: {
							'X-Requested-With': 'XMLHttpRequest',
							'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
							'Accept': 'application/json'
						}
					})
					.then(response => response.json())
					.then(data => {
						if (data.success) {
							loadNotifications();
						}
					});
				}

				if (markAllReadBtn) {
					markAllReadBtn.addEventListener('click', function(e) {
						e.preventDefault();
						fetch('{{ route("notifications.mark-all-read") }}', {
							method: 'POST',
							headers: {
								'X-Requested-With': 'XMLHttpRequest',
								'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
								'Accept': 'application/json'
							}
						})
						.then(response => response.json())
						.then(data => {
							if (data.success) {
								loadNotifications();
							}
						});
					});
				}

				// Load notifications on page load
				loadNotifications();

				// Poll for new notifications every 30 seconds
				setInterval(loadNotifications, 30000);

				// Reload when dropdown is opened
				const dropdown = document.getElementById('notificationDropdown');
				if (dropdown) {
					dropdown.addEventListener('click', function() {
						loadNotifications();
					});
				}

				// Make markNotificationAsRead available globally
				window.markNotificationAsRead = markNotificationAsRead;
			})();
		</script>
		@stack('scripts')
</body>
</html>
