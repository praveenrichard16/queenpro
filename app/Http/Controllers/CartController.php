<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShippingMethod;
use App\Models\Coupon;
use App\Services\TaxService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        $subtotal = $this->getCartSubtotal($cart);
        
        $taxService = new TaxService();
        $taxAmount = $taxService->calculateCartTax($cart);
        
        // Get applied coupon if any
        $appliedCoupon = session('applied_coupon');
        $discountAmount = 0;
        if ($appliedCoupon) {
            $coupon = Coupon::find($appliedCoupon['id']);
            if ($coupon && $coupon->isValid()) {
                $discountAmount = $coupon->calculateDiscount($subtotal);
            }
        }
        
        // Get available shipping methods
        $shippingMethods = ShippingMethod::active()->ordered()->get();
        
        // Get available public coupons for display
        $availableCouponsQuery = Coupon::where('status', 'active')
            ->where(function($query) {
                $query->whereNull('valid_from')
                    ->orWhere('valid_from', '<=', now());
            })
            ->where(function($query) {
                $query->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', now());
            });
        
        // Only filter by is_public if the column exists
        if (\Schema::hasColumn('coupons', 'is_public')) {
            $availableCouponsQuery->where('is_public', true);
        }
        
        $availableCoupons = $availableCouponsQuery->limit(3)->get();
        
        // Calculate total
        $total = $subtotal - $discountAmount + $taxAmount;
        
        // Free shipping threshold (default 250, can be from settings or first shipping method)
        $freeShippingThreshold = 250;
        $freeShippingMethod = $shippingMethods->firstWhere('type', 'free_shipping');
        if ($freeShippingMethod && $freeShippingMethod->free_shipping_threshold) {
            $freeShippingThreshold = $freeShippingMethod->free_shipping_threshold;
        }

        return view('cart.index', compact(
            'cart', 
            'subtotal', 
            'taxAmount', 
            'total',
            'discountAmount',
            'appliedCoupon',
            'shippingMethods',
            'availableCoupons',
            'freeShippingThreshold'
        ));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::active()->inStock()->findOrFail($request->product_id);

        if ($product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Insufficient stock available.');
        }

        $cart = $this->getCart();
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $request->quantity;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $request->quantity,
                'max_quantity' => $product->stock_quantity
            ];
        }

        Session::put('cart', $cart);

        return back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0'
        ]);

        $cart = $this->getCart();
        $productId = $request->product_id;

        if ($request->quantity == 0) {
            unset($cart[$productId]);
        } else {
            if (isset($cart[$productId])) {
                // Check stock availability
                $product = Product::find($productId);
                if ($product && $request->quantity > $product->stock_quantity) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Insufficient stock available. Maximum: ' . $product->stock_quantity
                        ], 400);
                    }
                    return back()->with('error', 'Insufficient stock available. Maximum: ' . $product->stock_quantity);
                }
                $cart[$productId]['quantity'] = $request->quantity;
            }
        }

        Session::put('cart', $cart);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!'
            ]);
        }

        return back()->with('success', 'Cart updated successfully!');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $cart = $this->getCart();
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
        }

        return back()->with('success', 'Product removed from cart successfully!');
    }

    public function clear()
    {
        Session::forget('cart');
        return back()->with('success', 'Cart cleared successfully!');
    }

    private function getCart()
    {
        return Session::get('cart', []);
    }

    private function getCartSubtotal($cart)
    {
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        return $subtotal;
    }

    private function getCartTotal($cart)
    {
        return $this->getCartSubtotal($cart);
    }
}
