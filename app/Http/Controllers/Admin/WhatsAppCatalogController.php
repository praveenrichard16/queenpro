<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\WhatsAppCatalogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WhatsAppCatalogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'images']);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sync_status')) {
            $status = $request->get('sync_status');
            if ($status === 'synced') {
                $query->where('is_synced_to_whatsapp', true);
            } elseif ($status === 'not_synced') {
                $query->where('is_synced_to_whatsapp', false);
            }
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        
        // Get catalog link, but don't fail the page if there's an error
        $catalogLink = null;
        try {
            $catalogService = app(WhatsAppCatalogService::class);
            $catalogLink = $catalogService->getCatalogLink();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to get WhatsApp catalog link on index page', [
                'error' => $e->getMessage(),
            ]);
            // Continue without catalog link - page will still load
        }

        return view('admin.integrations.whatsapp-catalog', compact('products', 'catalogLink'));
    }

    public function syncProduct(Product $product, WhatsAppCatalogService $service): RedirectResponse
    {
        try {
            // Clear cache to ensure fresh config
            \Illuminate\Support\Facades\Cache::forget('setting_integration_whatsapp_meta');
            
            $success = $service->syncProduct($product);
            
            // Refresh product to get latest sync status
            $product->refresh();
            
            if ($success) {
                return back()->with('success', "Product '{$product->name}' synced to WhatsApp catalog successfully.");
            } else {
                $errorMsg = $product->whatsapp_sync_error ?? 'Unknown error';
                return back()->with('error', "Failed to sync product: {$errorMsg}");
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('WhatsApp catalog sync exception', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Sync failed: ' . $e->getMessage());
        }
    }

    public function syncMultiple(Request $request, WhatsAppCatalogService $service): RedirectResponse
    {
        $request->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['required', 'integer', 'exists:products,id'],
        ]);

        // Clear cache to ensure fresh config
        \Illuminate\Support\Facades\Cache::forget('setting_integration_whatsapp_meta');

        $results = $service->syncProducts($request->get('product_ids'));

        $message = "Synced {$results['success']} product(s) successfully.";
        if ($results['failed'] > 0) {
            $message .= " {$results['failed']} product(s) failed.";
            if (!empty($results['errors'])) {
                $errorDetails = implode('; ', array_slice($results['errors'], 0, 5));
                if (count($results['errors']) > 5) {
                    $errorDetails .= ' and ' . (count($results['errors']) - 5) . ' more...';
                }
                $message .= " Errors: {$errorDetails}";
            }
        }

        if ($results['failed'] > 0 && $results['success'] == 0) {
            return back()->with('error', $message)->with('sync_results', $results);
        }

        return back()->with('success', $message)->with('sync_results', $results);
    }

    public function getCatalogLink(WhatsAppCatalogService $service): RedirectResponse
    {
        $link = $service->getCatalogLink();
        
        if ($link) {
            return back()->with('catalog_link', $link);
        }

        return back()->with('error', 'Failed to generate catalog link. Please ensure WhatsApp is configured.');
    }
}
