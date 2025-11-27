<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateCommission;
use App\Models\Order;
use App\Services\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Order::query()->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(12)->withQueryString();

        $statusCounts = Order::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): View
    {
        $order->load('orderItems.product');

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            'notes' => 'nullable|string',
        ]);

        // Map 'completed' to 'delivered' for backward compatibility
        if ($data['status'] === 'completed') {
            $data['status'] = 'delivered';
        }

        $oldStatus = $order->status;
        $order->update($data);

        // If order status changed, notify customer
        if ($oldStatus !== $order->status) {
            $customer = \App\Models\User::where('email', $order->customer_email)->first();
            if ($customer) {
                $customer->notify(new \App\Notifications\OrderStatusChangedNotification($order, $oldStatus, $order->status));
            }

            if ($order->customer_phone) {
                WhatsAppService::sendOrderStatusUpdated($order, $oldStatus, $order->status);
            }
        }

        // If order status changed to 'delivered', approve affiliate commissions
        if ($oldStatus !== 'delivered' && $order->status === 'delivered' && $order->affiliate_id) {
            $commission = AffiliateCommission::where('order_id', $order->id)
                ->where('status', 'pending')
                ->first();
            
            if ($commission) {
                $commission->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                ]);
                
                $referral = $commission->referral;
                if ($referral) {
                    $referral->update([
                        'status' => 'confirmed',
                        'confirmed_at' => now(),
                    ]);
                }

                // Notify affiliate about approved commission
                $affiliate = $commission->affiliate;
                if ($affiliate && $affiliate->user) {
                    $affiliate->user->notify(new \App\Notifications\AffiliateCommissionApprovedNotification($commission));
                }
            }
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }
}
