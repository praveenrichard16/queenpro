<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\ApiBaseController;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartApiController extends ApiBaseController
{
    public function index(Request $request): JsonResponse
    {
        $cart = $this->getCart();

        return $this->successResponse([
            'items' => $cart,
            'subtotal' => $this->getCartSubtotal($cart),
        ], 'Cart retrieved successfully');
    }

    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if (!$product->is_active) {
            return $this->errorResponse('Product is not available', 400);
        }

        if ($product->stock_quantity < $validated['quantity']) {
            return $this->errorResponse('Insufficient stock', 400);
        }

        $cart = $this->getCart();
        $cartKey = 'cart_item_' . $product->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $validated['quantity'];
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->selling_price ?? $product->price,
                'quantity' => $validated['quantity'],
                'image' => $product->image,
            ];
        }

        session(['cart' => $cart]);

        return $this->successResponse([
            'cart' => $cart,
            'message' => 'Item added to cart',
        ], 'Item added to cart successfully');
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = $this->getCart();
        $cartKey = 'cart_item_' . $validated['product_id'];

        if ($validated['quantity'] <= 0) {
            unset($cart[$cartKey]);
        } else {
            if (isset($cart[$cartKey])) {
                $cart[$cartKey]['quantity'] = $validated['quantity'];
            }
        }

        session(['cart' => $cart]);

        return $this->successResponse([
            'cart' => $cart,
        ], 'Cart updated successfully');
    }

    public function remove(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $cart = $this->getCart();
        $cartKey = 'cart_item_' . $validated['product_id'];

        unset($cart[$cartKey]);
        session(['cart' => $cart]);

        return $this->successResponse([
            'cart' => $cart,
        ], 'Item removed from cart successfully');
    }

    public function clear(): JsonResponse
    {
        session(['cart' => []]);

        return $this->successResponse(null, 'Cart cleared successfully');
    }

    protected function getCart(): array
    {
        return session('cart', []);
    }

    protected function getCartSubtotal(array $cart): float
    {
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }
        return round($subtotal, 2);
    }
}

