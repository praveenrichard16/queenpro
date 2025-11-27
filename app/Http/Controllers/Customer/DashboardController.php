<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $baseOrdersQuery = Order::query()->where('customer_email', $user->email);

        $ordersCount = (clone $baseOrdersQuery)->count();
        $inTransitCount = (clone $baseOrdersQuery)->where('status', 'in_transit')->count();
        $deliveredCount = (clone $baseOrdersQuery)->where('status', 'delivered')->count();

        $recentOrders = (clone $baseOrdersQuery)->latest()->take(5)->get();

        $ticketsQuery = Ticket::query()
            ->where('customer_id', $user->id)
            ->latest();

        $openTicketsCount = (clone $ticketsQuery)->whereIn('status', ['open', 'in_progress', 'awaiting_customer'])->count();
        $recentTickets = $ticketsQuery->take(5)->get();

        $recentNotifications = $user->notifications()
            ->whereIn('data->type', ['order_status_changed', 'ticket_replied'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboards.customer', [
            'ordersCount' => $ordersCount,
            'inTransitCount' => $inTransitCount,
            'deliveredCount' => $deliveredCount,
            'recentOrders' => $recentOrders,
            'openTicketsCount' => $openTicketsCount,
            'recentTickets' => $recentTickets,
            'recentNotifications' => $recentNotifications,
        ]);
    }
}

