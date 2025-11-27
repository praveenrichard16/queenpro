@extends('layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Order #{{ $order->order_number }}</h2>
            <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'completed' ? 'success' : 'info') }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <!-- Order Items -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Order Items</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product->image)
                                        <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" 
                                             class="me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                        <img src="https://via.placeholder.com/60x60/6c757d/ffffff?text=No+Image" 
                                             alt="{{ $item->product->name }}" class="me-3" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                                            @if($item->product->category)
                                            <small class="text-muted">{{ $item->product->category->name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ \App\Services\CurrencyService::format($item->price) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ \App\Services\CurrencyService::format($item->total) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                        <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                        <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Order Details</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                        <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                        <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'completed' ? 'success' : 'info') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Addresses -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Shipping Address</h5>
                    </div>
                    <div class="card-body">
                        <address>
                            {{ $order->shipping_address['street'] }}<br>
                            {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} {{ $order->shipping_address['zip'] }}<br>
                            {{ $order->shipping_address['country'] }}
                        </address>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Billing Address</h5>
                    </div>
                    <div class="card-body">
                        <address>
                            {{ $order->billing_address['street'] }}<br>
                            {{ $order->billing_address['city'] }}, {{ $order->billing_address['state'] }} {{ $order->billing_address['zip'] }}<br>
                            {{ $order->billing_address['country'] }}
                        </address>
                    </div>
                </div>
            </div>
        </div>

        @if($order->notes)
        <div class="card mt-4">
            <div class="card-header">
                <h5>Order Notes</h5>
            </div>
            <div class="card-body">
                <p>{{ $order->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>Order Summary</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>{{ \App\Services\CurrencyService::format($order->total_amount) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Shipping:</span>
                    <span>Free</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Total:</strong>
                    <strong>${{ number_format($order->total_amount, 2) }}</strong>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body text-center">
                <h6>Thank you for your order!</h6>
                <p class="text-muted small">We'll send you a confirmation email shortly.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
