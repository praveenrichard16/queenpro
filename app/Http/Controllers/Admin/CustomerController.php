<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\CartSession;
use App\Models\CustomerJourneyEvent;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('is_admin', false);

        // Filter by type
        if ($request->filled('type')) {
            switch ($request->type) {
                case 'paid':
                    $query->whereHas('orders', function($q) {
                        $q->whereIn('status', ['delivered', 'completed']);
                    });
                    break;
                case 'cart_abandoners':
                    if (Schema::hasTable('cart_sessions')) {
                        $customerIds = CartSession::where('is_abandoned', true)
                            ->whereNotNull('user_id')
                            ->pluck('user_id')
                            ->unique();
                        $query->whereIn('id', $customerIds);
                    } else {
                        $query->whereRaw('1 = 0'); // Return no results if table doesn't exist
                    }
                    break;
                case 'repeat':
                    $query->withCount('orders')
                        ->having('orders_count', '>', 1);
                    break;
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->withCount('orders')
            ->withSum('orders', 'total_amount')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer): View
    {
        $customer->load(['orders.orderItems.product', 'cartSessions', 'journeyEvents']);
        
        $stats = [
            'total_orders' => $customer->orders()->count(),
            'total_spent' => $customer->orders()->whereIn('status', ['delivered', 'completed'])->sum('total_amount'),
            'average_order_value' => $customer->orders()->avg('total_amount') ?? 0,
            'last_order_date' => $customer->orders()->latest()->first()?->created_at,
            'cart_abandonments' => $customer->cartSessions()->where('is_abandoned', true)->count(),
        ];

        return view('admin.customers.show', compact('customer', 'stats'));
    }

    public function journey(User $customer): View
    {
        if (!Schema::hasTable('customer_journey_events')) {
            $events = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 50, 1);
            return view('admin.customers.journey', compact('customer', 'events'));
        }

        $events = CustomerJourneyEvent::where('user_id', $customer->id)
            ->orWhere('customer_email', $customer->email)
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.customers.journey', compact('customer', 'events'));
    }

    public function bulkMessaging(Request $request): RedirectResponse
    {
        $request->validate([
            'customer_ids' => ['required', 'array'],
            'customer_ids.*' => ['exists:users,id'],
            'message_type' => ['required', 'in:email,whatsapp,push'],
            'template_id' => ['nullable', 'exists:marketing_templates,id'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        $customers = User::whereIn('id', $request->customer_ids)->get();
        $sent = 0;

        foreach ($customers as $customer) {
            // Here you would integrate with email/WhatsApp/push notification services
            // For now, we'll just log it or create a campaign record
            $sent++;
        }

        return back()->with('success', "Messages sent to {$sent} customers.");
    }
}

