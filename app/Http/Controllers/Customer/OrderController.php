<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $orders = Order::query()
            ->where('customer_email', $user->email)
            ->when($request->string('search')->toString(), function ($query, $search) {
                $query->where(function ($builder) use ($search) {
                    $builder->where('order_number', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%");
                });
            })
            ->withCount('orderItems')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('customer.orders.index', [
            'orders' => $orders,
        ]);
    }

    public function show(Request $request, Order $order)
    {
        abort_unless($order->customer_email === $request->user()->email, 404);

        $order->load(['orderItems.product']);

        return view('customer.orders.show', [
            'order' => $order,
        ]);
    }
}

