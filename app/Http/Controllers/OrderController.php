<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use App\Models\AffiliateReferral;
use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceTemplate;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingMethod;
use App\Services\PaymentService;
use App\Services\TaxService;
use App\Services\ShippingService;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout()
    {
        // Ensure user is authenticated (middleware handles this, but double-check)
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to continue checkout.');
        }

        $user = auth()->user();
        $cart = $this->getCart();
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $taxService = new TaxService();
        $shippingService = new ShippingService();
        
        $subtotal = $this->getCartSubtotal($cart);
        
        // Apply coupon discount if available
        $discountAmount = 0;
        $appliedCoupon = session('applied_coupon');
        if ($appliedCoupon) {
            $coupon = Coupon::find($appliedCoupon['id']);
            if ($coupon && $coupon->isValid()) {
                $discountAmount = $coupon->calculateDiscount($subtotal);
            }
        }
        
        $taxAmount = $taxService->calculateCartTax($cart);
        $shippingMethods = $shippingService->getAvailableMethods();
        
        // Default shipping (first available or null)
        $selectedShipping = $shippingMethods->first();
        $shippingAmount = $selectedShipping ? $shippingService->calculateShipping($selectedShipping, $subtotal) : 0;
        
        $total = $subtotal - $discountAmount + $taxAmount + $shippingAmount;

        // Load user's saved addresses
        $savedAddresses = $user->addresses()->where('type', 'shipping')->orderByDesc('is_default')->get();
        $defaultAddress = $savedAddresses->where('is_default', true)->first() ?? $savedAddresses->first();

        return view('orders.checkout', compact('cart', 'subtotal', 'discountAmount', 'taxAmount', 'shippingAmount', 'total', 'shippingMethods', 'selectedShipping', 'appliedCoupon', 'savedAddresses', 'defaultAddress', 'user'));
    }

    public function store(Request $request)
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to place an order.');
        }

        $user = auth()->user();

        // Determine if billing is same as shipping
        $sameAsShipping = $request->has('same_as_shipping') && $request->input('same_as_shipping') == '1';
        
        $rules = [
            'saved_shipping_address_id' => 'nullable|exists:customer_addresses,id',
            'shipping_address' => 'required_without:saved_shipping_address_id|array',
            'shipping_address.street' => 'required_without:saved_shipping_address_id|string|max:255',
            'shipping_address.city' => 'required_without:saved_shipping_address_id|string|max:100',
            'shipping_address.state' => 'required_without:saved_shipping_address_id|string|max:100',
            'shipping_address.postal_code' => 'required_without:saved_shipping_address_id|string|max:20',
            'shipping_address.country' => 'required_without:saved_shipping_address_id|string|in:Saudi Arabia',
            'payment_method' => 'required|string|in:cash_on_delivery,credit_card,paypal',
            'shipping_method_id' => ['required', 'exists:shipping_methods,id'],
        ];
        
        // Only validate billing address if not same as shipping
        if (!$sameAsShipping) {
            $rules['saved_billing_address_id'] = 'nullable|exists:customer_addresses,id';
            $rules['billing_address'] = 'required_without:saved_billing_address_id|array';
            $rules['billing_address.street'] = 'required_without:saved_billing_address_id|string|max:255';
            $rules['billing_address.city'] = 'required_without:saved_billing_address_id|string|max:100';
            $rules['billing_address.state'] = 'required_without:saved_billing_address_id|string|max:100';
            $rules['billing_address.postal_code'] = 'required_without:saved_billing_address_id|string|max:20';
            $rules['billing_address.country'] = 'required_without:saved_billing_address_id|string|in:Saudi Arabia';
        }
        
        $request->validate($rules);

        $cart = $this->getCart();
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Validate stock availability before processing order
        $productIds = array_column($cart, 'id');
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        
        $stockErrors = [];
        foreach ($cart as $item) {
            $product = $products->get($item['id']);
            if (!$product) {
                $stockErrors[] = "Product ID {$item['id']} not found.";
                continue;
            }
            
            if ($product->stock_quantity < $item['quantity']) {
                $stockErrors[] = "Insufficient stock for {$product->name}. Available: {$product->stock_quantity}, Requested: {$item['quantity']}.";
            }
        }
        
        if (!empty($stockErrors)) {
            return redirect()->route('cart.index')
                ->withErrors(['stock' => $stockErrors])
                ->with('error', 'Some products in your cart are out of stock or have insufficient quantity.');
        }

        // Handle saved addresses or manual entry
        $shippingAddress = null;
        $billingAddress = null;

        if ($request->saved_shipping_address_id) {
            $savedShipping = \App\Models\CustomerAddress::where('id', $request->saved_shipping_address_id)
                ->where('user_id', $user->id)
                ->firstOrFail();
            $shippingAddress = [
                'street' => $savedShipping->street,
                'city' => $savedShipping->city,
                'state' => $savedShipping->state,
                'postal_code' => $savedShipping->postal_code,
                'country' => $savedShipping->country ?? 'Saudi Arabia',
            ];
        } else {
            $shippingAddress = $request->shipping_address;
            $shippingAddress['country'] = 'Saudi Arabia'; // Force Saudi Arabia
        }

        // Handle billing address - use shipping if same, otherwise use saved or manual entry
        if ($sameAsShipping) {
            $billingAddress = $shippingAddress;
        } elseif ($request->saved_billing_address_id) {
            $savedBilling = \App\Models\CustomerAddress::where('id', $request->saved_billing_address_id)
                ->where('user_id', $user->id)
                ->firstOrFail();
            $billingAddress = [
                'street' => $savedBilling->street,
                'city' => $savedBilling->city,
                'state' => $savedBilling->state,
                'postal_code' => $savedBilling->postal_code,
                'country' => $savedBilling->country ?? 'Saudi Arabia',
            ];
        } else {
            $billingAddress = $request->billing_address;
            $billingAddress['country'] = 'Saudi Arabia'; // Force Saudi Arabia
        }

        $taxService = new TaxService();
        $shippingService = new ShippingService();
        
        // Calculate subtotal
        $subtotal = $this->getCartSubtotal($cart);
        
        // Apply coupon discount if available
        $discountAmount = 0;
        $couponCode = null;
        $coupon = null;
        
        $appliedCoupon = session('applied_coupon');
        if ($appliedCoupon) {
            $coupon = Coupon::find($appliedCoupon['id']);
            if ($coupon && $coupon->isValid()) {
                $discountAmount = $coupon->calculateDiscount($subtotal);
                $couponCode = $coupon->code;
            }
        }
        
        // Calculate tax on subtotal after discount
        $taxableAmount = max(0, $subtotal - $discountAmount);
        $taxAmount = $taxService->calculateCartTax($cart);
        
        // Get shipping method and calculate shipping
        $shippingMethod = ShippingMethod::findOrFail($request->shipping_method_id);
        $shippingAmount = $shippingService->calculateShipping($shippingMethod, $subtotal, 0, $shippingAddress);
        
        // Calculate total
        $total = $subtotal - $discountAmount + $taxAmount + $shippingAmount;

        // Get referral code from cookie or session
        $referralCode = $request->cookie('affiliate_ref') ?? session('affiliate_ref');
        $affiliate = null;
        
        if ($referralCode) {
            $affiliate = Affiliate::where('affiliate_code', $referralCode)
                ->where('status', 'active')
                ->first();
        }

        // Use database transaction to ensure atomicity and prevent race conditions
        try {
            $order = DB::transaction(function () use ($user, $request, $cart, $subtotal, $discountAmount, $couponCode, $coupon, $taxAmount, $shippingMethod, $shippingAmount, $total, $referralCode, $affiliate, $taxService, $shippingAddress, $billingAddress) {
                // Lock products for update to prevent race conditions
                $lockedProducts = Product::whereIn('id', array_column($cart, 'id'))
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                // Re-validate stock with locked products
                foreach ($cart as $item) {
                    $product = $lockedProducts->get($item['id']);
                    if (!$product || $product->stock_quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock for product ID {$item['id']}.");
                    }
                }

                // Create order with authenticated user's info
                $order = Order::create([
                    'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                    'user_id' => $user->id,
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                    'customer_phone' => $user->phone ?? '',
                    'shipping_address' => $shippingAddress,
                    'billing_address' => $billingAddress,
                    'subtotal' => $subtotal,
                    'discount_amount' => $discountAmount,
                    'coupon_code' => $couponCode,
                    'tax_amount' => $taxAmount,
                    'shipping_method_id' => $shippingMethod->id,
                    'shipping_amount' => $shippingAmount,
                    'total_amount' => $total,
                    'status' => 'pending',
                    'payment_method' => $request->payment_method,
                    'notes' => $request->notes,
                    'referral_code' => $referralCode,
                    'affiliate_id' => $affiliate?->id,
                ]);

                // Increment coupon usage if applied
                if ($coupon) {
                    $coupon->incrementUsage();
                }

                // Create order items with tax and update stock
                foreach ($cart as $item) {
                    $product = $lockedProducts->get($item['id']);
                    $itemSubtotal = $item['price'] * $item['quantity'];
                    $itemTaxAmount = $taxService->calculateProductTax($product, $itemSubtotal);
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'tax_amount' => $itemTaxAmount,
                    ]);

                    // Update product stock atomically
                    $product->decrement('stock_quantity', $item['quantity']);
                }

                return $order;
            });

            // Process payment after order creation (outside transaction to avoid long locks)
            $paymentService = new PaymentService();
            $paymentData = [
                'payment_intent_id' => $request->payment_intent_id ?? null,
                'order_id' => $request->paypal_order_id ?? null,
            ];
            
            $paymentResult = $paymentService->processPayment($order, $request->payment_method, $paymentData);
            
            // Update order with payment result
            $order->update([
                'payment_transaction_id' => $paymentResult['transaction_id'],
                'status' => $paymentResult['success'] && $request->payment_method !== 'cash_on_delivery' 
                    ? 'processing' 
                    : 'pending',
            ]);

            // If payment failed for non-COD methods, rollback stock and cancel order
            if (!$paymentResult['success'] && $request->payment_method !== 'cash_on_delivery') {
                // Restore stock
                foreach ($cart as $item) {
                    $product = Product::find($item['id']);
                    if ($product) {
                        $product->increment('stock_quantity', $item['quantity']);
                    }
                }
                
                $order->update(['status' => 'cancelled']);
                
                return redirect()->route('checkout')
                    ->with('error', 'Payment failed: ' . $paymentResult['message'])
                    ->withInput();
            }

        } catch (\Exception $e) {
            return redirect()->route('cart.index')
                ->with('error', 'Order processing failed: ' . $e->getMessage());
        }

        // Create affiliate referral and commission if applicable
        if ($affiliate && $order) {
            $this->createAffiliateCommission($affiliate, $order, $total);
        }

        // Automatically create invoice for the order
        $this->createInvoiceForOrder($order);

        // Notify admins about new order
        $admins = \App\Models\User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\OrderCreatedNotification($order));
        }

        // Notify customer via email + WhatsApp (if configured)
        if ($order->customer_email) {
            $orderUser = $order->user ?: $user;
            $orderUser->notify(new \App\Notifications\OrderCreatedNotification($order));
        }
        if ($order->customer_phone) {
            WhatsAppService::sendOrderCreated($order);
        }

        // Clear cart and applied coupon
        Session::forget('cart');
        Session::forget('applied_coupon');

        return redirect()->route('customer.orders.show', $order)
            ->with('success', 'Order placed successfully! Your order number is ' . $order->order_number);
    }

    protected function createAffiliateCommission($affiliate, $order, $total)
    {
        // Calculate commission
        $commissionAmount = ($total * $affiliate->commission_rate) / 100;
        
        // Create referral record
        $referral = AffiliateReferral::create([
            'affiliate_id' => $affiliate->id,
            'order_id' => $order->id,
            'customer_email' => $order->customer_email,
            'referral_code' => $affiliate->affiliate_code,
            'status' => 'pending',
            'commission_amount' => $commissionAmount,
        ]);
        
        // Create commission record
        $commission = AffiliateCommission::create([
            'affiliate_id' => $affiliate->id,
            'order_id' => $order->id,
            'referral_id' => $referral->id,
            'amount' => $commissionAmount,
            'status' => 'pending',
        ]);
        
        // Update affiliate earnings
        $affiliate->increment('pending_earnings', $commissionAmount);
        $affiliate->increment('total_earnings', $commissionAmount);
    }

    public function show($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        
        // If user is authenticated, check ownership
        if (auth()->check()) {
            // Check if order belongs to authenticated user
            if ($order->user_id && $order->user_id !== auth()->id()) {
                abort(403, 'Unauthorized access to this order.');
            }
            // Also check by email for backward compatibility
            if (!$order->user_id && $order->customer_email !== auth()->user()->email) {
                abort(403, 'Unauthorized access to this order.');
            }
            // Redirect authenticated users to customer order view
            return redirect()->route('customer.orders.show', $order);
        }
        
        // For guest users, allow viewing if they have the order number (basic security)
        // In production, you might want to require email verification or order number
        return view('orders.show', compact('order'));
    }

    public function index()
    {
        // Redirect authenticated users to customer orders
        if (auth()->check()) {
            return redirect()->route('customer.orders.index');
        }
        
        // For guests, redirect to login or show message
        return redirect()->route('login')->with('info', 'Please login to view your orders.');
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

    /**
     * Automatically create an invoice for an order
     */
    protected function createInvoiceForOrder(Order $order)
    {
        try {
            // Check if invoice table exists
            if (!\Illuminate\Support\Facades\Schema::hasTable('invoices')) {
                \Log::warning('Invoice table does not exist. Skipping invoice creation for order: ' . $order->id);
                return;
            }

            // Reload order to ensure we have fresh data
            $order->refresh();

            // Check if invoice already exists for this order
            if ($order->invoice) {
                \Log::info('Invoice already exists for order: ' . $order->id);
                return;
            }

            // Get default template if available
            $template = null;
            if (\Illuminate\Support\Facades\Schema::hasTable('invoice_templates')) {
                $template = InvoiceTemplate::where('is_default', true)
                    ->where('is_active', true)
                    ->first();
            }

            // Calculate due date (30 days from invoice date, or immediate for cash on delivery)
            $invoiceDate = now();
            $dueDate = $order->payment_method === 'cash_on_delivery' 
                ? $invoiceDate->copy()->addDays(0) 
                : $invoiceDate->copy()->addDays(30);

            // Ensure order has order items loaded
            $order->load('orderItems.product');
            
            if ($order->orderItems->isEmpty()) {
                \Log::warning('Order has no items. Skipping invoice creation for order: ' . $order->id);
                return;
            }

            // Create invoice
            $invoice = Invoice::create([
                'order_id' => $order->id,
                'template_id' => $template?->id,
                'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                'invoice_date' => $invoiceDate,
                'due_date' => $dueDate,
                'customer_name' => $order->customer_name,
                'customer_email' => $order->customer_email,
                'billing_address' => $order->billing_address,
                'subtotal' => $order->subtotal ?? 0,
                'tax_amount' => $order->tax_amount ?? 0,
                'shipping_amount' => $order->shipping_amount ?? 0,
                'discount_amount' => 0, // Can be updated later if discounts are added
                'total_amount' => $order->total_amount ?? 0,
                'status' => 'draft', // Can be changed to 'sent' if needed
                'notes' => $order->notes,
            ]);

            // Create invoice items from order items
            foreach ($order->orderItems as $orderItem) {
                $product = $orderItem->product;
                $itemTotal = ($orderItem->price * $orderItem->quantity) + ($orderItem->tax_amount ?? 0);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $orderItem->product_id,
                    'item_name' => $product ? $product->name : 'Product #' . $orderItem->product_id,
                    'item_description' => $product ? ($product->description ?? null) : null,
                    'quantity' => $orderItem->quantity,
                    'unit_price' => $orderItem->price,
                    'tax_amount' => $orderItem->tax_amount ?? 0,
                    'total_price' => $itemTotal,
                ]);
            }

            \Log::info('Invoice created successfully for order: ' . $order->id . ' - Invoice: ' . $invoice->invoice_number);
        } catch (\Exception $e) {
            \Log::error('Failed to create invoice for order: ' . $order->id . ' - Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            // Don't throw exception to prevent order creation from failing
        }
    }
}
