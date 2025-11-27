<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Admin\BlogCategoryController as AdminBlogCategoryController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\BlogTagController as AdminBlogTagController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SeoToolController as AdminSeoToolController;
use App\Http\Controllers\Admin\IntegrationController as AdminIntegrationController;
use App\Http\Controllers\Admin\SupportTicketController as AdminSupportTicketController;
use App\Http\Controllers\Admin\SupportSettingController as AdminSupportSettingController;
use App\Http\Controllers\Admin\HomeSliderController as AdminHomeSliderController;
use App\Http\Controllers\Admin\HeaderToolbarController as AdminHeaderToolbarController;
use App\Http\Controllers\Admin\LegalPageController as AdminLegalPageController;
use App\Http\Controllers\Admin\AboutPageController as AdminAboutPageController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\AddressController as CustomerAddressController;
use App\Http\Controllers\Customer\SupportTicketController as CustomerSupportTicketController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Admin\LeadActivityController;
use App\Http\Controllers\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\Admin\LeadFollowupController;
use App\Http\Controllers\Admin\QuotationController as AdminQuotationController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\SupportCategoryController as AdminSupportCategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Static Pages
Route::get('/about-us', [AboutController::class, 'index'])->name('about');
Route::view('/contact-us', 'pages.contact')->name('contact');
Route::view('/privacy-policy', 'pages.privacy')->name('privacy');
Route::view('/terms-and-conditions', 'pages.terms')->name('terms');
Route::view('/refund-and-return-policy', 'pages.refund')->name('refund');
Route::view('/wishlist', 'pages.wishlist')->name('wishlist.index');
Route::view('/compare', 'pages.compare')->name('compare.index');
Route::view('/social-hub', 'pages.social-hub')->name('social-hub');
Route::get('/affiliate', [\App\Http\Controllers\AffiliateController::class, 'index'])->name('affiliate.index');

// Blog routes
Route::prefix('blog')->group(function () {
	Route::get('/', [BlogController::class, 'index'])->name('blog.index');
	Route::get('/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
	Route::get('/{slug}', [BlogController::class, 'show'])->name('blog.show');
	Route::post('/{post}/comments', [\App\Http\Controllers\BlogCommentController::class, 'store'])->name('blog.comments.store');
});

// Product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::post('/enquiries', [EnquiryController::class, 'store'])
    ->middleware('auth')
    ->name('enquiries.store');
Route::get('/category/{category}', [ProductController::class, 'category'])->name('products.category');
Route::get('/tag/{tag}', [ProductController::class, 'tag'])->name('products.tag');
Route::post('/products/{product}/reviews', [\App\Http\Controllers\ProductReviewController::class, 'store'])->name('products.reviews.store');

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Coupon routes
Route::post('/coupons/validate', [\App\Http\Controllers\CouponController::class, 'validateCoupon'])->name('coupons.validate');
Route::post('/coupons/remove', [\App\Http\Controllers\CouponController::class, 'remove'])->name('coupons.remove');

// Saudi Arabia Location API routes
Route::get('/api/saudi-arabia/states', [\App\Http\Controllers\SaudiArabiaLocationController::class, 'states'])->name('api.saudi-arabia.states');
Route::get('/api/saudi-arabia/cities', [\App\Http\Controllers\SaudiArabiaLocationController::class, 'cities'])->name('api.saudi-arabia.cities');
Route::get('/api/saudi-arabia/search', [\App\Http\Controllers\SaudiArabiaLocationController::class, 'search'])->name('api.saudi-arabia.search');
Route::get('/api/saudi-arabia/postal-codes', [\App\Http\Controllers\SaudiArabiaLocationController::class, 'postalCodes'])->name('api.saudi-arabia.postal-codes');

// India Location API routes (using same controller/model)
Route::get('/api/india/states', [\App\Http\Controllers\SaudiArabiaLocationController::class, 'states'])->name('api.india.states');
Route::get('/api/india/cities', [\App\Http\Controllers\SaudiArabiaLocationController::class, 'cities'])->name('api.india.cities');
Route::get('/api/india/search', [\App\Http\Controllers\SaudiArabiaLocationController::class, 'search'])->name('api.india.search');
Route::get('/api/india/postal-codes', [\App\Http\Controllers\SaudiArabiaLocationController::class, 'postalCodes'])->name('api.india.postal-codes');

// WhatsApp Meta Webhook (public route for Meta to verify and send events)
Route::match(['get', 'post'], '/webhook/whatsapp', [\App\Http\Controllers\WhatsAppWebhookController::class, 'handle'])->name('webhook.whatsapp');
Route::get('/webhook/whatsapp/verify', [\App\Http\Controllers\WhatsAppWebhookController::class, 'verify'])->name('webhook.whatsapp.verify');

// Order routes (require authentication - no guest checkout)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    
    // WhatsApp Checkout Routes
    Route::prefix('whatsapp')->name('whatsapp.')->group(function () {
        Route::get('/checkout/initiate', [\App\Http\Controllers\WhatsAppCheckoutController::class, 'initiate'])->name('checkout.initiate');
        Route::get('/product/{product}/link', [\App\Http\Controllers\WhatsAppCheckoutController::class, 'getProductLink'])->name('product.link');
        Route::post('/webhook', [\App\Http\Controllers\WhatsAppCheckoutController::class, 'webhook'])->name('webhook');
    });
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
});
// Order viewing routes are now protected - use customer.orders routes instead

