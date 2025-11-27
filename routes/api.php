<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProductApiController;
use App\Http\Controllers\Api\V1\ProductReviewApiController;
use App\Http\Controllers\Api\V1\OrderApiController;
use App\Http\Controllers\Api\V1\CartApiController;
use App\Http\Controllers\Api\V1\UserApiController;
use App\Http\Controllers\Api\V1\CouponApiController;
use App\Http\Controllers\Api\V1\CategoryApiController;
use App\Http\Controllers\Api\V1\BrandApiController;
use App\Http\Controllers\Api\V1\LeadApiController;
use App\Http\Controllers\Api\V1\LeadFollowupApiController;
use App\Http\Controllers\Api\V1\LeadImportExportApiController;
use App\Http\Controllers\Api\V1\LeadReminderApiController;
use App\Http\Controllers\Api\V1\LeadSourceApiController;
use App\Http\Controllers\Api\V1\LeadStageApiController;
use App\Http\Controllers\Api\V1\QuotationApiController;
use App\Http\Controllers\Api\V1\EnquiryApiController;
use App\Http\Controllers\Api\V1\InvoiceApiController;
use App\Http\Controllers\Api\V1\BlogPostApiController;
use App\Http\Controllers\Api\V1\BlogCategoryApiController;
use App\Http\Controllers\Api\V1\TicketApiController;
use App\Http\Controllers\Api\V1\AffiliateApiController;
use App\Http\Controllers\Api\V1\ShippingMethodApiController;
use App\Http\Controllers\Api\V1\TaxClassApiController;
use App\Http\Controllers\Api\V1\CommandApiController;

Route::get('/health', function () {
    return ['status' => 'ok'];
});

Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    
    // User endpoints
    Route::get('/me', [UserApiController::class, 'me']);
    Route::apiResource('users', UserApiController::class)->only(['index', 'show']);

    // Product endpoints
    Route::apiResource('products', ProductApiController::class)->only(['index', 'show']);
    Route::get('products/{product}/reviews', [ProductReviewApiController::class, 'index']);

    // Product Reviews
    Route::apiResource('reviews', ProductReviewApiController::class)->only(['index', 'show', 'store']);

    // Categories
    Route::apiResource('categories', CategoryApiController::class)->only(['index', 'show']);

    // Brands
    Route::apiResource('brands', BrandApiController::class)->only(['index', 'show']);

    // Cart endpoints
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartApiController::class, 'index']);
        Route::post('/add', [CartApiController::class, 'add']);
        Route::put('/update', [CartApiController::class, 'update']);
        Route::delete('/remove', [CartApiController::class, 'remove']);
        Route::delete('/clear', [CartApiController::class, 'clear']);
    });

    // Order endpoints
    Route::apiResource('orders', OrderApiController::class)->only(['index', 'show', 'store']);

    // Coupon endpoints
    Route::apiResource('coupons', CouponApiController::class)->only(['index', 'show']);
    Route::post('coupons/validate', [CouponApiController::class, 'validate']);

    // Lead endpoints
    Route::get('leads/analytics/overview', [LeadApiController::class, 'analytics']);
    Route::get('leads/trash', [LeadApiController::class, 'trash']);
    Route::post('leads/{lead}/recalculate-score', [LeadApiController::class, 'recalculateScore']);
    Route::post('leads/{leadId}/restore', [LeadApiController::class, 'restore']);
    Route::delete('leads/{leadId}/force-delete', [LeadApiController::class, 'forceDelete']);
    Route::apiResource('leads', LeadApiController::class);
    Route::apiResource('leads.followups', LeadFollowupApiController::class);
    Route::post('leads/{lead}/followups/{followup}/complete', [LeadFollowupApiController::class, 'complete']);
    Route::post('leads/{lead}/followups/{followup}/cancel', [LeadFollowupApiController::class, 'cancel']);
    Route::post('leads/{lead}/followups/{followup}/send-reminder', [LeadFollowupApiController::class, 'sendReminder']);
    Route::get('leads/export', [LeadImportExportApiController::class, 'export']);
    Route::post('leads/import', [LeadImportExportApiController::class, 'import']);
    Route::post('leads/reminders/run', [LeadReminderApiController::class, 'run']);
    Route::apiResource('lead-sources', LeadSourceApiController::class)->only(['index', 'show']);
    Route::apiResource('lead-stages', LeadStageApiController::class)->only(['index', 'show']);

    // Quotation endpoints
    Route::apiResource('quotations', QuotationApiController::class);

    // Enquiry endpoints
    Route::apiResource('enquiries', EnquiryApiController::class);

    // Invoice endpoints
    Route::apiResource('invoices', InvoiceApiController::class)->only(['index', 'show']);

    // Blog endpoints
    Route::apiResource('blog/posts', BlogPostApiController::class)->only(['index', 'show']);
    Route::apiResource('blog/categories', BlogCategoryApiController::class)->only(['index', 'show']);

    // Ticket/Support endpoints
    Route::apiResource('tickets', TicketApiController::class);

    // Affiliate endpoints
    Route::apiResource('affiliates', AffiliateApiController::class)->only(['index', 'show']);
    Route::prefix('affiliate')->group(function () {
        Route::get('/me', [AffiliateApiController::class, 'me']);
        Route::get('/stats', [AffiliateApiController::class, 'stats']);
        Route::get('/commissions', [AffiliateApiController::class, 'commissions']);
        Route::get('/referrals', [AffiliateApiController::class, 'referrals']);
        Route::post('/payout-request', [AffiliateApiController::class, 'payoutRequest']);
        Route::get('/payouts', [AffiliateApiController::class, 'payouts']);
        Route::get('/link', [AffiliateApiController::class, 'link']);
    });

    // Shipping Methods
    Route::apiResource('shipping-methods', ShippingMethodApiController::class)->only(['index', 'show']);

    // Tax Classes
    Route::apiResource('tax-classes', TaxClassApiController::class)->only(['index', 'show']);

    // System Commands (admin only)
    Route::prefix('commands')->group(function () {
        Route::get('/', [CommandApiController::class, 'list']);
        Route::post('/execute', [CommandApiController::class, 'execute']);
    });
});
