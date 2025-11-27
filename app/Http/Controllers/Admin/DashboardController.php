<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Affiliate;
use App\Models\AffiliateCommission;
use App\Models\AffiliatePayout;
use App\Models\Ticket;
use App\Enums\TicketStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
	public function index(Request $request)
	{
		$from = $request->query('from') ? Carbon::parse($request->query('from'))->startOfDay() : Carbon::now()->startOfMonth();
		$to = $request->query('to') ? Carbon::parse($request->query('to'))->endOfDay() : Carbon::now()->endOfMonth();

		// Handle AJAX requests for widget updates
		if ($request->ajax() || $request->wantsJson()) {
			return response()->json([
				'widgets' => [
					'totalSales' => \App\Services\CurrencyService::format(Order::whereBetween('created_at', [$from, $to])->sum('total_amount')),
					'totalOrders' => number_format(Order::whereBetween('created_at', [$from, $to])->count()),
					'totalCustomers' => number_format(User::count()),
					'totalProducts' => number_format(Product::count()),
					'totalAffiliates' => number_format(Affiliate::count()),
					'activeAffiliates' => number_format(Affiliate::where('status', 'active')->count()),
					'commissionsPaid' => \App\Services\CurrencyService::format(AffiliateCommission::where('status', 'paid')->sum('amount')),
					'pendingPayouts' => \App\Services\CurrencyService::format(AffiliatePayout::where('status', 'pending')->sum('total_amount')),
					'totalTickets' => number_format(Ticket::count()),
					'openTickets' => number_format(Ticket::whereIn('status', [TicketStatus::OPEN, TicketStatus::IN_PROGRESS])->count()),
					'lowStock' => number_format(Product::where('stock_quantity', '>', 0)->where('stock_quantity', '<=', 10)->count()),
					'outOfStock' => number_format(Product::where('stock_quantity', 0)->count()),
					'stockValue' => \App\Services\CurrencyService::format(Product::select(DB::raw('SUM(stock_quantity * price) as total'))->first()->total ?? 0),
				],
				'notifications' => auth()->user()->notifications()
					->orderBy('created_at', 'desc')
					->limit(5)
					->get()
					->map(function($notification) {
						$data = $notification->data;
						return [
							'title' => $data['title'] ?? 'Notification',
							'icon' => $data['icon'] ?? 'solar:bell-bold-duotone',
							'icon_color' => $data['icon_color'] ?? 'primary',
							'url' => $data['url'] ?? '#',
							'created_at' => $notification->created_at->diffForHumans(),
						];
					})
			]);
		}

		$totalSales = Order::whereBetween('created_at', [$from, $to])->sum('total_amount');
		$totalOrders = Order::whereBetween('created_at', [$from, $to])->count();
		totalCustomers:
		$totalCustomers = User::count();
		totalProducts:
		$totalProducts = Product::count();

		$salesSeries = Order::select(DB::raw('DATE(created_at) as d'), DB::raw('SUM(total_amount) as total'))
			->whereBetween('created_at', [$from, $to])
			->groupBy('d')
			->orderBy('d')
			->get();

		$ordersSummary = [
			'delivered' => Order::where('status', 'delivered')->whereBetween('created_at', [$from, $to])->count(),
			'cancelled' => Order::where('status', 'cancelled')->whereBetween('created_at', [$from, $to])->count(),
			'rejected' => Order::where('status', 'rejected')->whereBetween('created_at', [$from, $to])->count(),
			'pending' => Order::where('status', 'pending')->whereBetween('created_at', [$from, $to])->count(),
		];

		$topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as qty'))
			->groupBy('product_id')
			->orderByDesc('qty')
			->with('product')
			->limit(8)
			->get();

		$topCustomers = Order::select('customer_email', 'customer_name', DB::raw('COUNT(*) as orders'))
			->groupBy('customer_email', 'customer_name')
			->orderByDesc('orders')
			->limit(8)
			->get();

		// Affiliate Statistics
		$totalAffiliates = Affiliate::count();
		$activeAffiliates = Affiliate::where('status', 'active')->count();
		$totalCommissionsPaid = AffiliateCommission::where('status', 'paid')->sum('amount');
		$pendingPayouts = AffiliatePayout::where('status', 'pending')->sum('total_amount');
		
		// Affiliate commission trends
		$affiliateCommissionsSeries = AffiliateCommission::select(
				DB::raw('DATE(created_at) as d'),
				DB::raw('SUM(amount) as total')
			)
			->whereBetween('created_at', [$from, $to])
			->groupBy('d')
			->orderBy('d')
			->get();

		// Support Ticket Statistics
		$totalTickets = Ticket::count();
		$openTickets = Ticket::whereIn('status', [TicketStatus::OPEN, TicketStatus::IN_PROGRESS])->count();
		$pendingTickets = Ticket::where('status', TicketStatus::AWAITING_CUSTOMER)->count();
		$resolvedTickets = Ticket::where('status', TicketStatus::RESOLVED)->count();
		
		$ticketStatusSummary = [
			'open' => Ticket::where('status', TicketStatus::OPEN)->count(),
			'in_progress' => Ticket::where('status', TicketStatus::IN_PROGRESS)->count(),
			'awaiting_customer' => Ticket::where('status', TicketStatus::AWAITING_CUSTOMER)->count(),
			'resolved' => Ticket::where('status', TicketStatus::RESOLVED)->count(),
			'closed' => Ticket::where('status', TicketStatus::CLOSED)->count(),
		];

		// Recent tickets
		$recentTickets = Ticket::with(['customer:id,name,email', 'assignee:id,name'])
			->latest()
			->limit(5)
			->get();

		// Inventory Statistics
		$lowStockThreshold = 10; // Configurable threshold
		$lowStockProducts = Product::where('stock_quantity', '>', 0)
			->where('stock_quantity', '<=', $lowStockThreshold)
			->count();
		$outOfStockProducts = Product::where('stock_quantity', 0)->count();
		$stockValue = Product::select(DB::raw('SUM(stock_quantity * price) as total'))
			->first()
			->total ?? 0;

		// Recent orders
		$recentOrders = Order::latest()
			->limit(5)
			->get();

		// Recent notifications
		$recentNotifications = auth()->user()->notifications()
			->orderBy('created_at', 'desc')
			->limit(5)
			->get();

		// Revenue breakdown
		$affiliateRevenue = Order::whereNotNull('affiliate_id')
			->whereBetween('created_at', [$from, $to])
			->sum('total_amount');
		$directRevenue = Order::whereNull('affiliate_id')
			->whereBetween('created_at', [$from, $to])
			->sum('total_amount');
		$totalCommissionsPaidPeriod = AffiliateCommission::where('status', 'paid')
			->whereBetween('created_at', [$from, $to])
			->sum('amount');

		return view('admin.dashboard', compact(
			'from', 'to', 'totalSales', 'totalOrders', 'totalCustomers', 'totalProducts',
			'salesSeries', 'ordersSummary', 'topProducts', 'topCustomers',
			'totalAffiliates', 'activeAffiliates', 'totalCommissionsPaid', 'pendingPayouts',
			'affiliateCommissionsSeries',
			'totalTickets', 'openTickets', 'pendingTickets', 'resolvedTickets', 'ticketStatusSummary',
			'recentTickets', 'recentOrders', 'recentNotifications',
			'lowStockProducts', 'outOfStockProducts', 'stockValue',
			'affiliateRevenue', 'directRevenue', 'totalCommissionsPaidPeriod'
		));
	}
}
