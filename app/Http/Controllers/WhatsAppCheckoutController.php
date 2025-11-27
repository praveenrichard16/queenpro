<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\WhatsAppCatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WhatsAppCheckoutController extends Controller
{
    /**
     * Initiate checkout from WhatsApp product link
     */
    public function initiate(Request $request)
    {
        $productId = $request->get('product_id');
        $quantity = $request->get('quantity', 1);

        if (!$productId) {
            return redirect()->route('products.index')
                ->with('error', 'Invalid product link.');
        }

        $product = Product::find($productId);
        
        if (!$product || !$product->is_active) {
            return redirect()->route('products.index')
                ->with('error', 'Product not found or unavailable.');
        }

        // Check if user is authenticated
        if (!auth()->check()) {
            // Store product info in session to add to cart after login
            Session::put('whatsapp_checkout_product', [
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
            
            return redirect()->route('login')
                ->with('info', 'Please login to continue checkout from WhatsApp.');
        }

        // Add product to cart
        $cart = Session::get('cart', []);
        $key = "product_{$productId}";

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $quantity;
        } else {
            $cart[$key] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->effective_price,
            ];
        }

        Session::put('cart', $cart);
        Session::forget('whatsapp_checkout_product');

        return redirect()->route('checkout')
            ->with('success', 'Product added to cart from WhatsApp. Continue with checkout.');
    }

    /**
     * Generate WhatsApp checkout link for a product
     */
    public function getProductLink(Product $product, WhatsAppCatalogService $service)
    {
        if (!$product->is_synced_to_whatsapp) {
            return response()->json([
                'error' => 'Product is not synced to WhatsApp catalog.',
            ], 400);
        }

        $link = $service->getProductLink($product);
        $checkoutLink = route('whatsapp.checkout.initiate', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        return response()->json([
            'whatsapp_link' => $link,
            'checkout_link' => $checkoutLink,
        ]);
    }

    /**
     * Handle WhatsApp payment webhook (if using WhatsApp Pay)
     */
    public function webhook(Request $request)
    {
        // This would handle WhatsApp Pay webhook callbacks
        // Implementation depends on WhatsApp Pay integration
        
        $payload = $request->all();
        
        // Verify webhook signature
        // Process payment status
        // Update order status
        
        return response()->json(['status' => 'received']);
    }
}