// Minimal Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest')->name('login.perform');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->middleware('guest')->name('register');
Route::post('/register', [RegisterController::class, 'register'])->middleware('guest')->name('register.perform');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->middleware('guest')->name('password.update');

// Email verification routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('customer.dashboard')->with('success', 'Your email has been verified!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Dashboards & Admin
Route::middleware('auth')->group(function () {
	Route::get('/dashboard', function () {
		return auth()->user()->is_admin
			? redirect()->route('admin.dashboard')
			: redirect()->route('customer.dashboard');
	})->name('dashboard');

	Route::prefix('notifications')->name('notifications.')->middleware('auth')->group(function () {
		Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])->name('index');
		Route::get('/recent', [\App\Http\Controllers\NotificationController::class, 'recent'])->name('recent');
		Route::get('/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('unread-count');
		Route::post('/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('read');
		Route::post('/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
		Route::delete('/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
	});

	Route::prefix('customer')->name('customer.')->group(function () {
		Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

		Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
		Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');

		Route::get('/addresses', [CustomerAddressController::class, 'index'])->name('addresses.index');
		Route::get('/addresses/create', [CustomerAddressController::class, 'create'])->name('addresses.create');
		Route::post('/addresses', [CustomerAddressController::class, 'store'])->name('addresses.store');
		Route::get('/addresses/{address}/edit', [CustomerAddressController::class, 'edit'])->name('addresses.edit');
		Route::put('/addresses/{address}', [CustomerAddressController::class, 'update'])->name('addresses.update');
		Route::delete('/addresses/{address}', [CustomerAddressController::class, 'destroy'])->name('addresses.destroy');
		Route::post('/addresses/{address}/default', [CustomerAddressController::class, 'setDefault'])->name('addresses.default');

		Route::get('/support/tickets', [CustomerSupportTicketController::class, 'index'])->name('support.tickets.index');
		Route::get('/support/tickets/create', [CustomerSupportTicketController::class, 'create'])->name('support.tickets.create');
		Route::post('/support/tickets', [CustomerSupportTicketController::class, 'store'])->name('support.tickets.store');
		Route::get('/support/tickets/{ticket}', [CustomerSupportTicketController::class, 'show'])->name('support.tickets.show');
		Route::post('/support/tickets/{ticket}/reply', [CustomerSupportTicketController::class, 'reply'])->name('support.tickets.reply');

		Route::get('/profile', [CustomerProfileController::class, 'edit'])->name('profile.edit');
		Route::put('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');
		Route::put('/profile/password', [CustomerProfileController::class, 'password'])->name('profile.password');

		Route::get('/affiliates', [\App\Http\Controllers\Customer\AffiliateController::class, 'index'])->name('affiliates.index');
		Route::post('/affiliates/apply', [\App\Http\Controllers\Customer\AffiliateController::class, 'apply'])->name('affiliates.apply');
		Route::post('/affiliates/payout-request', [\App\Http\Controllers\Customer\AffiliateController::class, 'requestPayout'])->name('affiliates.payout-request');
	});

	Route::prefix('admin')->middleware('admin')->group(function () {
		Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
		Route::get('products/export', [AdminProductController::class, 'export'])->name('admin.products.export');
		Route::post('products/import', [AdminProductController::class, 'import'])->name('admin.products.import');
		Route::delete('products/{product}/images/{image}', [AdminProductController::class, 'destroyImage'])->name('admin.products.images.destroy');
		Route::resource('products', AdminProductController::class)->names('admin.products')->except(['show']);
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)
         ->names('admin.orders')
         ->only(['index', 'show', 'update']);
		Route::get('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('admin.profile');
		Route::post('profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('admin.profile.update');
		Route::post('profile/password', [\App\Http\Controllers\Admin\ProfileController::class, 'password'])->name('admin.profile.password');
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->names('admin.categories')->except(['show']);
        Route::resource('tags', \App\Http\Controllers\Admin\TagController::class)->names('admin.tags')->except(['show']);
        Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class)->names('admin.brands')->except(['show']);
        Route::resource('attributes', \App\Http\Controllers\Admin\AttributeController::class)->names('admin.attributes')->except(['show']);
        Route::resource('tax-classes', \App\Http\Controllers\Admin\TaxClassController::class)->names('admin.tax-classes')->except(['show']);
        Route::resource('shipping-methods', \App\Http\Controllers\Admin\ShippingMethodController::class)->names('admin.shipping-methods')->except(['show']);
        Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class)->names('admin.coupons')->except(['show']);
        Route::resource('offers', \App\Http\Controllers\Admin\OfferController::class)->names('admin.offers')->except(['show']);
        Route::resource('affiliates', \App\Http\Controllers\Admin\AffiliateController::class)->names('admin.affiliates')->except(['show']);
        Route::get('reviews', [\App\Http\Controllers\Admin\ProductReviewController::class, 'index'])->name('admin.reviews.index');
        Route::post('reviews/{review}/approve', [\App\Http\Controllers\Admin\ProductReviewController::class, 'approve'])->name('admin.reviews.approve');
        Route::post('reviews/{review}/reject', [\App\Http\Controllers\Admin\ProductReviewController::class, 'reject'])->name('admin.reviews.reject');
        Route::post('reviews/{review}/feature', [\App\Http\Controllers\Admin\ProductReviewController::class, 'feature'])->name('admin.reviews.feature');
        Route::delete('reviews/{review}', [\App\Http\Controllers\Admin\ProductReviewController::class, 'destroy'])->name('admin.reviews.destroy');
        Route::get('comments', [\App\Http\Controllers\Admin\BlogCommentController::class, 'index'])->name('admin.comments.index');
        Route::post('comments/{comment}/approve', [\App\Http\Controllers\Admin\BlogCommentController::class, 'approve'])->name('admin.comments.approve');
        Route::post('comments/{comment}/reject', [\App\Http\Controllers\Admin\BlogCommentController::class, 'reject'])->name('admin.comments.reject');
        Route::post('comments/{comment}/spam', [\App\Http\Controllers\Admin\BlogCommentController::class, 'markSpam'])->name('admin.comments.spam');
        Route::delete('comments/{comment}', [\App\Http\Controllers\Admin\BlogCommentController::class, 'destroy'])->name('admin.comments.destroy');
        Route::prefix('affiliates')->name('admin.affiliates.')->group(function () {
            Route::get('settings', [\App\Http\Controllers\Admin\AffiliateSettingController::class, 'edit'])->name('settings');
            Route::post('settings', [\App\Http\Controllers\Admin\AffiliateSettingController::class, 'update'])->name('settings.update');
            Route::get('commissions', [\App\Http\Controllers\Admin\AffiliateCommissionController::class, 'index'])->name('commissions.index');
            Route::post('commissions/{commission}/approve', [\App\Http\Controllers\Admin\AffiliateCommissionController::class, 'approve'])->name('commissions.approve');
            Route::post('commissions/{commission}/cancel', [\App\Http\Controllers\Admin\AffiliateCommissionController::class, 'cancel'])->name('commissions.cancel');
            Route::resource('payouts', \App\Http\Controllers\Admin\AffiliatePayoutController::class)->names('payouts')->except(['destroy']);
        });
        Route::get('users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('users/admins', [AdminUserController::class, 'admins'])->name('admin.users.admins');
        Route::get('users/staff', [AdminUserController::class, 'staff'])->name('admin.users.staff');
        Route::get('users/customers', [AdminUserController::class, 'customers'])->name('admin.users.customers');
        Route::get('users/{user}/admin-activity', [AdminUserController::class, 'adminActivity'])->name('admin.users.admin-activity');
        Route::get('users/{user}/staff-activity', [AdminUserController::class, 'staffActivity'])->name('admin.users.staff-activity');
        Route::get('users/{user}/customer-activity', [AdminUserController::class, 'customerActivity'])->name('admin.users.customer-activity');
        Route::get('users/{user}/customer-details', [AdminUserController::class, 'customerDetails'])->name('admin.users.customer-details');
        Route::get('users/assign-modules', [AdminUserController::class, 'assignModules'])->name('admin.users.assign-modules');
        Route::post('users/{user}/modules', [AdminUserController::class, 'updateModules'])->name('admin.users.update-modules');
        Route::get('users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('users', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::put('users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('admin.activity-logs.index');
        Route::get('customers', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('admin.customers.index');
        Route::get('customers/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('admin.customers.show');
        Route::get('customers/{customer}/journey', [\App\Http\Controllers\Admin\CustomerController::class, 'journey'])->name('admin.customers.journey');
        Route::post('customers/bulk-messaging', [\App\Http\Controllers\Admin\CustomerController::class, 'bulkMessaging'])->name('admin.customers.bulk-messaging');
        Route::prefix('marketing')->name('admin.marketing.')->group(function () {
            Route::resource('templates', \App\Http\Controllers\Admin\MarketingTemplateController::class)->names('templates');
            Route::post('templates/sync-whatsapp', [\App\Http\Controllers\Admin\MarketingTemplateController::class, 'syncWhatsAppTemplates'])->name('templates.sync-whatsapp');
            Route::resource('campaigns', \App\Http\Controllers\Admin\MarketingCampaignController::class)->names('campaigns');
            Route::post('campaigns/{campaign}/send', [\App\Http\Controllers\Admin\MarketingCampaignController::class, 'send'])->name('campaigns.send');
            Route::resource('drip-campaigns', \App\Http\Controllers\Admin\DripCampaignController::class)->names('drip-campaigns');
            Route::get('drip-campaigns/trigger/manual', [\App\Http\Controllers\Admin\DripCampaignController::class, 'trigger'])->name('drip-campaigns.trigger');
            Route::post('drip-campaigns/trigger', [\App\Http\Controllers\Admin\DripCampaignController::class, 'triggerCampaign'])->name('drip-campaigns.trigger-campaign');
        });
        Route::resource('leads', AdminLeadController::class)->names('admin.leads')->except(['show']);
        Route::get('leads/export', [AdminLeadController::class, 'export'])->name('admin.leads.export');
        Route::post('leads/import', [AdminLeadController::class, 'import'])->name('admin.leads.import');
        Route::get('leads/assign', [AdminLeadController::class, 'assign'])->name('admin.leads.assign');
        Route::post('leads/assign', [AdminLeadController::class, 'assignUpdate'])->name('admin.leads.assign.update');
        Route::get('leads/followups', [AdminLeadController::class, 'followups'])->name('admin.leads.followups');
        Route::get('leads/trash', [AdminLeadController::class, 'trash'])->name('admin.leads.trash');
        Route::post('leads/{leadId}/restore', [AdminLeadController::class, 'restore'])->name('admin.leads.restore');
        Route::delete('leads/{leadId}/force-delete', [AdminLeadController::class, 'forceDelete'])->name('admin.leads.force-delete');
        Route::post('leads/{lead}/score', [AdminLeadController::class, 'updateScore'])->name('admin.leads.update-score');

        Route::prefix('leads/{lead}/followups')->name('admin.leads.followups.')->group(function () {
            Route::post('/', [LeadFollowupController::class, 'store'])->name('store');
            Route::put('{followup}', [LeadFollowupController::class, 'update'])->name('update');
            Route::delete('{followup}', [LeadFollowupController::class, 'destroy'])->name('destroy');
            Route::post('{followup}/complete', [LeadFollowupController::class, 'complete'])->name('complete');
            Route::post('{followup}/cancel', [LeadFollowupController::class, 'cancel'])->name('cancel');
        });

        Route::prefix('leads/{lead}/activities')->name('admin.leads.activities.')->group(function () {
            Route::get('/', [LeadActivityController::class, 'index'])->name('index');
            Route::post('/', [LeadActivityController::class, 'store'])->name('store');
            Route::delete('{activity}', [LeadActivityController::class, 'destroy'])->name('destroy');
        });
        Route::resource('lead-sources', \App\Http\Controllers\Admin\LeadSourceController::class)->names('admin.lead-sources')->except(['show']);
        Route::resource('lead-stages', \App\Http\Controllers\Admin\LeadStageController::class)->names('admin.lead-stages')->except(['show']);
        Route::resource('quotations', AdminQuotationController::class)->names('admin.quotations');
        Route::resource('enquiries', \App\Http\Controllers\Admin\EnquiryController::class)->names('admin.enquiries')->only(['index', 'show']);
        Route::post('enquiries/{enquiry}/status', [\App\Http\Controllers\Admin\EnquiryController::class, 'updateStatus'])->name('admin.enquiries.status');
        Route::post('enquiries/{enquiry}/convert-to-lead', [\App\Http\Controllers\Admin\EnquiryController::class, 'convertToLead'])->name('admin.enquiries.convert-to-lead');
        Route::prefix('invoices')->name('admin.invoices.')->group(function () {
            Route::resource('templates', \App\Http\Controllers\Admin\InvoiceTemplateController::class)->names('templates');
            Route::resource('invoices', \App\Http\Controllers\Admin\InvoiceController::class)->names('invoices');
            Route::get('invoices/{invoice}/pdf', [\App\Http\Controllers\Admin\InvoiceController::class, 'generatePdf'])->name('invoices.pdf');
            Route::post('invoices/{invoice}/status', [\App\Http\Controllers\Admin\InvoiceController::class, 'updateStatus'])->name('invoices.status');
        });
        Route::prefix('blog')->name('admin.blog.')->group(function () {
            Route::resource('posts', AdminBlogPostController::class)->except(['show']);
            Route::resource('categories', AdminBlogCategoryController::class)->except(['show']);
            Route::resource('tags', AdminBlogTagController::class)->except(['show']);
        });
        Route::prefix('cms')->name('admin.cms.')->group(function () {
            Route::prefix('home')->name('home.')->group(function () {
                Route::get('/', [AdminHomeSliderController::class, 'home'])->name('index');
                Route::get('sliders', [AdminHomeSliderController::class, 'index'])->name('sliders.index');
                Route::get('sliders/create', [AdminHomeSliderController::class, 'create'])->name('sliders.create');
                Route::post('sliders', [AdminHomeSliderController::class, 'store'])->name('sliders.store');
                Route::get('sliders/{slider}/edit', [AdminHomeSliderController::class, 'edit'])->name('sliders.edit');
                Route::put('sliders/{slider}', [AdminHomeSliderController::class, 'update'])->name('sliders.update');
                Route::delete('sliders/{slider}', [AdminHomeSliderController::class, 'destroy'])->name('sliders.destroy');
                Route::get('second-slider', [AdminHomeSliderController::class, 'secondSliderSettings'])->name('second-slider.edit');
                Route::post('second-slider', [AdminHomeSliderController::class, 'updateSecondSliderSettings'])->name('second-slider.update');
                Route::get('product-slides', [AdminHomeSliderController::class, 'productSlidesSettings'])->name('product-slides.edit');
                Route::post('product-slides', [AdminHomeSliderController::class, 'updateProductSlidesSettings'])->name('product-slides.update');
                Route::get('third-slider', [AdminHomeSliderController::class, 'thirdSliderSettings'])->name('third-slider.edit');
                Route::post('third-slider', [AdminHomeSliderController::class, 'updateThirdSliderSettings'])->name('third-slider.update');
                Route::get('product-slider-2', [AdminHomeSliderController::class, 'productSlider2Settings'])->name('product-slider-2.edit');
                Route::post('product-slider-2', [AdminHomeSliderController::class, 'updateProductSlider2Settings'])->name('product-slider-2.update');
                Route::get('reviews', [AdminHomeSliderController::class, 'homeReviewsSettings'])->name('reviews.edit');
                Route::post('reviews', [AdminHomeSliderController::class, 'updateHomeReviewsSettings'])->name('reviews.update');
                Route::get('seo', [AdminHomeSliderController::class, 'seoSettings'])->name('seo.settings');
                Route::post('seo', [AdminHomeSliderController::class, 'updateSeoSettings'])->name('seo.settings.update');
            });
            Route::prefix('header')->name('header.')->group(function () {
                Route::get('toolbar', [AdminHeaderToolbarController::class, 'index'])->name('toolbar.index');
                Route::get('toolbar/create', [AdminHeaderToolbarController::class, 'create'])->name('toolbar.create');
                Route::post('toolbar', [AdminHeaderToolbarController::class, 'store'])->name('toolbar.store');
                Route::get('toolbar/{toolbar}/edit', [AdminHeaderToolbarController::class, 'edit'])->name('toolbar.edit');
                Route::put('toolbar/{toolbar}', [AdminHeaderToolbarController::class, 'update'])->name('toolbar.update');
                Route::delete('toolbar/{toolbar}', [AdminHeaderToolbarController::class, 'destroy'])->name('toolbar.destroy');
                Route::get('toolbar/settings', [AdminHeaderToolbarController::class, 'settings'])->name('toolbar.settings');
                Route::post('toolbar/settings', [AdminHeaderToolbarController::class, 'updateSettings'])->name('toolbar.settings.update');
            });
            Route::prefix('legal-pages')->name('legal-pages.')->group(function () {
                Route::get('/', [AdminLegalPageController::class, 'index'])->name('index');
                Route::get('{type}/edit', [AdminLegalPageController::class, 'edit'])->name('edit');
                Route::put('{type}', [AdminLegalPageController::class, 'update'])->name('update');
            });
            Route::prefix('about-us')->name('about-us.')->group(function () {
                Route::get('edit', [AdminAboutPageController::class, 'edit'])->name('edit');
                Route::put('update', [AdminAboutPageController::class, 'update'])->name('update');
            });
            Route::prefix('breadcrumb')->name('breadcrumb.')->group(function () {
                Route::get('settings', [\App\Http\Controllers\Admin\BreadcrumbController::class, 'settings'])->name('settings');
                Route::post('settings', [\App\Http\Controllers\Admin\BreadcrumbController::class, 'updateSettings'])->name('settings.update');
            });
        });
		Route::get('settings/general', [AdminSettingController::class, 'edit'])->name('admin.settings.general');
		Route::post('settings/general', [AdminSettingController::class, 'update'])->name('admin.settings.update');
		Route::get('settings/seo', [AdminSeoToolController::class, 'edit'])->name('admin.settings.seo');
		Route::post('settings/seo/robots', [AdminSeoToolController::class, 'updateRobots'])->name('admin.settings.seo.robots.update');
		Route::post('settings/seo/robots/reset', [AdminSeoToolController::class, 'resetRobots'])->name('admin.settings.seo.robots.reset');
		Route::get('settings/seo/robots/download', [AdminSeoToolController::class, 'downloadRobots'])->name('admin.settings.seo.robots.download');
		Route::post('settings/seo/sitemap', [AdminSeoToolController::class, 'generateSitemap'])->name('admin.settings.seo.sitemap.generate');
		Route::get('settings/seo/sitemap/download', [AdminSeoToolController::class, 'downloadSitemap'])->name('admin.settings.seo.sitemap.download');
		
		// Location Manager routes
		Route::resource('settings/locations', \App\Http\Controllers\Admin\LocationController::class)->names('admin.settings.locations')->parameters(['locations' => 'location']);
		Route::get('settings/locations/import', [\App\Http\Controllers\Admin\LocationController::class, 'import'])->name('admin.settings.locations.import');
		Route::post('settings/locations/import', [\App\Http\Controllers\Admin\LocationController::class, 'processImport'])->name('admin.settings.locations.import.process');
		Route::get('settings/locations/export', [\App\Http\Controllers\Admin\LocationController::class, 'export'])->name('admin.settings.locations.export');

		Route::get('integrations', [AdminIntegrationController::class, 'index'])->name('admin.integrations.index');
		Route::post('integrations/email/smtp', [AdminIntegrationController::class, 'updateSmtp'])->name('admin.integrations.smtp.update');
		Route::post('integrations/email/smtp/test', [AdminIntegrationController::class, 'testSmtp'])->name('admin.integrations.smtp.test');
		Route::post('integrations/whatsapp/twilio', [AdminIntegrationController::class, 'updateTwilioWhatsApp'])->name('admin.integrations.whatsapp.twilio.update');
		Route::post('integrations/whatsapp/twilio/test', [AdminIntegrationController::class, 'testTwilioWhatsApp'])->name('admin.integrations.whatsapp.twilio.test');
		Route::post('integrations/whatsapp/meta', [AdminIntegrationController::class, 'updateMetaWhatsApp'])->name('admin.integrations.whatsapp.meta.update');
		Route::post('integrations/whatsapp/meta/test', [AdminIntegrationController::class, 'testMetaWhatsApp'])->name('admin.integrations.whatsapp.meta.test');
		Route::get('integrations/whatsapp/order-notifications-docs', function () {
			return view('admin.integrations.whatsapp-order-notifications-docs');
		})->name('admin.integrations.whatsapp.order-notifications-docs');
		Route::prefix('whatsapp-catalog')->name('admin.whatsapp-catalog.')->group(function () {
			Route::get('/', [\App\Http\Controllers\Admin\WhatsAppCatalogController::class, 'index'])->name('index');
			Route::post('sync/{product}', [\App\Http\Controllers\Admin\WhatsAppCatalogController::class, 'syncProduct'])->name('sync');
			Route::post('sync-multiple', [\App\Http\Controllers\Admin\WhatsAppCatalogController::class, 'syncMultiple'])->name('sync-multiple');
			Route::post('get-link', [\App\Http\Controllers\Admin\WhatsAppCatalogController::class, 'getCatalogLink'])->name('get-link');
		});
		Route::post('integrations/sms/twilio', [AdminIntegrationController::class, 'updateTwilioSms'])->name('admin.integrations.sms.twilio.update');
		Route::post('integrations/sms/twilio/test', [AdminIntegrationController::class, 'testTwilioSms'])->name('admin.integrations.sms.twilio.test');
		Route::post('integrations/push/fcm', [AdminIntegrationController::class, 'updatePushFcm'])->name('admin.integrations.push.fcm.update');
        Route::post('integrations/push/fcm/test', [AdminIntegrationController::class, 'testPushFcm'])->name('admin.integrations.push.fcm.test');
		Route::post('integrations/social/google', [AdminIntegrationController::class, 'updateSocialGoogle'])->name('admin.integrations.social.google.update');
		Route::post('integrations/social/google/test', [AdminIntegrationController::class, 'testSocialGoogle'])->name('admin.integrations.social.google.test');
		Route::post('integrations/social/facebook', [AdminIntegrationController::class, 'updateSocialFacebook'])->name('admin.integrations.social.facebook.update');
		Route::post('integrations/social/facebook/test', [AdminIntegrationController::class, 'testSocialFacebook'])->name('admin.integrations.social.facebook.test');
		Route::post('integrations/payments/razorpay', [AdminIntegrationController::class, 'updatePaymentsRazorpay'])->name('admin.integrations.payments.razorpay.update');
		Route::post('integrations/payments/razorpay/test', [AdminIntegrationController::class, 'testPaymentsRazorpay'])->name('admin.integrations.payments.razorpay.test');

        Route::prefix('support')->name('admin.support.')->group(function () {
			Route::get('tickets', [AdminSupportTicketController::class, 'index'])->name('tickets.index');
			Route::get('tickets/{ticket}', [AdminSupportTicketController::class, 'show'])->name('tickets.show');
			Route::post('tickets/{ticket}/reply', [AdminSupportTicketController::class, 'reply'])->name('tickets.reply');
			Route::get('settings', [AdminSupportSettingController::class, 'edit'])->name('settings.edit');
			Route::post('settings', [AdminSupportSettingController::class, 'update'])->name('settings.update');
			Route::resource('categories', AdminSupportCategoryController::class)->except(['show']);
        });

        // Documentation
        Route::prefix('documentation')->name('admin.documentation.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DocumentationController::class, 'index'])->name('index');
            Route::get('{doc}', [\App\Http\Controllers\Admin\DocumentationController::class, 'show'])->name('show');
        });

        // API Access & Docs
        Route::resource('api-tokens', \App\Http\Controllers\Admin\ApiTokenController::class)
            ->names('admin.api-tokens')
            ->except(['show']);
        
        Route::prefix('api')->name('admin.api.')->group(function () {
            Route::get('documentation', function () {
                return view('admin.api.documentation');
            })->name('documentation');
            
            Route::get('docs', function () {
                return view('admin.api.docs');
            })->name('docs');
            
            Route::get('usage-statistics', [\App\Http\Controllers\Admin\ApiUsageStatisticsController::class, 'index'])->name('usage-statistics');
            Route::get('test-console', [\App\Http\Controllers\Admin\ApiTestController::class, 'index'])->name('test-console');
            
            Route::resource('webhooks', \App\Http\Controllers\Admin\WebhookController::class)
                ->names('webhooks')
                ->parameters(['webhooks' => 'webhook_endpoint'])
                ->except(['show']);
            
            Route::get('webhook-logs', [\App\Http\Controllers\Admin\WebhookLogController::class, 'index'])->name('webhook-logs.index');
        });
	});
});

// Auth (Breeze) if present
if (file_exists(base_path('routes/auth.php'))) {
	require base_path('routes/auth.php');
}
